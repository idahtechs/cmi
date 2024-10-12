import json

import requests
from config import config
from extensions.ext_cache import get_cache, set_cache
from flask import g
from libs import utils


def get(url: str, params):
    cache_key = f"tikhub_resp:{utils.md5(url)}:{utils.md5(json.dumps(params))}"
    data = get_cache(cache_key)
    if data:
        return data

    headers = dict(Authorization=f"Bearer {config.TIKHUB_KEY}")
    api = f'{config.TIKHUB_API.rstrip('/')}/{url.lstrip('/')}'
    resp = requests.get(api, headers=headers, params=params)
    resp.raise_for_status()
    data = resp.json()
    # 这个data估计挺大的, 后面可以的话, 存进数据库而不是 redis
    set_cache(cache_key, data)
    return data
