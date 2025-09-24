FROM mysql:9.4

COPY ./sql/*.sql /docker-entrypoint-initdb.s/