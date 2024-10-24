class BaseError(Exception):
    def __init__(self, msg, code=1):
        super().__init__(msg)
        self.message = msg
        self.code = code

    @property
    def dict_info(self):
        return dict(err=self.message, code=self.code)


ShareUrlError = BaseError("分享链接未提供", 2)
XhsNoteIdError = BaseError("无法获取笔记 ID", 3)
BilibiliBvidError = BaseError("无法获取b站视频bv号", 4)
WechatPublicAccountContentError = BaseError("无法获取公众号文章内容", 5)
AudioDurationError = BaseError("音频时长超出限制", 6)
GetAudioError = BaseError("无法获取音频内容", 7)
