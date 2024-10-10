from pydantic import BaseModel


class PublicAccountArticleInfo(BaseModel):
    title: str
    content: str
