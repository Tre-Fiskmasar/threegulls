FROM ubuntu/apache2

WORKDIR /var/www/html

COPY . /var/www/html/
EXPOSE 5150

RUN "npm i"

CMD [ "node", "server.js" ]