#!/usr/bin/env bash
# Deploy do our-budget na VPS Staging (BigWorks).
#
# Sobe a imagem nova com a tag do commit, roda as migrations e limpa cache de
# config e rota. Rollback: IMAGE_TAG=<sha-antigo> docker compose -p our-budget up -d
set -euo pipefail

cd "$(dirname "${BASH_SOURCE[0]}")/.."

PROJECT="${COMPOSE_PROJECT:-our-budget}"
IMAGE_TAG="${IMAGE_TAG:-$(git rev-parse --short HEAD)}"
export IMAGE_TAG

if [[ ! -f .env ]]; then
  echo "[deploy] .env ausente na raiz do projeto (APP_KEY, DB_PASSWORD, DB_ROOT_PASSWORD)" >&2
  exit 1
fi

echo "[deploy] subindo containers (tag ${IMAGE_TAG})"
docker compose -p "$PROJECT" up -d --build

# Nginx resolve o upstream do php-fpm no start; recreate do app muda o IP.
echo "[deploy] recarregar nginx (upstream php-fpm)"
docker compose -p "$PROJECT" restart web

echo "[deploy] aguardando o php-fpm responder"
for _ in $(seq 1 30); do
  if docker compose -p "$PROJECT" exec -T app php -v >/dev/null 2>&1; then
    break
  fi
  sleep 2
done

echo "[deploy] migrations"
DB_CONN="$(grep -E '^DB_CONNECTION=' .env | cut -d= -f2- | tr -d '\r\"' | tr -d ' ')"
if [[ "${DB_CONN}" == "pgsql" ]]; then
  echo "[deploy] DB pgsql externo: migrations omitidas (schema ja existe no banco de producao)"
else
  docker compose -p "$PROJECT" exec -T app php artisan migrate --force
fi

echo "[deploy] cache de config e rotas"
docker compose -p "$PROJECT" exec -T app php artisan config:clear
docker compose -p "$PROJECT" exec -T app php artisan route:clear
docker compose -p "$PROJECT" exec -T app php artisan view:clear

echo "[deploy] ok: https://budget.staging.bigworks.com.br"
