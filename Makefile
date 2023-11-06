username?=devoptimus
version?=0.0.1

build:
	docker build -t devoptimus/ged-admin-cli --target=cli .
	docker build -t devoptimus/ged-admin-fpm --target=fpm .
	docker build -t devoptimus/ged-admin-node --target=node .
	docker build -t devoptimus/ged-admin-nginx --target=nginx .

push:
	docker push devoptimus/ged-admin-cli
	docker push devoptimus/ged-admin-fpm
	docker push devoptimus/ged-admin-node
	docker push devoptimus/ged-admin-nginx

dbwriter:
	docker container run --name db-writer \
		-e MASTER_HOST=db-writer \
		-e MASTER_PORT=5432 \
		-e SLOT_NAME=master \
		-e POSTGRES_USERNAME=postgres \
		-e POSTGRES_PASSWORD=postgres \
		-e REPLICANT_USERNAME=replicant \
		-e REPLICANT_PASSWORD=replicant \
		-p 5432:5432 \
		--rm \
		--pull always \
		--network=db_bridge \
		-d devoptimus/postgres:latest
dbreader:
	docker container run --name db-reader \
		-e MASTER_HOST=db-writer \
		-e MASTER_PORT=5432 \
		-e SLOT_NAME=master \
		-e POSTGRES_USERNAME=postgres \
		-e POSTGRES_PASSWORD=postgres \
		-e REPLICANT_USERNAME=replicant \
		-e REPLICANT_PASSWORD=replicant \
		-e REPLICATION_MODE=slave \
		-p 5433:5432 \
		--rm \
		--pull always \
		--network=db_bridge \
		-d devoptimus/postgres:latest
cache:
	docker container run  --name cache \
		-p 6379:6379 \
		--rm \
		--pull always \
		--network=db_bridge \
		-d redis:latest

up: build
	docker compose up -d

down:
	docker compose down

