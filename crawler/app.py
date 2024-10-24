import traceback
from functools import wraps

from config import config
from extensions import ext_cache
from extensions.ext_cache import get_cache, set_cache
from flask import Flask, g, request
from libs import bilibili, dify, douyin, error, utils, wechat, xhs
from libs.azure import audio_to_text

app = Flask(__name__)
app.config.from_object(config)
ext_cache.init_app(app)


# 中间件
def api_key_required(func):
    @wraps(func)
    def wrapper(*args, **kwargs):
        if request.headers.get("x-api-key") != config.API_KEY:
            return {"message": "Unauthorized"}, 401
        return func(*args, **kwargs)

    return wrapper


def parse_json_body(func):
    @wraps(func)
    def wrapper(*args, **kwargs):
        share_url = request.json.get("share_url")
        if not share_url:
            return dict(err="share_url is required", code=2)
        g.share_url = share_url
        g.ignore_cache = str(request.json.get("ignore_cache")) == "1"
        return func(*args, **kwargs)

    return wrapper


@app.errorhandler(Exception)
def handle_exception(e):
    app.logger.error(traceback.format_exc())
    app.logger.error(f"Request body: {request.get_data()}")
    return error.BaseError(str(e)).dict_info


@app.route("/")
@app.route("/healthcheck")
def ping():
    return "pong"


@app.post("/douyin_preview_info")
@api_key_required
@parse_json_body
def douyin_preview_info():
    info = douyin.get_douyin_info(g.get("share_url"))
    return dict(
        **info.model_dump(),
        code=0,
    )


@app.post("/douyin_to_text")
@api_key_required
@parse_json_body
def douyin_to_text():
    share_url = g.get("share_url")

    info = douyin.get_douyin_info(share_url)
    if info.duration // 1000 > config.AUDIO_DURATION_LIMIT:
        return error.AudioDurationError.dict_info

    cache_key = f"douyin:transcription:{utils.md5(share_url)}"
    text = get_cache(cache_key)
    if not text:
        try:
            valid_audio_url = utils.get_valid_url(info.audio_urls)
            audio_path = utils.download_file(valid_audio_url)
            assert audio_path is not None
        except Exception as e:
            app.log_exception(e)
            return error.GetAudioError.dict_info

        text = audio_to_text(
            audio_path=audio_path,
            prompt="以下是普通话的句子，这是一段抖音音频。",
        )
        text = dify.correct_typo(text)
        set_cache(cache_key, text)
        utils.remove_files(audio_path)
    return dict(text=text, info=info.model_dump(), code=0)


@app.post("/xhs_preview_info")
@api_key_required
@parse_json_body
def xhs_preview_info():
    note_id = xhs.get_xhs_note_id_from_url(g.get("share_url"))
    if not note_id:
        return error.XhsNoteIdError.dict_info

    info = xhs.get_xhs_info(note_id)
    return dict(
        **info.model_dump(),
        code=0,
    )


@app.post("/xhs_to_text")
@api_key_required
@parse_json_body
def xhs_to_text():
    share_url = g.get("share_url")
    note_id = xhs.get_xhs_note_id_from_url(share_url)
    if not note_id:
        return error.XhsNoteIdError.dict_info

    info = xhs.get_xhs_info(note_id)
    if info.duration // 1000 > config.AUDIO_DURATION_LIMIT:
        return error.AudioDurationError.dict_info

    cache_key = f"xhs:transcription:{note_id}"
    text = get_cache(cache_key)
    if not text:
        try:
            valid_video_url = utils.get_valid_url(info.video_urls)
            video_path = utils.download_file(valid_video_url)
            audio_path = utils.extract_audio_from_video(video_path)
            assert audio_path is not None
        except Exception as e:
            app.log_exception(e)
            return error.GetAudioError.dict_info

        text = audio_to_text(
            audio_path=audio_path,
            prompt="以下是普通话的句子，这是一段小红书音频。",
        )
        text = dify.correct_typo(text)
        set_cache(cache_key, text)
        utils.remove_files([video_path, audio_path])
    return dict(text=text, info=info.model_dump(), code=0)


@app.post("/bilibili_preview_info")
@api_key_required
@parse_json_body
def bilibili_preview_info():
    bv_id = bilibili.get_bilibili_bvid_from_url(g.get("share_url"))
    if not bv_id:
        return error.BilibiliBvidError.dict_info

    info = bilibili.get_bilibili_info(bv_id)
    return dict(
        **info.model_dump(),
        code=0,
    )


@app.post("/bilibili_to_text")
@api_key_required
@parse_json_body
def bilibili_to_text():
    bv_id = bilibili.get_bilibili_bvid_from_url(g.get("share_url"))
    if not bv_id:
        return error.BilibiliBvidError.dict_info

    info = bilibili.get_bilibili_info(bv_id)
    if info.duration // 1000 > config.AUDIO_DURATION_LIMIT:
        return error.AudioDurationError.dict_info

    cache_key = f"bilibili:transcription:{bv_id}"
    text = get_cache(cache_key)
    if not text:
        try:
            valid_audio_url = utils.get_valid_url(info.audio_urls)
            audio_path = utils.download_file(valid_audio_url)
            # 转换成mp3格式
            mp3_path = utils.extract_audio_from_video(video_path=audio_path)
            assert mp3_path is not None
        except Exception as e:
            app.log_exception(e)
            return error.GetAudioError.dict_info

        text = audio_to_text(
            audio_path=mp3_path,
            prompt="以下是普通话的句子，这是一段B站音频。",
        )
        text = dify.correct_typo(text)
        set_cache(cache_key, text)
        utils.remove_files([audio_path, mp3_path])
    return dict(text=text, info=info.model_dump(), code=0)


@app.post("/wechat_public_account_article_to_text")
@api_key_required
@parse_json_body
def wechat_public_account_article_to_text():
    share_url = g.get("share_url")
    info = wechat.get_public_account_article_content(share_url)
    if not info:
        return error.WechatPublicAccountContentError.dict_info

    return dict(title=info.title, text=info.content, code=0)
