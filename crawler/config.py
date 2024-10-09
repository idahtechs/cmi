import os

import dotenv

dotenv.load_dotenv()

DEFAULTS = {}


def get_env(key):
    return os.environ.get(key, DEFAULTS.get(key))


def get_bool_env(key):
    value = get_env(key)
    return value.lower() == "true" if value is not None else False


class Config:
    """Application configuration class."""

    def __init__(self):
        self.API_KEY = get_env("API_KEY")
        self.TIKHUB_API = get_env("TIKHUB_API")
        self.TIKHUB_KEY = get_env("TIKHUB_KEY")
        self.AUDIO_DURATION_LIMIT = int(get_env("AUDIO_DURATION_LIMIT"))
        self.AZURE_ENDPOINT = get_env("AZURE_ENDPOINT")
        self.AZURE_API_KEY = get_env("AZURE_API_KEY")
        self.AZURE_API_VERSION = get_env("AZURE_API_VERSION")
        self.AZURE_MODEL = get_env("AZURE_MODEL")
        self.CACHE_REDIS_HOST = get_env("CACHE_REDIS_HOST")
        self.CACHE_REDIS_PORT = get_env("CACHE_REDIS_PORT")
        self.CACHE_REDIS_DB = get_env("CACHE_REDIS_DB")


config = Config()
