# CMI-Crawler

该项目主要提供了

- 抖音作品预览 API `/douyin_preview_info`
- 抖音作品视频转换成文字 API `/douyin_to_text`
- 小红书作品预览 API `/xhs_preview_info`
- 小红书作品视频转换成文字 API `/xhs_to_text`
- B站作品预览 API `/bilibili_preview_info`
- B站作品视频转换成文字 API `/bilibili_to_text`

## 用到的服务

- [TikHub](https://tikhub.io/)
- Azure Whisper
- Redis

## 开发

### 配置环境变量

```shell
cp .env.example .env
```

按需要更新环境变量值

### 依赖安装

使用 conda 创建 python 虚拟环境, 然后安装依赖即可.

1. 创建虚拟环境, 该命令只需执行一次

```shell
conda create -n cmi-crawler python=3.12 pip
```

2. 激活环境

```shell
conda activate cmi-crawler
```

3. 安装依赖

```shell
pip install -r requirements.txt
```

4. 可用下面的命令退出当前已激活的虚拟环境

```shell
conda deactivate
```

### 启动

启动应用

```shell
python -m flask run --host 0.0.0.0 --port=6000 --debug
```
