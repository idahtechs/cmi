import requests
from config import config


# https://mp.weixin.qq.com/s?__biz=Mzk0NTY0MTkwNQ==&mid=2247484517&idx=1&sn=33a064dd2d90bd4977f2b07651f0ba15&chksm=c31304bbf4648dad1ab3c028a4f5925a11a15b10c5fac4efd2c11becda336276ec5ab50d7159#rd
# https://mp.weixin.qq.com/s?__biz=MzI5MTg1NjA4Nw%3D%3D&idx=1&mid=2247485676&scene=21&sn=c574b76574473d3d3969b6ad6e57078f#wechat_redirect
def run_get_public_account_article_content_workflow(article_url: str):
    # example response
    #     {
    #   "code": 0,
    #   "cost": "0",
    #   "data": "{\"data\":{\"content\":\"“店数通”与国庆游笔记秘方\\n图片\\n图片\\n国庆游笔记秘方\\n图片\\n图片\\n· 七天的诗和远方，“店数通”小红书秘方 ·\\n图片\\n图片\\n图片\\n 现在来吧！\\n 哇\\n图片\\n图片\\nTRAVEL！\\n图片\\n随着国庆长假的日益临近，\\n许多人都在规划着出游方案和路线，\\n找灵感、找参考，绝对少不了小红书，\\n无论你是商家，还是博主，\\nGet这份出游笔记秘方，\\n让商家的你，生意做得轻松有范！\\n让博主的你，国庆假期也更有仪式感!\\n  炫酷功能，立刻启程  \\n图片\\n图片\\n01\\n案例1·古镇茶铺\\n图片\\nTRAVEL\\nTechnology \\n图片\\n图片\\n让好东西焕发新意：天天在群发，来自淳朴小镇，原生态好茶，柠檬草茶好清新、乌龙茶好有香韵、普洱茶特别回甘，再来就是促销、特惠、大降价~ \\n一句“家人们”明明情真意切，就是没人搭理，一点流量也没呢！\\n来试试 “店数通”小程序\\nAI热点仿写：选择对标的、热度高的账号内容，一键把链接复制；然后选择目标的主题，以及行文的风格，简单三下，就能让“店数通”理解你的需求了~\\n图片\\n图片\\n图片\\n标题、正文、配图说明一条龙：可根据你输入的信息生成亮眼的、切题的标题，还有丰富有内涵，格式也已经编排优化好的完整内容，配上详细的配图说明来添加自己的图片，这一切都那么的自然而简单！\\n图片\\n图片\\n违禁词检测，清新护航：除了生成的内容，自己也想挥毫一下文采的，也可以借助这个违禁词检测功能，避免平台审查的不必要麻烦。\\n图片\\n图片\\nNATIONAL DAY \\n图片\\n02\\n案例2·风格带货\\n图片\\n图片\\nNATIONAL DAY 2024\\n图片\\n差异化风格：单纯的景点介绍，商品的生硬推销，实在发挥不了小红书的优势，所以我们做攻略型，输出直击人心的人设内容，配合我们的配图建议，完成整个风格化、差异化的攻略。\\n优雅种草：让我们带的货更上格调，好文配好货显得更为合理自然，坚持多发，迎来涨粉和拔草时光吧~\\n图片\\n图片\\n图片\\n图片\\n图片\\nNATIONAL DAY \\n图片\\n03\\n笔记秘方等你来试\\n图片\\n图片\\n图片\\n图片\\n图片\\n图片\\n图片\\n图片\\nNATIONAL DAY \\n图片\\n-END-\",\"title\":\"“店数通”与国庆游笔记秘方\"},\"error_code\":\"\",\"error_msg\":\"\"}",
    #   "debug_url": "https://www.coze.cn/work_flow?execute_id=7424039781321654287&space_id=7424030023172472886&workflow_id=7424031599526559781",
    #   "msg": "Success",
    #   "token": 0
    # }

    headers = {
        "Authorization": f"Bearer {config.COZE_GET_PUBLIC_ACCOUNT_ARTICLE_CONTENT_KEY}",
        "Content-Type": "application/json",
    }
    data = {
        "workflow_id": config.COZE_GET_PUBLIC_ACCOUNT_ARTICLE_CONTENT_WORKFLOW_ID,
        "parameters": {"article_url": article_url},
    }
    resp = requests.post(
        "https://api.coze.cn/v1/workflow/run",
        headers=headers,
        json=data,
    )
    resp.raise_for_status()
    return resp.json()
