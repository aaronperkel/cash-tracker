#!/bin/bash

DB_USER="aperkel_writer"
DB_PASS="A<_QNc.Hd[.W!IFS3[,t"
DB_NAME="APERKEL_cashtrack"
DB_HOST="webdb.uvm.edu"

QUERY="SELECT payer, amount, percentage FROM charges WHERE settled = 0"

RESULT=$(mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME -e "$QUERY" -s)

aaron_owes=0
riley_owes=0

while read -r payer amount percentage; do
    owed_amount=$(echo "$amount * ($percentage / 100)" | bc -l)
    if [ "$payer" == "Aaron" ]; then
        riley_owes=$(echo "$riley_owes + $owed_amount" | bc -l)
    else
        aaron_owes=$(echo "$aaron_owes + $owed_amount" | bc -l)
    fi
done <<< "$RESULT"

total=$(echo "$riley_owes - $aaron_owes" | bc -l)

if (( $(echo "$total > 0" | bc -l) )); then
    message="Riley owes Aaron \$$(printf "%.2f" $total)"
elif (( $(echo "$total < 0" | bc -l) )); then
    message="Aaron owes Riley \$$(printf "%.2f" $(echo "$total * -1" | bc -l))"
else
    message="You are all settled up!"
fi

echo "Weekly Expense Summary: $message" | /usr/bin/mail -s "Weekly Expense Summary" aperkel@uvm.edu
