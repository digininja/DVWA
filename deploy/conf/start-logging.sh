#!/bin/bash
exec tail -f /var/log/mysql/mysql.log > /proc/1/fd/1
exec tail -f /var/log/mysql/mysql-slow.log > /proc/1/fd/1
