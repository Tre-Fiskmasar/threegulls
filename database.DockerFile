FROM mysql:9.4

# COPY ./sql/*.sql /docker-entrypoint-initdb.d/
COPY ./sql/06-initial_schema.sql /docker-entrypoint-initdb.d/

# https://hub.docker.com/_/mysql
