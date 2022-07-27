#!/bin/bash

# Add a tick or cross and display the output if any
function output {
    if [ "$1" -ne 0 ]; then
        echo -n "✘"
        echo
        echo "$2"
        exit 1
    else
        echo -n "✔"
    fi
}

# List all modified files in the commit
git_precommit_changed_files() {
    git diff --cached --name-only --diff-filter=ACMRTUXB
}

# Filter out only *.php files
php_name_filter() {
    CHANGED_FILES=${CHANGED_FILES//$'\n'}
    CHANGED_FILES=${CHANGED_FILES//$'\r'}

    local file
    while read -r file; do
        case "$file" in
        *.php)
            FILE=${file//$'\n'}
            FILE=${file//$'\r'}
            echo -e "$FILE "
        ;;
        esac
    done
}

# Get a list of all changed php files only for this commit
function get_staged_files_for_commit() {
    echo -n "$(git_precommit_changed_files | php_name_filter)"
}
