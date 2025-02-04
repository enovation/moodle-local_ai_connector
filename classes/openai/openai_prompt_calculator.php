<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace local_ai_connector\openai;

use function chr;
use function ord;
use function strlen;

/**
 * Class openai_prompt_calculator
 *
 * This class provides methods to encode and decode text using
 *
 * @see         https://github.com/CodeRevolutionPlugins/GPT-3-Encoder-PHP
 * @package     local_ai_connector
 * @copyright   2022 Szabolcs-Istvan Kisded
 * @author      @CodeRevoltionPlugins
 * @license     https://opensource.org/licenses/MIT MIT license
 */
class openai_prompt_calculator {

    /**
     * Encodes text
     *
     * @param $text string The text to be encoded.
     * @return array The encoded text.
     */
    public function encode(string $text): array {
        $bpetokens = [];
        if (empty($text)) {
            return $bpetokens;
        }

        $rawchars = file_get_contents(dirname(__FILE__) . "/characters.json");
        $byteencoder = json_decode($rawchars, true);
        if (empty($byteencoder)) {
            debugging('Failed to load characters.json: ' . $rawchars);
            return $bpetokens;
        }

        $rencoder = file_get_contents(dirname(__FILE__) . "/encoder.json");
        $encoder = json_decode($rencoder, true);
        if (empty($encoder)) {
            debugging('Failed to load encoder.json: ' . $rencoder);
            return $bpetokens;
        }

        $bpefile = file_get_contents(dirname(__FILE__) . "/vocab.bpe");
        if (empty($bpefile)) {
            debugging('Failed to load vocab.bpe');
            return $bpetokens;
        }

        preg_match_all("#'s|'t|'re|'ve|'m|'ll|'d| ?\p{L}+| ?\p{N}+| ?[^\s\p{L}\p{N}]+|\s+(?!\S)|\s+#u", $text, $matches);
        if (!isset($matches[0]) || count($matches[0]) == 0) {
            debugging('Failed to match string: ' . $text);
            return $bpetokens;
        }

        $lines = preg_split('/\r\n|\r|\n/', $bpefile);
        $bpemerges = [];
        $bpemergestemp = array_slice($lines, 1, count($lines), true);
        foreach ($bpemergestemp as $bmt) {
            $splitbmt = preg_split('#(\s+)#', $bmt);
            $splitbmt = array_filter($splitbmt, [$this, 'gpt_my_filter']);
            if (count($splitbmt) > 0) {
                $bpemerges[] = $splitbmt;
            }
        }
        $bperanks = $this->gpt_dict_zip($bpemerges, range(0, count($bpemerges) - 1));

        $cache = [];
        foreach ($matches[0] as $token) {
            $newtokens = [];
            $chars = [];
            $token = $this->gpt_utf8_encode($token);
            if (function_exists('mb_strlen')) {
                $len = mb_strlen($token, 'UTF-8');
                for ($i = 0; $i < $len; $i++) {
                    $chars[] = mb_substr($token, $i, 1, 'UTF-8');
                }
            } else {
                $chars = str_split($token);
            }

            $resultword = '';
            foreach ($chars as $char) {
                if (isset($byteencoder[$this->gpt_unichr($char)])) {
                    $resultword .= $byteencoder[$this->gpt_unichr($char)];
                }
            }

            $newtokensbpe = $this->gpt_bpe($resultword, $bperanks, $cache);
            $newtokensbpe = explode(' ', $newtokensbpe);
            foreach ($newtokensbpe as $x) {
                if (isset($encoder[$x])) {
                    if (isset($newtokens[$x])) {
                        $newtokens[rand() . '---' . $x] = $encoder[$x];
                    } else {
                        $newtokens[$x] = $encoder[$x];
                    }
                } else {
                    if (isset($newtokens[$x])) {
                        $newtokens[rand() . '---' . $x] = $x;
                    } else {
                        $newtokens[$x] = $x;
                    }
                }
            }

            foreach ($newtokens as $ninx => $nval) {
                if (isset($bpetokens[$ninx])) {
                    $bpetokens[rand() . '---' . $ninx] = $nval;
                } else {
                    $bpetokens[$ninx] = $nval;
                }
            }
        }
        return $bpetokens;
    }

