import json

from extensions.ext_cache import get_cache, set_cache
from flask import current_app, g
from libs import coze, dify, utils
from models.wechat import PublicAccountArticleInfo


def get_public_account_article_content(share_url: str):
    share_url = share_url.strip()
    cache_key = f"wechat:public_account_content_info:{utils.md5(share_url)}"
    info: PublicAccountArticleInfo = get_cache(cache_key)
    if info and not g.ignore_cache:
        return info

    ret = coze.run_get_public_account_article_content_workflow(share_url)

    if ret["code"] != 0:
        current_app.logger.error(
            f"调用 coze.run_get_public_account_article_content_workflow 出错, {ret=}"
        )
        return None

    workflow_ret = json.loads(ret["data"])
    if not workflow_ret["data"]:
        current_app.logger.error(f"无法获取公众号文章内容, {ret=}")
        return None

    # if "已被发布者删除" in workflow_ret["data"]["title"]:
    #     current_app.logger.error(f"该内容已被发布者删除, {ret=}")
    #     return None

    content = dify.correct_typo(workflow_ret["data"]["content"])
    info = PublicAccountArticleInfo(
        title=workflow_ret["data"]["title"],
        content=content,
    )
    set_cache(cache_key, info)
    return info
