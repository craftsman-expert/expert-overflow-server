FROM nginx:latest as base

COPY ./*.conf               /etc/nginx/
#COPY ./snippets/*.conf      /etc/nginx/snippets/
COPY ./sites-enabled/*.conf /etc/nginx/sites-enabled/
COPY ./.htpasswd /etc/nginx/.htpasswd

ARG NGINX_AUTH_BASIC="false"

RUN if [ "$NGINX_AUTH_BASIC" = "true" ] ; then \
        sed -i 's/#auth_basic/auth_basic/' /etc/nginx/sites-enabled/default.conf;\
    fi

FROM base as dev

# Customize for dev

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]

FROM base as prod

# Customize for prod

RUN sed -i -e 's/#expires 30d/expires 30d/' /etc/nginx/sites-enabled/default.conf \
    && sed -i -e 's/#access_log off/access_log off/' /etc/nginx/sites-enabled/default.conf

EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]
