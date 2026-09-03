#!/usr/bin/env bash
# Multisite domain migration after import (e.g. .local → staging/production).
#
# 1. wp_site / wp_blogs domain columns
# 2. siteurl / home in wp_options (+ wp_2_options)
# 3. wp search-replace across all tables (serialized-safe)
#
# Target domains (.env on server):
#   WORDPRESS_DOMAIN_CURRENT_SITE  — main site (blog 1)
#   HEALTHCHECK_HOST_SECOND        — second site (blog 2), optional
#
# Source domains (defaults match local dev):
#   MIGRATE_FROM_MAIN=extrasport.local
#   MIGRATE_FROM_SECOND=devision.local
#
# Disable URL search-replace: MIGRATE_URLS=0

set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$REPO_ROOT"

if [[ -f .env ]]; then
	# shellcheck disable=SC1091
	set -a
	source .env
	set +a
fi

MAIN="${WORDPRESS_DOMAIN_CURRENT_SITE:-}"
SECOND="${HEALTHCHECK_HOST_SECOND:-}"
FROM_MAIN="${MIGRATE_FROM_MAIN:-extrasport.local}"
FROM_SECOND="${MIGRATE_FROM_SECOND:-devision.local}"
MIGRATE_URLS="${MIGRATE_URLS:-1}"

if [[ -z "$MAIN" ]]; then
	echo "WORDPRESS_DOMAIN_CURRENT_SITE is not set — skip domain fix." >&2
	exit 0
fi

CONTAINER="${WP_DB_CONTAINER:-extra_mariadb}"
DB_USER="${WORDPRESS_DB_USER:-extra}"
DB_PASSWORD="${WORDPRESS_DB_PASSWORD:?WORDPRESS_DB_PASSWORD required}"
DB_NAME="${WORDPRESS_DB_NAME:-extra}"

echo "==> Multisite table domains: ${FROM_MAIN} → ${MAIN}, ${FROM_SECOND} → ${SECOND:-<skip>}"

SQL="UPDATE wp_site SET domain = '${MAIN}';
UPDATE wp_blogs SET domain = '${MAIN}' WHERE blog_id = 1;
UPDATE wp_options SET option_value = 'https://${MAIN}' WHERE option_name IN ('siteurl', 'home');"

if [[ -n "$SECOND" ]]; then
	SQL="${SQL}
UPDATE wp_blogs SET domain = '${SECOND}' WHERE blog_id = 2;
UPDATE wp_2_options SET option_value = 'https://${SECOND}' WHERE option_name IN ('siteurl', 'home');"
fi

docker exec -i "$CONTAINER" mariadb -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" <<< "$SQL"

if [[ "$MIGRATE_URLS" != "1" ]]; then
	echo "==> URL search-replace skipped (MIGRATE_URLS=${MIGRATE_URLS})"
	echo "==> Domain fix done"
	exit 0
fi

if ! docker ps --format '{{.Names}}' | grep -q '^extra_wordpress$'; then
	echo "extra_wordpress is not running — table domains updated, URL replace skipped." >&2
	exit 0
fi

NETWORK="$(docker inspect -f '{{range $k, $v := .NetworkSettings.Networks}}{{$k}}{{end}}' extra_wordpress)"
WP_VOLUME="$(docker inspect -f '{{range .Mounts}}{{if eq .Destination "/var/www/html"}}{{.Name}}{{end}}{{end}}' extra_wordpress)"

if [[ -z "$NETWORK" || -z "$WP_VOLUME" ]]; then
	echo "Could not detect Docker network/volume for wp-cli — table domains updated only." >&2
	exit 0
fi

WP_CLI_IMAGE="${WP_CLI_IMAGE:-wordpress:cli-php8.2}"

run_wp() {
	docker run --rm \
		--network "$NETWORK" \
		-v "${WP_VOLUME}:/var/www/html" \
		-v "${REPO_ROOT}/deploy/wp-config.php:/var/www/html/wp-config.php:ro" \
		-e WORDPRESS_DB_HOST=db \
		-e WORDPRESS_DB_NAME="${DB_NAME}" \
		-e WORDPRESS_DB_USER="${DB_USER}" \
		-e WORDPRESS_DB_PASSWORD="${DB_PASSWORD}" \
		-e WORDPRESS_DOMAIN_CURRENT_SITE="${MAIN}" \
		-e WORDPRESS_COOKIE_DOMAIN="${WORDPRESS_COOKIE_DOMAIN:-}" \
		"$WP_CLI_IMAGE" \
		--allow-root \
		"$@"
}

replace_domain_urls() {
	local from="$1"
	local to="$2"
	local label="$3"

	if [[ -z "$from" || -z "$to" || "$from" == "$to" ]]; then
		return 0
	fi

	echo "==> wp search-replace (${label}): ${from} → ${to}"

	run_wp search-replace "https://${from}" "https://${to}" --all-tables --precise --skip-columns=guid --report-changed-only
	run_wp search-replace "http://${from}" "https://${to}" --all-tables --precise --skip-columns=guid --report-changed-only
	run_wp search-replace "//${from}" "//${to}" --all-tables --precise --skip-columns=guid --report-changed-only
}

replace_domain_urls "$FROM_MAIN" "$MAIN" "main"
replace_domain_urls "$FROM_SECOND" "$SECOND" "second"

echo "==> Domain migration done"
