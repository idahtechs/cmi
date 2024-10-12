from models.base import Info


class DouyinInfo(Info):
    audio_urls: list[str]
    duration: int  # 毫秒
