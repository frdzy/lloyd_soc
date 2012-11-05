#! /bin/bash

while read line
do
    IFS=';' read -ra args <<< "$line"
    echo "Processing: ${args[0]}, ${args[1]}, ${args[2]}, ${args[3]}, ${args[4]}, ${args[5]}";

    balance=${args[5]}

    if [ ${args[3]} -eq 0 ]; then
        driving="Please help me fill out the survey so I can assign you a car!";
        subject="passenger survey";
    else
        driving="Please use this page to keep track of who you'll be driving. As I mentioned in my last email, let me and Elisa know ASAP if you want snow chains and tell us your tire size.";
        subject="driver info";
    fi

    if [ ${args[5]} = ${args[4]} ]; then
        payment="Just a quick update for you:";
        if [ ${args[2]} -eq 1 ]; then
            payment="Come to my room to pick up a lift ticket if you haven't already.";
        fi
    else 
            payment="First of all, I'd like to remind you to pay me or Elisa ASAP for ski trip! You still owe \$`echo ${args[5]} - ${args[4]} | bc `. If you already paid one of us, I apologize - just email me and let me know..";
            if [ ${args[2]} -eq 1 ]; then
                payment="$payment You won't be able to pick up your lift ticket until you pay this off!";
            fi
    fi

    echo "Hey ${args[0]},

$payment

I updated the ski trip page with the latest updated information, including driving info as Elisa and I start to assign seats. $driving
http://fz.caltech.edu/ski2011.php?id=passengers

See you on the slopes!

Fred" | mail -aFROM:fredzhao@caltech.edu -s "Ski trip $subject" ${args[1]}
    
done
