#!/usr/bin/env bash
# Post-deploy smoke checks (run on the server from repo root).
set -euo pipefail

HOST_MAIN="${1:-${WORDPRESS_DOMAIN_CURRENT_SITE:-extrasport.local}}"
HOST_SECOND="${2:-devision.local}"

if [[ -f .env ]]; then
	# shellcheck disable=SC1091
	source .env
fi

check_url() {
	local label="$1"
	local url="$2"
	local code
	code="$(curl -fsSL -o /dev/null -w '%{http_code}' "$url" || echo "000")"
	if [[ "$code" =~ ^(200|301|302)$ ]]; then
		echo "OK  $label ($code) $url"
	else
		echo "FAIL $label ($code) $url" >&2
		return 1
	}
}

echo "Docker services:"
docker compose -f deploy/docker-compose.yml ps

check_url "main-http" "http://${HOST_MAIN}/"
check_url "second-http" "http://${HOST_SECOND}/"

echo "Health check passed."
