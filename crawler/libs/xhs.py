import re

import requests
from extensions.ext_cache import cache
from flask import current_app
from libs import tikhub
from models.xhs import XhsInfo


def get_xhs_note_id_from_url(url: str):
    # 如果是短链接，获取重定向后的链接
    match = re.search(r"https?://xhslink.com/a/[a-zA-Z0-9]+", url)
    if match:
        try:
            response = requests.get(
                match.group(0),
                headers={
                    "User-Agent": "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36"
                },
                allow_redirects=True,
            )
            url = response.url
        except Exception as e:
            current_app.logger.error(f"Error fetching URL: {e}")
            return None

    # 从最终链接中提取 ID
    match = re.search(r"xiaohongshu.com/(?:explore|discovery/item)/([a-f0-9]+)", url)
    if match:
        return match.group(1)
    else:
        match = re.search(r"noteId=([a-f0-9]+)", url)
        if match:
            return match.group(1)
    return None


def get_xhs_info(note_id: str):
    cache_key = f"xhs:{note_id}:tikhub_resp"
    info: XhsInfo = cache.get(cache_key)
    if info:
        return info

    current_app.logger.error(f"get_xhs_info {note_id=}")
    ret = tikhub.get("/api/v1/xiaohongshu/web/get_note_info_v2", {"note_id": note_id})

    # 不确定会不会有多个 note
    note = ret["data"]["data"][0]["note_list"][0]
    if note["model_type"] != "note" or note["type"] != "video":
        current_app.logger.error(f"非小红书视频类型笔记 {note_id=}")
        raise Exception("非小红书视频类型笔记")

    stream = note["video"]["media"]["stream"]
    for _, v in stream.items():
        if v:
            info = XhsInfo(
                author=ret["data"]["data"][0]["user"]["name"],
                caption=note["title"],
                desc=note["desc"],
                create_time=int(note["time"]),
                video_url=v[0]["master_url"],
                duration=int(v[0]["audio_duration"]),
            )
            cache.set(cache_key, info)
            break

    if not info:
        current_app.logger.error(f"无法获取小红书视频 {note_id=}")
        raise Exception("无法获取小红书视频")

    return info
