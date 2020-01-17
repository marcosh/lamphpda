#!/usr/bin/env sh
set -e

if [ "$(printf %c "$1")" = '-' ]; then
  set -- /sbin/tini -- php /composer/vendor/bin/psalm "$@"
elif [ "$1" = "/composer/vendor/bin/psalm" ]; then
  set -- /sbin/tini -- php "$@"
elif [ "$1" = "psalm" ]; then
  set -- /sbin/tini -- php /composer/vendor/bin/"$@"
fi

exec "$@"