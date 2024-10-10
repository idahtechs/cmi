import json

from extensions.ext_cache import cache
from libs import coze, utils
from models.wechat import PublicAccountArticleInfo


def get_public_account_article_content(share_url: str, ignore_cache: bool = False):
    share_url = share_url.strip()
    cache_key = f"wechat:public_account_content:{utils.md5(share_url)}:coze_resp"
    info: PublicAccountArticleInfo = cache.get(cache_key)
    if info and not ignore_cache:
        return info

    ret = coze.run_get_public_account_article_content_workflow(share_url)

    if ret["code"] != 0:
        print(f"调用 coze.run_get_public_account_article_content_workflow 出错, {ret=}")
        return None

    workflow_ret = json.loads(ret["data"])
    if not workflow_ret["data"]:
        print(f"无法获取公众号文章内容, {ret=}")
        return None

    # if "已被发布者删除" in workflow_ret["data"]["title"]:
    #     print(f"该内容已被发布者删除, {ret=}")
    #     return None

    info = PublicAccountArticleInfo(
        title=workflow_ret["data"]["title"],
        content=workflow_ret["data"]["content"],
    )
    cache.set(cache_key, info)
    return info
