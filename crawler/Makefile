.ONESHELL:

CONDA_ACTIVATE = source $$(conda info --base)/etc/profile.d/conda.sh ; conda activate ; conda activate

skip:
	@echo "Skipping..."
.PHONY: skip

build:
	docker-compose -f ./docker-compose.yml build
.PHONY: build

activate-venv:
	$(CONDA_ACTIVATE) cmi-crawler
.PHONY: activate-venv

install:
	pip install -r requirements.txt

burn: activate-venv install
.PHONY: burn

dev:
	python -m flask run --host 0.0.0.0 --port=6000 --debug 
.PHONY: dev