    private function gpt_dict_zip($x, $y): array {
        $result = [];
        $cnt = 0;
        foreach ($x as $i) {
            if (isset($i[1]) && isset($i[0])) {
                $result[$i[0] . ',' . $i[1]] = $cnt;
                $cnt++;
            }
        }
        return $result;
    }

    private function gpt_utf8_encode(string $str): string {
        $str .= $str;
        $len = strlen($str);
        for ($i = $len >> 1, $j = 0; $i < $len; ++$i, ++$j) {
            switch (true) {
                case $str[$i] < "\x80":
                    $str[$j] = $str[$i];
                    break;
                case $str[$i] < "\xC0":
                    $str[$j] = "\xC2";
                    $str[++$j] = $str[$i];
                    break;
                default:
                    $str[$j] = "\xC3";
                    $str[++$j] = chr(ord($str[$i]) - 64);
                    break;
            }
        }
        return substr($str, 0, $j);
    }

    private function gpt_unichr($c) {
        if (ord($c[0]) <= 127) {
            return ord($c[0]);
        }

        if (ord($c[0]) >= 192 && ord($c[0]) <= 223) {
            return (ord($c[0]) - 192) * 64 + (ord($c[1]) - 128);
        }

        if (ord($c[0]) >= 224 && ord($c[0]) <= 239) {
            return (ord($c[0]) - 224) * 4096 + (ord($c[1]) - 128) * 64 + (ord($c[2]) - 128);
        }

        if (ord($c[0]) >= 240 && ord($c[0]) <= 247) {
            return (ord($c[0]) - 240) * 262144 + (ord($c[1]) - 128) * 4096 + (ord($c[2]) - 128) * 64 + (ord($c[3]) - 128);
        }
        if (ord($c[0]) >= 248 && ord($c[0]) <= 251) {
            return (ord($c[0]) - 248) * 16777216 + (ord($c[1]) - 128) * 262144 + (ord($c[2]) - 128) * 4096 +
                    (ord($c[3]) - 128) * 64 + (ord($c[4]) - 128);
        }
        if (ord($c[0]) >= 252 && ord($c[0]) <= 253) {
            return (ord($c[0]) - 252) * 1073741824 + (ord($c[1]) - 128) * 16777216 + (ord($c[2]) - 128) * 262144 +
                    (ord($c[3]) - 128) * 4096 + (ord($c[4]) - 128) * 64 + (ord($c[5]) - 128);
        }
        return 0;
    }

    private function gpt_bpe($token, $bperanks, &$cache) {
        if (array_key_exists($token, $cache)) {
            return $cache[$token];
        }

        $word = $this->gpt_split($token);
        $initlen = count($word);
        $pairs = $this->gpt_get_pairs($word);
        if (!$pairs) {
            return $token;
        }

        while (true) {
            $minpairs = [];
            foreach ($pairs as $pair) {
                if (array_key_exists($pair[0] . ',' . $pair[1], $bperanks)) {
                    $rank = $bperanks[$pair[0] . ',' . $pair[1]];
                    $minpairs[$rank] = $pair;
                } else {
                    $minpairs[10e10] = $pair;
                }
            }
            ksort($minpairs);
            $minkey = array_key_first($minpairs);
            foreach ($minpairs as $mpi => $mp) {
                if ($mpi < $minkey) {
                    $minkey = $mpi;
                }
            }

            $bigram = $minpairs[$minkey];
            if (!array_key_exists($bigram[0] . ',' . $bigram[1], $bperanks)) {
                break;
            }
            $first = $bigram[0];
            $second = $bigram[1];
            $newword = [];
            $i = 0;
            while ($i < count($word)) {
                $j = $this->gpt_index_of($word, $first, $i);
                if ($j === -1) {
                    $newword = array_merge($newword, array_slice($word, $i, null, true));
                    break;
                }
                if ($i > $j) {
                    $slicer = [];
                } else if ($j == 0) {
                    $slicer = [];
                } else {
                    $slicer = array_slice($word, $i, $j - $i, true);
                }
                $newword = array_merge($newword, $slicer);
                if (count($newword) > $initlen) {
                    break;
                }
                $i = $j;
                if ($word[$i] === $first && $i < count($word) - 1 && $word[$i + 1] === $second) {
                    $newword[] = $first . $second;
                    $i = $i + 2;
                } else {
                    $newword[] = $word[$i];
                    $i = $i + 1;
                }
            }
            if ($word == $newword) {
                break;
            }
            $word = $newword;
            if (count($word) === 1) {
                break;
            } else {
                $pairs = $this->gpt_get_pairs($word);
            }
        }
        $word = implode(' ', $word);
        $cache[$token] = $word;
        return $word;
    }

