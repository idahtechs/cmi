from extensions.ext_cache import get_cache, set_cache
from flask import current_app
from libs import tikhub, utils
from models.douyin import DouyinInfo


# https://www.douyin.com/video/7415925418064186643
def get_douyin_info(share_url: str):
    share_url = share_url.strip()
    cache_key = f"douyin_info:{utils.md5(share_url)}"
    info: DouyinInfo = get_cache(cache_key)
    if info:
        return info

    ret = tikhub.get(
        "/api/v1/douyin/app/v1/fetch_one_video_by_share_url",
        {"share_url": share_url},
    )
    detail = ret["data"]["aweme_detail"]
    audio_urls = utils.append_or_extend([], detail["music"]["play_url"]["uri"])
    audio_urls = utils.append_or_extend(
        audio_urls, detail["music"]["play_url"]["url_list"]
    )
    info = DouyinInfo(
        author=detail["author"]["nickname"],
        caption=detail["caption"],
        desc=detail["desc"],
        create_time=int(detail["create_time"]),
        audio_urls=sorted(list(set(audio_urls))),
        duration=int(detail["duration"]),
    )

    if len(info.audio_urls) == 0:
        current_app.logger.error(f"无法获取抖音视频信息 {share_url=}")
        raise Exception("无法获取抖音视频信息")
    set_cache(cache_key, info)

    return info
