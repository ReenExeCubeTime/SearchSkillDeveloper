#!/bin/bash

startTime=`date +%s`

STEPS=(
    "scrap:skill:site:list"
    "scrap:skill:site:page"
    "create:skill:site:structure"
)

for STEP in "${STEPS[@]}"
do
    while true; do
        bin/console $STEP
        EXIT_CODE=$?
        if test $EXIT_CODE -eq 1
        then
            echo "Done"
            break
        fi
        echo '...';
    done
done

endTime=`date +%s`
echo execution time was `expr $endTime - $startTime` s.