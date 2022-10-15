build:
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose up -d --build

down:
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose down

start:
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose up -d

test: test8.0 test8.1
test8.0:
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose build --pull php8.0
	make create-cluster
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose run php8.0 vendor/bin/codecept run
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose down

test8.1:
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose build --pull php8.1
	make create-cluster
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose run php8.1 vendor/bin/codecept run
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose down

create-cluster:
	docker exec redis1 sh -c "redis-cli -p 6381 -a Password --cluster create 172.20.128.2:6381 172.20.128.3:6382 172.20.128.4:6383 172.20.128.5:6384 172.20.128.6:6385 172.20.128.7:6386 --cluster-replicas 1 --no-auth-warning --cluster-yes"

connect-cluster:
	docker exec -it redis1 sh -c "redis-cli -c -p 6381 -a Password --no-auth-warning"

analyse:
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose build --pull php$(v)
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose run php$(v) vendor/bin/psalm --stats -m --output-format=console --php-version=$(v) --threads=2
	COMPOSE_FILE=tests/docker/docker-compose.yml docker-compose down