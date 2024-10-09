FROM python:3.12-slim

RUN sed -i 's@deb.debian.org@mirrors.tuna.tsinghua.edu.cn@g' /etc/apt/sources.list.d/debian.sources

RUN apt-get update && \
    apt-get install --no-install-recommends -y ffmpeg && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN adduser appuser 
USER appuser:appuser 
WORKDIR /home/appuser/app

COPY --chown=appuser:appuser . .

RUN pip install --user -r requirements.txt

CMD [ "python3", "-m" , "flask", "run", "--host=0.0.0.0"]

