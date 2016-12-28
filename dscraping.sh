#!/bin/bash

startTime=`date +%s`

STEPS=(
    "pd:sequence:scrap:target:list --limit=25"
    "pd:scrap:target --limit=25"
)

for STEP in "${STEPS[@]}"
do
    echo $STEP
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