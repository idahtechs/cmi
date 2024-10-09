from config import config
from flask import Flask
from flask_caching import Cache

cache = Cache(
    config={
        "CACHE_TYPE": "RedisCache",
        "CACHE_REDIS_HOST": config.CACHE_REDIS_HOST,
        "CACHE_REDIS_PORT": config.CACHE_REDIS_PORT,
        "CACHE_REDIS_DB": config.CACHE_REDIS_DB,
        "CACHE_DEFAULT_TIMEOUT": 24 * 60 * 60,
        "CACHE_KEY_PREFIX": "CMI_CRAWLER:",
    }
)


def init_app(app: Flask):
    cache.init_app(app)
