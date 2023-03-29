#!/bin/bash

kill -kill $(ps aux | grep "php admin.php" | awk '{print $2}')
kill -kill $(ps aux | grep "php index.php" | awk '{print $2}')

set -a && source .env && set +a

php index.php &
php admin.php &

echo "OK"