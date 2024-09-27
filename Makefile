HOST ?= cmi-qa
TARGET ?= ~/app/cmi

skip:
	@echo "Skipping..."

sync:
	@echo "---git pulling..."
	ssh $(HOST) "cd $(TARGET) && git pull"
	@echo "---git pull completed."
.PHONY: sync

deploy-qa:
	@echo "---QA Deploying..."
	ssh $(HOST) "cd $(TARGET) && docker-compose -f ./docker/qa/docker-compose.yml build && docker-compose -f ./docker/qa/docker-compose.yml up -d"
	@echo "---QA Deploy completed."

build-dev:
	docker-compose -f ./docker/dev/docker-compose.yml build
.PHONY: build-dev

dev:
	rm -f ./runtime/swoole.pid
	docker-compose -f ./docker/dev/docker-compose.yml up
.PHONY: dev

dev-nodb:
	rm -f ./runtime/swoole.pid
	docker-compose -f ./docker/dev/docker-compose.nodb.yml up
.PHONY: dev-nodb

rebuild-dev: build dev
.PHONY: rebuild-dev