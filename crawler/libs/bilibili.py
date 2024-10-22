import re

from extensions.ext_cache import get_cache, set_cache
from flask import current_app
from libs import tikhub, utils
from models.bilibili import BilibiliInfo


# 【《狐妖小红娘月红篇》？我从来没见过这么颠覆性的改编！】 https://www.bilibili.com/video/BV19T421i7rg/?share_source=copy_web
# 【当不喜欢狗狗的超市老板，被一只遗弃小狗赖上后～-哔哩哔哩】 https://b23.tv/2pEZr7Q
def get_bilibili_bvid_from_url(url: str):
    # 如果是短链接，获取重定向后的链接
    short_url_match = re.search(r"https?://b23.tv/[a-zA-Z0-9]+", url)
    if short_url_match:
        url = utils.get_final_redirect_url(short_url_match.group(0))
        if not url:
            return None

    # 从最终链接中提取 BV
    match = re.search(r"bilibili.com/video/([a-zA-Z0-9]+)", url)
    if match:
        return match.group(1)
    return None


def get_bilibili_info(bv_id: str):
    cache_key = f"bilibili_info:{bv_id}"
    info: BilibiliInfo = get_cache(cache_key)
    if info:
        return info

    # 获取基本信息及 cid
    ret1 = tikhub.get(
        "/api/v1/bilibili/web/fetch_one_video",
        {"bv_id": bv_id},
    )
    data1 = ret1["data"]["data"]
    cid = data1["cid"]

    # 用 bvid 和 cid 获取音频地址
    ret2 = tikhub.get(
        "/api/v1/bilibili/web/fetch_video_playurl",
        {"bv_id": bv_id, "cid": cid},
    )
    audios = ret2["data"]["data"]["dash"]["audio"]
    audio_urls = []
    for audio in audios:
        audio_urls = utils.append_or_extend(audio_urls, audio["base_url"])
        audio_urls = utils.append_or_extend(audio_urls, audio["backup_url"])

    info = BilibiliInfo(
        author=data1["owner"]["name"],
        caption=data1["title"],
        desc=data1["desc"],
        cid=cid,
        audio_urls=sorted(list(set(audio_urls))),
        create_time=int(data1["ctime"]),
        duration=int(data1["duration"]) * 1000,
    )

    if len(info.audio_urls) == 0:
        current_app.logger.error(f"无法获取B站视频信息 {bv_id=}")
        raise Exception("无法获取B站视频信息")
    set_cache(cache_key, info)

    return info
