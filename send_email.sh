#!/bin/bash

DB_USER="aperkel_writer"
DB_PASS="A<_QNc.Hd[.W!IFS3[,t"
DB_NAME="APERKEL_cashtrack"
DB_HOST="webdb.uvm.edu"

QUERY="SELECT payer, paid_for, amount, percentage FROM charges WHERE settled = 0"

RESULT=$(mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME -e "$QUERY" -s)

aaron_balance=0
riley_balance=0

while read -r payer paid_for amount percentage; do
    if [ "$paid_for" == "Both" ]; then
        owed_amount=$(echo "$amount * ($percentage / 100)" | bc -l)
        if [ "$payer" == "Aaron" ]; then
            riley_balance=$(echo "$riley_balance - $owed_amount" | bc -l)
        else
            aaron_balance=$(echo "$aaron_balance - $owed_amount" | bc -l)
        fi
    else
        if [ "$payer" == "Aaron" ] && [ "$paid_for" == "Riley" ]; then
            riley_balance=$(echo "$riley_balance - $amount" | bc -l)
        elif [ "$payer" == "Riley" ] && [ "$paid_for" == "Aaron" ]; then
            aaron_balance=$(echo "$aaron_balance - $amount" | bc -l)
        fi
    fi
done <<< "$RESULT"

total=$(echo "$aaron_balance - $riley_balance" | bc -l)

if (( $(echo "$total > 0" | bc -l) )); then
    message="Riley owes Aaron \$$(printf "%.2f" $total)"
elif (( $(echo "$total < 0" | bc -l) )); then
    message="Aaron owes Riley \$$(printf "%.2f" $(echo "$total * -1" | bc -l))"
else
    message="You are all settled up!"
fi

echo "Weekly Expense Summary: $message" | /usr/bin/mail -s "Weekly Expense Summary" aperkel@uvm.edu
