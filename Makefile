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