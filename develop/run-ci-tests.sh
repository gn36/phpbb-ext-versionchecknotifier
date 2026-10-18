#!/bin/bash
# Führt die Tests exakt so aus wie das CI (phpBB-Konfiguration phpunit-mysql-github.xml, inkl. funktionaler Tests).
# Voraussetzung: laufender MariaDB-Server (root ohne Passwort) und ein Webserver auf http://localhost/ mit phpBB/ als Root:
#   service mariadb start; (cd $PHPBB_DIR && nohup php -S 127.0.0.1:80 -t phpBB >/tmp/php-server.log 2>&1 &)
set -e
EXTNAME=gn36/versionchecknotifier
EXT="$(cd "$(dirname "$0")/.." && pwd)"
CORE="${PHPBB_DIR:-/opt/phpbb-core}"
DEST="$CORE/phpBB/ext/$EXTNAME"
rm -rf "$DEST" && mkdir -p "$DEST/.github"
cp -a "$EXT/." "$DEST/" && rm -rf "$DEST/.git"
cd "$CORE"
sed 's#value="0.0.0.0"#value="127.0.0.1"#' .github/phpunit-mysql-github.xml > "$DEST/.github/phpunit-mysql-github.xml"
php -d error_reporting=24575 phpBB/vendor/bin/phpunit \
  --configuration "phpBB/ext/$EXTNAME/.github/phpunit-mysql-github.xml" --bootstrap ./tests/bootstrap.php "$@"
