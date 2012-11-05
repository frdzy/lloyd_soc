#! /bin/bash

while read line
do
    IFS=';' read -ra args <<< "$line"
    echo "Processing: ${args[0]}, ${args[1]}, ${args[2]}, ${args[3]}, ${args[4]}, ${args[5]}";
    echo "Good morning ${args[0]},

This is an email to let you know that you are currently signed up for the Ski Trip on Jan. 7th - 9th, 2011.

Your details are as follows:

Lift ticket: ${args[2]}
Driving: ${args[3]}
Cost: ${args[4]}
Paid: ${args[5]}

If any of the above information is incorrect, please update the web form ASAP. As we are currently short on seats, we'll need all the drivers we can get! Seatless Lloydies are sad Lloydies. :(

The registration form will close at 11:59pm today, so please finalize your choices before then. Costs will be adjusted by Monday when we have a final count of participants, and payment will be due Thursday to me (Lloyd 215) or Elisa (Lloyd 223). Feel free to talk to us if you have any problems paying by then.

Thank you,

Fred" | mail -aFROM:fredzhao@caltech.edu -s "Lloyd House Ski Trip Confirmation" ${args[1]}
    
done
