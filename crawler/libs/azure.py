from config import config
from openai import AzureOpenAI

azure_client = AzureOpenAI(
    api_key=config.AZURE_API_KEY,
    api_version=config.AZURE_API_VERSION,
    azure_endpoint=config.AZURE_ENDPOINT,
)


def audio_to_text(audio_path: str, prompt: str):
    text = azure_client.audio.transcriptions.create(
        model=config.AZURE_MODEL,
        file=open(audio_path, "rb"),
        prompt=prompt,
    ).text
    return text
