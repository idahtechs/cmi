import requests
from config import config


def get(url: str, params):
    headers = dict(Authorization=f"Bearer {config.TIKHUB_KEY}")
    api = f'{config.TIKHUB_API.rstrip('/')}/{url.lstrip('/')}'
    resp = requests.get(api, headers=headers, params=params)
    resp.raise_for_status()
    return resp.json()
