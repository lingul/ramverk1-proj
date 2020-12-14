#!/usr/bin/env bash

chmod 777

mysql -h localhost -u anax -p < sql/ddl/tables_mysql.sql
