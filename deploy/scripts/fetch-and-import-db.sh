#!/usr/bin/env bash
# Download a WordPress SQL dump and import into the running MariaDB container.
#
# Requires IMPORT_DB=1 (safety guard — never run accidentally on deploy).
#
# Environment (from .env and/or CI):
#   DATABASE_DUMP_URL    — HTTPS URL (.sql or .sql.gz), e.g. GitHub Release asset
#   DATABASE_DUMP_TOKEN  — optional Bearer/token for private downloads
#   IMPORT_DB=1          — must be set
#   FIX_MULTISITE_DOMAINS=1 — run domain fix after import (default: 1 when importing)
#
# Usage:
#   IMPORT_DB=1 DATABASE_DUMP_URL=https://... ./deploy/scripts/fetch-and-import-db.sh

set -euo pipefail

REPO_ROOT="$(cd "$(dirname "$0")/../.." && pwd)"
cd "$REPO_ROOT"

if [[ -f .env ]]; then
	# shellcheck disable=SC1091
	set -a
	source .env
	set +a
fi

if [[ "${IMPORT_DB:-0}" != "1" ]]; then
	echo "Skip DB import (set IMPORT_DB=1 to enable)." >&2
	exit 0
fi

URL="${DATABASE_DUMP_URL:-}"
if [[ -z "$URL" ]]; then
	echo "DATABASE_DUMP_URL is not set (GitHub Secret or .env on server)." >&2
	exit 1
fi

if ! docker ps --format '{{.Names}}' | grep -q '^extra_mariadb$'; then
	echo "extra_mariadb is not running. Start the stack first." >&2
	exit 1
fi

TMP_DIR="$(mktemp -d)"
trap 'rm -rf "$TMP_DIR"' EXIT

DUMP_RAW="${TMP_DIR}/dump.bin"
CURL_ARGS=(-fsSL -o "$DUMP_RAW")

if [[ -n "${DATABASE_DUMP_TOKEN:-}" ]]; then
	if [[ "${DATABASE_DUMP_TOKEN}" == ghp_* ]]; then
		CURL_ARGS+=(-H "Authorization: token ${DATABASE_DUMP_TOKEN}")
	else
		CURL_ARGS+=(-H "Authorization: Bearer ${DATABASE_DUMP_TOKEN}")
	fi
fi

echo "==> Downloading database dump"
curl "${CURL_ARGS[@]}" "$URL"

DUMP_SQL="${TMP_DIR}/dump.sql"
if gzip -t "$DUMP_RAW" 2>/dev/null; then
	echo "==> Decompressing .gz dump"
	gunzip -c "$DUMP_RAW" > "$DUMP_SQL"
else
	cp "$DUMP_RAW" "$DUMP_SQL"
fi

chmod +x deploy/scripts/wordpress-import-db.sh
echo "==> Importing into MariaDB"
./deploy/scripts/wordpress-import-db.sh "$DUMP_SQL"

if [[ "${FIX_MULTISITE_DOMAINS:-1}" == "1" ]]; then
	chmod +x deploy/scripts/wordpress-multisite-domain-fix.sh
	echo "==> Updating Multisite domains from .env"
	./deploy/scripts/wordpress-multisite-domain-fix.sh
fi

echo "==> Database import complete"
