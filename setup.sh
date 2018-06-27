#!/usr/bin/env bash

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
SET='\033[0m'




read -p "Do you wish to rebuild and pull images? (y/n)" -n 1 -r
echo    # (optional) move to a new line
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker-compose pull
    docker-compose build
fi

echo -e "${YELLOW}Cleaning up docker containers${SET}\n"
docker-compose kill
docker-compose rm -f -v
echo -e "${GREEN}Running docker containers${SET}\n"
docker-compose up -d maria
docker-compose up -d webserver
sleep 5

docker-compose up -d websockets

echo -e "${GREEN}Waiting for application bootstrap. The page would be open automatically on MacOS or Ubuntu${SET}"

url="http://localhost:8080/"

until $(curl -m 1 --output /dev/null --silent --head --fail "$url"); do
    printf '.'
    sleep 1
done

if [ -x "$(command -v xdg-open)" ]; then
    xdg-open $url;
fi

if [ -x "$(command -v open)" ]; then
    open $url;
fi
