from extensions.ext_cache import cache
from libs import tikhub, utils
from models.douyin import DouyinInfo


def get_douyin_info(share_url: str):
    share_url = share_url.strip()
    cache_key = f"douyin:{utils.md5(share_url)}:tikhub_resp"
    info: DouyinInfo = cache.get(cache_key)
    if info:
        return info

    ret = tikhub.get(
        "/api/v1/douyin/app/v1/fetch_one_video_by_share_url",
        {"share_url": share_url},
    )
    detail = ret["data"]["aweme_detail"]
    info = DouyinInfo(
        author=detail["author"]["nickname"],
        caption=detail["caption"],
        desc=detail["desc"],
        create_time=int(detail["create_time"]),
        audio_url=detail["music"]["play_url"]["uri"],
        duration=int(detail["duration"]),
    )
    cache.set(cache_key, info)
    return info
