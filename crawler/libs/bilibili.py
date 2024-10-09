import re

from extensions.ext_cache import cache
from libs import tikhub
from models.bilibili import BilibiliInfo


# 【《狐妖小红娘月红篇》？我从来没见过这么颠覆性的改编！】 https://www.bilibili.com/video/BV19T421i7rg/?share_source=copy_web
def get_bilibili_bvid_from_url(url: str):
    # 如果是短链接，获取重定向后的链接
    match = re.search(r"bilibili.com/video/([a-zA-Z0-9]+)", url)
    if match:
        return match.group(1)
    return None


def get_bilibili_info(bv_id: str):
    cache_key = f"bilibili:{bv_id}:tikhub_resp"
    info: BilibiliInfo = cache.get(cache_key)
    if info:
        return info

    # 获取基本信息及 cid
    ret1 = tikhub.get(
        "/api/v1/bilibili/web/fetch_one_video",
        {"bv_id": bv_id},
    )
    data1 = ret1["data"]["data"]
    info = BilibiliInfo(
        author=data1["owner"]["name"],
        caption=data1["title"],
        desc=data1["desc"],
        cid=data1["cid"],
        create_time=int(data1["ctime"]),
        duration=int(data1["duration"]) * 1000,
    )

    # 用 bvid 和 cid 获取音频地址
    ret2 = tikhub.get(
        "/api/v1/bilibili/web/fetch_video_playurl",
        {"bv_id": bv_id, "cid": info.cid},
    )
    audios = ret2["data"]["data"]["dash"]["audio"]
    for audio in audios:
        print(audio["base_url"])
        if "akamaized" in audio["base_url"]:
            info.audio_url = audio["base_url"]
            break
    cache.set(cache_key, info)
    return info
