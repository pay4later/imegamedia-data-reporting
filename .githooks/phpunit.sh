#!/bin/bash

# Include helper functions
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
. "$DIR/helpers.sh"

function run_unit_tests {
    output=$(./vendor/phpunit/phpunit/phpunit --stop-on-failure --exclude-group ignore,broken,tdd)

    output $? "$output"
}
