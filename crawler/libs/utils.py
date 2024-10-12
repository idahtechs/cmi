import hashlib
import os
import shutil
import subprocess

import requests
from flask import current_app


def remove_files(paths: str | list):
    if isinstance(paths, str):
        paths = [paths]

    for path in paths:
        try:
            os.remove(path)
        except OSError:
            pass


def md5(s: str):
    return hashlib.md5(s.encode("utf-8")).hexdigest()


def download_file(url: str):
    response = requests.get(url, stream=True)
    response.raise_for_status()  # 检查请求是否成功

    save_path = os.path.join(
        os.path.curdir, "downloads", url.split("/")[-1].split("?")[0]
    )
    # 确保目录存在
    os.makedirs(os.path.dirname(save_path), exist_ok=True)

    with open(save_path, "wb") as file:
        for chunk in response.iter_content(chunk_size=8192):
            file.write(chunk)

    return save_path


def extract_audio_from_video(video_path: str):
    if not shutil.which("ffmpeg"):
        raise Exception("ffmpeg is not found")

    output_path = os.path.splitext(video_path)[0] + ".mp3"
    try:
        command = f"ffmpeg -i {video_path} -q:a 0 -map a -y {output_path}"
        pipe = subprocess.Popen(
            command, shell=True, stdout=subprocess.PIPE, stderr=subprocess.PIPE
        )
        stdout, stderr = pipe.communicate()
        if pipe.returncode != 0:
            remove_files(output_path)
            raise Exception(stderr.decode())
    except Exception as e:
        current_app.logger.error("execute ffmpeg error: ", e)

    if not os.path.exists(output_path):
        return None
    return output_path


def append_or_extend(array, value):
    if not array:
        return [value]
    if isinstance(value, list):
        return array + value
    return array + [value]


def get_valid_url(urls: list[str]):
    for url in urls:
        resp = requests.get(url)
        if resp.status_code == 200:
            return url
