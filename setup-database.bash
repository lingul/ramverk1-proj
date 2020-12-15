#!/usr/bin/env bash

touch data/db.sqlite
chmod 777 data/db.sqlite

sqlite3 data/db.sqlite < sql/ddl/tables_sqlite.sql
