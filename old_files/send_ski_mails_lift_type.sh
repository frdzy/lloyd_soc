#! /bin/bash

while read line
do
    IFS=';' read -ra args <<< "$line"
    echo "Processing: ${args[0]}, ${args[1]}";

    echo "Waa ignore the last email! I just found a coupon to LeRoy's for 15% off. T_T

Happy New Year though! :)

Fred" | mail -aFROM:fredzhao@caltech.edu -s "Re: Ski trip: ski or snowboard?" ${args[1]}
    
done
