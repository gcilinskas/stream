#!/bin/bash

# Make us independent from working directory
pushd `dirname $0` > /dev/null
SCRIPT_DIR=`pwd`
popd > /dev/null

docker --version > /dev/null 2>&1 || { echo >&2 "Docker not found. Please install it via https://docs.docker.com/install/"; exit 1; }
docker-compose --version > /dev/null 2>&1 || { echo >&2 "Docker-compose not found. Please install it via https://docs.docker.com/compose/install/"; exit 1; }

if [ ! -f "$SCRIPT_DIR/../.env" ]; then
    if [ -f "$SCRIPT_DIR/../.env.dist" ]; then
        cp "$SCRIPT_DIR/../.env.dist" "$SCRIPT_DIR/../.env"
    else
        echo >&2 "No .env file. Current Symofony project setup will not work"
        exit 1
    fi
fi

docker-compose -f "$SCRIPT_DIR/../docker-compose.yaml" build
docker-compose -f "$SCRIPT_DIR/../docker-compose.yaml" up -d

if [ -d "vendor" ] && [ -d "node_modules" ]; then
    echo "Now open in browser http://127.0.0.1:8888"
else
    echo "Now run scripts/install-prod.sh"
fi
