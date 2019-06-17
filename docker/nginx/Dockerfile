FROM node:alpine as front
COPY ./ /var/www
WORKDIR /var/www
RUN npm --prefix=frontend ci && npm --prefix=frontend run build


FROM nginx:alpine
RUN apk add --no-cache openssl

ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-alpine-linux-amd64-$DOCKERIZE_VERSION.tar.gz

COPY ./ /var/www
COPY --from=front /var/www/frontend/build /var/www/frontend/build
COPY ./docker/nginx/telepoll.template.conf /etc/telepoll.template.conf

CMD dockerize -template /etc/telepoll.template.conf:/etc/nginx/conf.d/telepoll.conf -stdout /var/log/nginx/access.log -stderr /var/log/nginx/error.log nginx