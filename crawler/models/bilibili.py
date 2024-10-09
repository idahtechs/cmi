from typing import Optional

from models.base import Info


class BilibiliInfo(Info):
    cid: int  # 用于获取视频播放地址
    audio_url: Optional[str] = None
    duration: int  # 毫秒
