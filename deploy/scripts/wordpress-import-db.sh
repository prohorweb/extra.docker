#!/usr/bin/env bash
# Import a WordPress SQL dump into the running MariaDB container.
#
# Usage:
#   ./deploy/scripts/wordpress-import-db.sh backup.sql
#   ./deploy/scripts/wordpress-import-db.sh backup.sql --database extra
#
# Environment overrides (optional):
#   WP_DB_CONTAINER=extra_mariadb
#   WP_DB_USER=extra
#   WP_DB_PASSWORD=extra123
#   WP_DB_NAME=extra

set -euo pipefail

if [[ $# -lt 1 ]]; then
	echo "Usage: $0 <dump.sql> [--database NAME]" >&2
	exit 1
fi

DUMP_FILE="$1"
DB_NAME="${WP_DB_NAME:-extra}"
CONTAINER="${WP_DB_CONTAINER:-extra_mariadb}"
DB_USER="${WP_DB_USER:-extra}"
DB_PASSWORD="${WP_DB_PASSWORD:-extra123}"

shift
while [[ $# -gt 0 ]]; do
	case "$1" in
		--database)
			DB_NAME="$2"
			shift 2
			;;
		*)
			echo "Unknown option: $1" >&2
			exit 1
			;;
	esac
done

if [[ ! -f "$DUMP_FILE" ]]; then
	echo "Dump file not found: $DUMP_FILE" >&2
	exit 1
fi

echo "Importing ${DUMP_FILE} → ${CONTAINER}:${DB_NAME} (user: ${DB_USER})"

docker exec -i "$CONTAINER" mysql -u"$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" < "$DUMP_FILE"

echo "Done."
