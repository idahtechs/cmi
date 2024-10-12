from models.base import Info


class XhsInfo(Info):
    video_urls: list[str]
    duration: int  # 毫秒
