# AI Class
The ai class is part of the local_ai_connector namespace and provides functionality for interacting with AI models and making requests to AI APIs.

# Class Properties
**$apikey**: Stores the API key required for authentication with AI services.
**$model:** Represents the AI model used for generating responses.
**$temperature:** Controls the randomness of generated responses.
**$max_length**: Specifies the maximum length of the generated response.
**$top_p:** Determines the cumulative probability cutoff for the generated response.
**$frequency_penalty**: Adjusts the penalty for frequently used tokens in the generated response.
**$presence_penalty:** Adjusts the penalty for tokens already present in the prompt in the generated response.

# API Keys:
For ChatGPT and DALL-E services you can retrieve your API key from: https://platform.openai.com/account/api-keys
For Stable Diffusion get your API key from here: https://deepai.org/dashboard/profile

## Methods:
prompt($prompttext): Generates a response using the AI model and the given prompt text. Returns the generated response.

prompt_dalle($prompttext, $image = null): Generates an image or image edit based on the given prompt text and optional image. Returns the URL of the generated image.

prompt_stable_diffusion($prompttext): Uses the Stable Diffusion AI API to generate an image based on the given prompt text. Returns the URL of the generated image.


## Usage
To use the ai class, follow these steps:

**Create an instance of the ai class.
**$ai = new \local_ai_connector\ai\ai();

**Call the desired method on the instance. **
$response = $ai->prompt('Please generate a response for this prompt.');
$imageUrl = $ai->prompt_dalle('Generate an image based on this prompt.', $imageData);
$diffusionUrl = $ai->prompt_stable_diffusion('Generate an image using Stable Diffusion.');

## Configuration Settings
To configure the AI class and customize its behavior, you can use the following settings:

**OpenAI API Key**: Provide the API key for authentication with OpenAI services.
**Stable Diffusion API Key**: Provide the API key for authentication with Stable Diffusion AI services.
**Source of Truth:** Specify the source of truth for the AI model.
**Model**: Select the AI model to be used for generating responses. The default value is 'text-babbage-001'.
**Temperature**: Set the temperature value to control the randomness of generated responses.
**Max Length**: Set the maximum length of the generated response.
**Top P**: Set the cumulative probability cutoff for the generated response.
**Frequency Penalty**: Adjust the penalty for frequently used tokens in the generated response.
**Presence Penalty**: Adjust the penalty for tokens already present in the prompt in the generated response.
Please note that the availability and functionality of these settings may depend on the specific AI models and APIs used.

### Error Handling
The ai class throws moodle_exception exceptions in case of errors. You should handle these exceptions appropriately to provide meaningful feedback to the user.
