from models.base import Info


class BilibiliInfo(Info):
    cid: int  # 用于获取视频播放地址
    audio_urls: list[str]
    duration: int  # 毫秒
