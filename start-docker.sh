#!/bin/bash

chmod 775 -R ./hackable/uploads/
chmod 775 ./external/phpids/0.6/lib/IDS/tmp/phpids_log.txt
docker-compose up -d
