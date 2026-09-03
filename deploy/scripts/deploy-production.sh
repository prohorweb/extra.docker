#!/usr/bin/env bash
# Production deploy on the server (prod compose only — no legacy overlay).
set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$REPO_ROOT"

mkdir -p backups

if [[ -f .env ]]; then
	# shellcheck disable=SC1091
	set -a
	source .env
	set +a
fi

if docker ps --format '{{.Names}}' | grep -q '^extra_mariadb$'; then
	echo "==> Pre-deploy database backup"
	docker exec extra_mariadb mysqldump \
		-u"${WORDPRESS_DB_USER}" \
		-p"${WORDPRESS_DB_PASSWORD}" \
		"${WORDPRESS_DB_NAME}" | gzip > "backups/pre-deploy-$(date +%Y%m%d-%H%M%S).sql.gz"
fi

echo "==> Update code"
git fetch origin
TARGET_BRANCH="${DEPLOY_BRANCH:-main}"
git checkout "${TARGET_BRANCH}"
git pull --ff-only origin "${TARGET_BRANCH}"

echo "==> Build theme assets"
(
	cd deploy/wp-content/themes/extrasport
	npm ci
	npm run build
)

echo "==> Start production stack"
docker compose -f deploy/docker-compose.yml up -d --remove-orphans

echo "==> Health check"
chmod +x deploy/scripts/post-deploy-healthcheck.sh
MAIN="${HEALTHCHECK_HOST_MAIN:-${WORDPRESS_DOMAIN_CURRENT_SITE:-extrasport.local}}"
SECOND="${HEALTHCHECK_HOST_SECOND:-devision.local}"
./deploy/scripts/post-deploy-healthcheck.sh "${MAIN}" "${SECOND}"

echo "==> Deploy complete"
