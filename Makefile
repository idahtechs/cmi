HOST ?= cmi-qa
TARGET ?= ~/app/cmi

skip:
	@echo "Skipping..."

sync:
	@echo "---git pulling..."
	ssh $(HOST) "cd $(TARGET) && git pull"
	@echo "---git pull completed."
.PHONY: sync

deploy-web-qa:
	@echo "---QA Deploying..."
	ssh $(HOST) "cd $(TARGET) && docker-compose -f ./web/docker/qa/docker-compose.yml build && docker-compose -f ./web/docker/qa/docker-compose.yml up -d"
	@echo "---QA Deploy completed."

build-web-dev:
	docker-compose -f ./web/docker/dev/docker-compose.yml build
.PHONY: build-web-dev

web-dev:
	rm -f ./web/runtime/swoole.pid
	docker-compose -f ./web/docker/dev/docker-compose.yml up
.PHONY: web-dev

web-dev-nodb:
	rm -f ./web/runtime/swoole.pid
	docker-compose -f ./web/docker/dev/docker-compose.nodb.yml up
.PHONY: web-dev-nodb

dev-h5:
	cd ./uni-app; \
	yarn install; \
	yarn serve
.PHONY: dev-h5

dev-mp-weixin:
	cd ./uni-app; \
	yarn install; \
	yarn dev:mp-weixin
.PHONY: dev-mp-weixin

build-h5:
	cd ./uni-app; \
	yarn install --frozen-lockfile; \
	yarn build:h5; \
	rm ../web/public/static/js/pages-* ../web/public/static/js/index.* ../web/public/static/js/chunk-vendors.*; \
	cp -R dist/build/h5/* ../web/public
.PHONY: build-h5

build-mp-weixin-qa:
	cd ./uni-app; \
	yarn install --frozen-lockfile; \
	yarn qa:mp-weixin
.PHONY: build-mp-weixin-qa

build-mp-weixin:
	cd ./uni-app; \
	yarn install --frozen-lockfile; \
	yarn build:mp-weixin
.PHONY: build-mp-weixin