    private function gpt_split($str, $len = 1): array {
        $arr = [];
        if (function_exists('mb_strlen')) {
            $length = mb_strlen($str, 'UTF-8');
        } else {
            $length = strlen($str);
        }

        for ($i = 0; $i < $length; $i += $len) {
            if (function_exists('mb_substr')) {
                $arr[] = mb_substr($str, $i, $len, 'UTF-8');
            } else {
                $arr[] = substr($str, $i, $len);
            }
        }
        return $arr;

    }

    private function gpt_get_pairs($word): array {
        $pairs = [];
        $prevchar = $word[0];
        for ($i = 1; $i < count($word); $i++) {
            $char = $word[$i];
            $pairs[] = [$prevchar, $char];
            $prevchar = $char;
        }
        return $pairs;
    }

    private function gpt_index_of($arrax, $searchelement, $fromindex) {
        foreach ($arrax as $index => $value) {
            if ($index < $fromindex) {
                continue;
            }
            if ($value == $searchelement) {
                return $index;
            }
        }
        return -1;
    }

    /**
     * Decodes text
     *
     * @param $tokens array The tokens to be decoded.
     * @return false|string The decoded text.
     */
    public function decode(array $tokens) {
        $rencoder = file_get_contents(dirname(__FILE__) . "/encoder.json");
        $encoder = json_decode($rencoder, true);
        if (empty($encoder)) {
            debugging('Failed to load encoder.json: ' . $rencoder);
            return false;
        }

        $decoder = [];
        foreach ($encoder as $index => $val) {
            $decoder[$val] = $index;
        }

        $rawchars = file_get_contents(dirname(__FILE__) . "/characters.json");
        $byteencoder = json_decode($rawchars, true);
        if (empty($byteencoder)) {
            debugging('Failed to load characters.json: ' . $rawchars);
            return false;
        }

        $bytedecoder = [];
        foreach ($byteencoder as $index => $val) {
            $bytedecoder[$val] = $index;
        }

        $mycharr = [];
        foreach ($tokens as $myt) {
            if (isset($decoder[$myt])) {
                $mycharr[] = $decoder[$myt];
            } else {
                debugging('Character not found in decoder: ' . $myt);
            }
        }

        $text = implode('', $mycharr);
        $textarr = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        $finalarr = [];
        foreach ($textarr as $txa) {
            if (isset($bytedecoder[$txa])) {
                $finalarr[] = $bytedecoder[$txa];
            } else {
                debugging('Character not found in byte_decoder: ' . $txa);
            }
        }

        $output = '';
        for ($i = 0, $j = count($finalarr); $i < $j; ++$i) {
            $output .= chr($finalarr[$i]);
        }
        return $output;
    }

    private function gpt_my_filter($var): bool {
        return ($var !== null && $var !== false && $var !== '');
    }
}
