#!/bin/bash

DVWA_CONTAINER_NAME=dvwa
DVWA_NETWORK_NAME=dvwa
ZAP_CONTAINER_NAME=zap

just _info "🔧 Starting DVWA..."
just start

just _info "⏳ Waiting for DVWA to be UP..."
until curl -s -L http://localhost:4280 | grep -q "Damn Vulnerable Web Application"; do
  sleep 3
done
just _info "✅ DVWA is UP!"

just _info "🕵️‍♂️ Scanning with OWASP ZAP..."
docker run --rm --name ${ZAP_CONTAINER_NAME} --network ${DVWA_NETWORK_NAME} \
    -v $(pwd)/../reports/zap:/zap/wrk/:rw \
    zaproxy/zap-stable zap-baseline.py \
    -t http://${DVWA_CONTAINER_NAME} \
    -r zap_report.html \
    -m 4

SCAN_RESULT=$?

just _info "🧼 Stopping DVWA..."
just stop

if [ ${SCAN_RESULT} -ne 0 ]; then
    just _error "❌ ZAP found vulnerabilities. Commit blocked!"
fi

just _info "✅ No vulnerabilities found! You are allowed to commit!"
exit 0
