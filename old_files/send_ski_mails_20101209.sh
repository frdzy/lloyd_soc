#! /bin/bash

while read line
do
    IFS=';' read -ra args <<< "$line"
    echo "Processing: ${args[0]}, ${args[1]}, ${args[2]}, ${args[3]}, ${args[4]}, ${args[5]}, ${args[6]}";

    if [ ${args[2]} -eq 1 ]; then
        lift="";
    else
        lift="not";
    fi

    balance=${args[5]}

    if [ ${args[4]} = ${args[5]} ]; then
        payment="you've already paid everything in full. Thanks!";
    else 
        if [ ${args[4]} = 0.00 ]; then
            payment="you have not paid ANY money at all to me or Elisa. We'll have to assume that you don't want to go on the trip if you can't pay by tomorrow and haven't contacted us about why you haven't paid yet. :(";
            if [ ${args[2]} -eq 1 ]; then
                payment="$payment Without money from you WE CAN'T BUY YOUR LIFT TICKET!";
            fi
        else
            payment="you have paid \$${args[4]}. Please get the rest of your payment in ASAP!";
        fi
    fi

    if [ ${args[3]} -eq 0 ]; then
        driver="
If you have a car, didn't sign up for driving, but would consider driving, please let me know. We'll need extra space to bring food and drinks over to Big Bear, so we still don't have enough drivers right now. Gas cost will be reimbursed!
";
        wtf=" as well";
    else
        driver="";
        wtf="";
    fi

    if [ ${args[6]} -eq 1 ]; then
        cabin="
Lastly, the cabin you're in is currently OVER CAPACITY. Please think about moving to another cabin like Racket Club. If your cabin is still overfilled in 24 hours, I will have to move people around. We have enough beds, so we won't let them go to waste!
";
    else
        cabin="";
    fi

    echo "Hey ${args[0]},

Just as a heads up, here's the latest record on your ski trip payment.

You are$lift getting a lift ticket, so your balance due is \$$balance. As of when I'm sending this email, $payment 
$driver$cabin
If I made a mistake, let me know ASAP so I can correct your information.

Happy winter! :)

Fred" | mail -aFROM:fredzhao@caltech.edu -s "Ski trip stuff" ${args[1]}
    
done
