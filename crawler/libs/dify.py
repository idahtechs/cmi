import requests
from config import config
from flask import current_app


def correct_typo(content: str):
    # 使用 dify 的 workflow 做 错别字修正和添加标点符号
    try:
        headers = dict(
            Authorization=f"Bearer {config.CORRECT_TYPO_WORKFLOW_KEY}",
        )
        resp = requests.post(
            config.CORRECT_TYPO_WORKFLOW_API,
            headers=headers,
            json=dict(
                inputs={"content": content},
                response_mode="blocking",
                user=f"cmi-crawler-{config.APP_ENV}",
            ),
        )
        resp.raise_for_status()
        resp = resp.json()
        data = resp["data"]
        if data["error"]:
            current_app.logger.error(f"dify.correct_typo 未返回成功响应, {resp=}")
            return content

        return data["outputs"]["text"]
    except Exception as e:
        current_app.logger.error(f"调用 dify.correct_typo 出错, {e=}")
        return content
