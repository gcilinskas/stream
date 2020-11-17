#!/bin/bash

# Make us independent from working directory
pushd `dirname $0` > /dev/null
SCRIPT_DIR=`pwd`
popd > /dev/null

docker-compose -f "$SCRIPT_DIR/../docker-compose.yaml" stop
