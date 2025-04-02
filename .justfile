set export

RED := '\033[0;31m'
GREEN := '\033[0;32m'
YELLOW := '\033[1;33m'
BLUE := '\033[0;34m'
NC := '\033[0m'

@_default:
  just --list



# Start DVWA container and DB
@start: setup
  docker compose up -d


# Stop DVWA container and DB
@stop:
  docker compose stop

@wipe:
  docker rm -f dvwa dvwa_db
  docker volume rm dvwa
  docker network rm dvwa

# Install dependencies
setup:
  #!/usr/bin/env bash
  set -euo pipefail
  if [[ "{{os()}}" == "macos" ]]; then
    just _info "Installing pre-commit..."
    brew install pre-commit
    just _info "Setting up pre-commit..."
    pre-commit install
  elif [[ "{{os()}}" == "linux" ]]; then
    just _info "Installing pre-commit..."
    pip install pre-commit
  else
    just _error "Operating system {{os()}} not supported!"
    exit 1
  fi

_info MESSAGE:
  #!/usr/bin/env bash
  echo -e "${GREEN}[INFO]${NC} {{MESSAGE}}"

_warn MESSAGE:
  #!/usr/bin/env bash
  echo -e "${YELLOW}[WARNING]${NC} {{MESSAGE}}"

_debug MESSAGE:
  #!/usr/bin/env bash
  echo -e "${BLUE}[WARNING]${NC} {{MESSAGE}}"

_error MESSAGE:
  #!/usr/bin/env bash
  echo -e "${RED}[ERROR]${NC} {{MESSAGE}}"
  exit 1
