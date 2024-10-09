from pydantic import BaseModel


class Info(BaseModel):
    author: str  # 用户名
    caption: str  # 或者说是 title
    desc: str
    create_time: int  # 时间戳
