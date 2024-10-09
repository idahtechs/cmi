import traceback
from functools import wraps

from config import config
from extensions import ext_cache
from extensions.ext_cache import cache
from flask import Flask, request
from libs import bilibili, douyin, utils, xhs
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


def share_url_required(func):
    @wraps(func)
    def wrapper(*args, **kwargs):
        share_url = request.args.get("share_url")
        if not share_url:
            return dict(err="share_url is required", code=2)
        return func(*args, **kwargs)

    return wrapper


@app.errorhandler(Exception)
def handle_exception(e):
    print(traceback.format_exc())
    return dict(err=str(e), code=1)


@app.route("/")
@app.route("/healthcheck")
def ping():
    return "pong"


@app.route("/douyin_preview_info")
@api_key_required
@share_url_required
def douyin_preview_info():
    info = douyin.get_douyin_info(request.args.get("share_url"))
    return dict(
        **info.model_dump(),
        code=0,
    )


@app.route("/douyin_to_text")
@api_key_required
@share_url_required
def douyin_to_text():
    share_url = request.args.get("share_url")

    info = douyin.get_douyin_info(share_url)
    if info.duration // 1000 > config.AUDIO_DURATION_LIMIT:
        return dict(err="音频长度超过限制", code=1)

    cache_key = f"douyin:{utils.md5(share_url)}:transcription"
    text = cache.get(cache_key)
    if not text or request.args.get("ignore_cache") == "1":
        audio_path = utils.download_file(info.audio_url)
        text = audio_to_text(
            audio_path=audio_path,
            prompt="以下是普通话的句子，这是一段抖音音频。",
        )
        cache.set(cache_key, text)
        utils.remove_files(audio_path)
    return dict(text=text, info=info.model_dump(), code=0)


@app.route("/xhs_preview_info")
@api_key_required
@share_url_required
def xhs_preview_info():
    note_id = xhs.get_xhs_note_id_from_url(request.args.get("share_url"))
    if not note_id:
        return dict(err="无法获取笔记 ID", code=3)

    info = xhs.get_xhs_info(note_id)
    return dict(
        **info.model_dump(),
        code=0,
    )


@app.route("/xhs_to_text")
@api_key_required
@share_url_required
def xhs_to_text():
    share_url = request.args.get("share_url")
    note_id = xhs.get_xhs_note_id_from_url(share_url)
    if not note_id:
        return dict(err="无法获取笔记 ID", code=3)

    info = xhs.get_xhs_info(note_id)
    if info.duration // 1000 > config.AUDIO_DURATION_LIMIT:
        return dict(err="音频长度超过限制", code=4)

    cache_key = f"xhs:{note_id}:transcription"
    text = cache.get(cache_key)
    if not text or request.args.get("ignore_cache") == "1":
        video_path = utils.download_file(info.video_url)
        audio_path = utils.extract_audio_from_video(video_path)
        if not audio_path:
            return dict(err="无法获取音频", code=5)
        text = audio_to_text(
            audio_path=audio_path,
            prompt="以下是普通话的句子，这是一段小红书音频。",
        )
        cache.set(cache_key, text)
        utils.remove_files([video_path, audio_path])
    return dict(text=text, info=info.model_dump(), code=0)


@app.route("/bilibili_preview_info")
@api_key_required
@share_url_required
def bilibili_preview_info():
    bv_id = bilibili.get_bilibili_bvid_from_url(request.args.get("share_url"))
    if not bv_id:
        return dict(err="无法获取b站视频bv号", code=3)

    info = bilibili.get_bilibili_info(bv_id)
    return dict(
        **info.model_dump(),
        code=0,
    )


@app.route("/bilibili_to_text")
@api_key_required
@share_url_required
def bilibili_to_text():
    bv_id = bilibili.get_bilibili_bvid_from_url(request.args.get("share_url"))
    if not bv_id:
        return dict(err="无法获取b站视频bv号", code=3)

    info = bilibili.get_bilibili_info(bv_id)
    if info.duration // 1000 > config.AUDIO_DURATION_LIMIT:
        return dict(err="音频长度超过限制", code=4)

    cache_key = f"bilibili:{bv_id}:transcription"
    text = cache.get(cache_key)
    if not text or request.args.get("ignore_cache") == "1":
        audio_path = utils.download_file(info.audio_url)
        # 转换成mp3格式
        mp3_path = utils.extract_audio_from_video(video_path=audio_path)
        if not mp3_path:
            return dict(err="无法获取音频", code=5)
        text = audio_to_text(
            audio_path=mp3_path,
            prompt="以下是普通话的句子，这是一段B站音频。",
        )
        cache.set(cache_key, text)
        utils.remove_files([audio_path, mp3_path])
    return dict(text=text, info=info.model_dump(), code=0)
