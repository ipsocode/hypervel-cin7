#!/usr/bin/env bash

# Run the sync tests against one database connection. Adapted from hypervel/components'
# bin/run-database-tests.sh (branch 0.4, e44bad61bfd91a0f68eacbfdbc9d871f56cbb100).
#
# Usage:
#   ./bin/run-database-tests.sh mysql
#   ./bin/run-database-tests.sh pgsql -p 3 --filter=PayloadTest
#
# Supported connection names are mysql, mariadb, pgsql, and sqlite. The runner
# sets DB_CONNECTION from this argument. Every run executes the shared tests in
# tests/Feature/Sync first. It then discovers driver-specific tests using this
# exact directory convention:
#
#   tests/Feature/<Area>/Database/<DriverDirectory>
#
# DriverDirectory is MySql, MariaDb, Postgres, or Sqlite, as mapped below. The
# area must occupy exactly one directory segment below Feature; deeper
# directory structures are not discovered. For example, PostgreSQL-only tests
# for the sync belong in:
#
#   tests/Feature/Sync/Database/Postgres
#
# Arguments after the connection name are forwarded to every ParaTest command.
# Runs with extra options tolerate an empty individual suite because selection
# options may match only one directory; runs without extra options remain strict.

set -euo pipefail

if [[ $# -lt 1 ]]; then
    echo "Usage: $0 <mysql|mariadb|pgsql|sqlite> [ParaTest options]" >&2
    exit 2
fi

script_directory="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
project_directory="$(dirname "$script_directory")"
connection=$1
shift

case "$connection" in
    mysql) driver_directory=MySql ;;
    mariadb) driver_directory=MariaDb ;;
    pgsql) driver_directory=Postgres ;;
    sqlite) driver_directory=Sqlite ;;
    *)
        echo "Unsupported database connection: $connection" >&2
        echo "Usage: $0 <mysql|mariadb|pgsql|sqlite> [ParaTest options]" >&2
        exit 2
        ;;
esac

export DB_CONNECTION="$connection"

paratest_options=("$@")

# CI passes no extra options, so an unexpectedly empty suite still fails there.
# Ad-hoc selection options can legitimately match only one independently invoked suite.
if [[ $# -gt 0 ]]; then
    paratest_options+=(--do-not-fail-on-empty-test-suite)
fi

cd "$project_directory"

printf 'Running %s\n' 'tests/Feature/Sync'
vendor/bin/paratest "${paratest_options[@]}" tests/Feature/Sync

shopt -s nullglob
candidate_directories=(tests/Feature/*/Database/"$driver_directory")
test_directories=()

for test_directory in "${candidate_directories[@]}"; do
    if [[ -d "$test_directory" ]]; then
        test_directories+=("$test_directory")
    fi
done

if [[ ${#test_directories[@]} -eq 0 ]]; then
    printf 'No driver-specific %s database test directories found.\n' "$driver_directory"
fi

for test_directory in "${test_directories[@]}"; do
    printf 'Running %s\n' "$test_directory"
    vendor/bin/paratest "${paratest_options[@]}" "$test_directory"
done
