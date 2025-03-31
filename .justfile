@_default:
  just --list

# Start DVWA container and DB
@start:
  docker compose up -d

@stop:
  docker compose stop