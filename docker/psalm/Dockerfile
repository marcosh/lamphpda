# Set defaults

ARG BASE_IMAGE="php:7.4-alpine"
ARG PACKAGIST_NAME="vimeo/psalm"
ARG PHPQA_NAME="psalm"
ARG VERSION="dev-master"

# Build image

FROM ${BASE_IMAGE}
ARG COMPOSER_IMAGE
ARG PACKAGIST_NAME
ARG VERSION
ARG PHPQA_NAME
ARG VERSION
ARG BUILD_DATE
ARG VCS_REF
ARG IMAGE_NAME

# Install Tini - https://github.com/krallin/tini

RUN apk add --no-cache tini

# Install Psalm - https://github.com/vimeo/psalm

COPY --from=composer:1.6.5 /usr/bin/composer /usr/bin/composer
RUN COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME="/composer" \
    composer global require --prefer-dist --no-progress --dev ${PACKAGIST_NAME}:${VERSION}
ENV PATH /composer/vendor/bin:${PATH}

# Satisfy Psalm's quest for a composer autoloader (with a symlink that disappears once a volume is mounted at /app)

RUN mkdir /app && ln -s /composer/vendor/ /app/vendor

# Add entrypoint script

COPY ./docker-entrypoint.sh /docker-entrypoint.sh
RUN chmod +x /docker-entrypoint.sh

# Add image labels

LABEL org.label-schema.schema-version="1.0" \
      org.label-schema.vendor="phpqa" \
      org.label-schema.name="${PHPQA_NAME}" \
      org.label-schema.version="${VERSION}" \
      org.label-schema.build-date="${BUILD_DATE}" \
      org.label-schema.url="https://github.com/phpqa/${PHPQA_NAME}" \
      org.label-schema.usage="https://github.com/phpqa/${PHPQA_NAME}/README.md" \
      org.label-schema.vcs-url="https://github.com/phpqa/${PHPQA_NAME}.git" \
      org.label-schema.vcs-ref="${VCS_REF}" \
      org.label-schema.docker.cmd="docker run --rm --volume \${PWD}:/app --workdir /app ${IMAGE_NAME}"

# Package container

WORKDIR "/app"
ENTRYPOINT ["/docker-entrypoint.sh"]
CMD ["psalm"]