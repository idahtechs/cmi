import re

from extensions.ext_cache import get_cache, set_cache
from flask import current_app
from libs import tikhub, utils
from models.xhs import XhsInfo


def get_xhs_note_id_from_url(url: str):
    # 如果是短链接，获取重定向后的链接
    short_url_match = re.search(r"https?://xhslink.com/a/[a-zA-Z0-9]+", url)
    if short_url_match:
        url = utils.get_final_redirect_url(short_url_match.group(0))
        if not url:
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


# https://www.xiaohongshu.com/explore/66c92cd0000000001d0172b9?xsec_token=ABpmX71NmjIiB8zggR3CNgeZsMLsXiC18Y3htWHR0Tkq4=%26xsec_source=pc_feed
# http://xhslink.com/a/4M913hr1KUaY
def get_xhs_info(note_id: str):
    cache_key = f"xhs_info:{note_id}"
    info: XhsInfo = get_cache(cache_key)
    if info:
        return info

    current_app.logger.error(f"get_xhs_info {note_id=}")
    ret = tikhub.get("/api/v1/xiaohongshu/web/get_note_info_v2", {"note_id": note_id})

    # 不确定会不会有多个 note
    note = ret["data"]["data"][0]["note_list"][0]
    if note["model_type"] != "note" or note["type"] != "video":
        current_app.logger.error(f"非小红书视频类型笔记 {note_id=}")
        raise Exception("非小红书视频类型笔记")

    video_urls = []
    for _, v in note["video"]["media"]["stream"].items():
        for vv in v:
            if vv:
                video_urls = utils.append_or_extend(video_urls, vv["master_url"])
                video_urls = utils.append_or_extend(video_urls, vv["backup_urls"])

    info = XhsInfo(
        author=ret["data"]["data"][0]["user"]["name"],
        caption=note["title"],
        desc=note["desc"],
        create_time=int(note["time"]),
        video_urls=sorted(list(set(video_urls))),
        duration=int(note["video"]["media"]["video"]["duration"]) * 1000,
    )

    if len(info.video_urls) == 0:
        current_app.logger.error(f"无法获取小红书视频信息 {note_id=}")
        raise Exception("无法获取小红书视频信息")
    set_cache(cache_key, info)

    return info
