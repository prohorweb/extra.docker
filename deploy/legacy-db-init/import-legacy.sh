#!/bin/bash
# Import legacy Yii/Laravel dumps into separate databases (legacy_mariadb first boot).
set -euo pipefail

ROOT_PW="${MARIADB_ROOT_PASSWORD:-root123}"
DUMP_DIR="/legacy-dumps"

mariadb -uroot -p"${ROOT_PW}" <<'SQL'
CREATE DATABASE IF NOT EXISTS extra CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS extra_new CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS extra_matros CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS extra_piter CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON extra.* TO 'extra'@'%';
GRANT ALL PRIVILEGES ON extra_new.* TO 'extra'@'%';
GRANT ALL PRIVILEGES ON extra_matros.* TO 'extra'@'%';
GRANT ALL PRIVILEGES ON extra_piter.* TO 'extra'@'%';
FLUSH PRIVILEGES;
SQL

import_dump() {
	local db="$1"
	local file="$2"
	if [[ -f "${DUMP_DIR}/${file}" ]]; then
		echo "Importing ${file} -> ${db}"
		mariadb -uroot -p"${ROOT_PW}" "${db}" < "${DUMP_DIR}/${file}"
	fi
}

import_dump extra extra.sql
import_dump extra_matros extra_matros.sql
import_dump extra_piter extra_piter.sql
import_dump extra_new extra_new.sql

echo "Legacy databases imported."
