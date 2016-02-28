#!/bin/bash

startTime=`date +%s`

STEPS=(
    "w:scrap:skill:site:list"
    "w:scrap:skill:site:page"
    "w:create:skill:site:structure"
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

bin/console skill:site:analyze:structure

endTime=`date +%s`
echo execution time was `expr $endTime - $startTime` s.