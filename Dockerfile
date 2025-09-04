FROM ubuntu/apache2

WORKDIR /var/www/html

COPY . /var/www/html/
EXPOSE 5150

RUN cd /var/www/html/ && npm install

CMD [ "node", "server.js" ]