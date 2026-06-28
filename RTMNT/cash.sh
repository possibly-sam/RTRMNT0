#!/bin/bash

# Output CSV file
OUTPUT_FILE="cash_balances.csv"

# Write CSV header
echo "Year,Cash" > "$OUTPUT_FILE"

# Loop through years 2026 to 2099
for YEAR in {2026..2060}; do
    # Run hledger command for the current year
    HLEDGER_OUTPUT=$(hledger -f data_/RTMNT.j balance cash date:2026-01-01..${YEAR}-12-31 2>/dev/null)

    # Extract the cash amount (assuming format: "$AMOUNT  cash")
    CASH_AMOUNT=$(echo "$HLEDGER_OUTPUT" | grep -oP '\$[\d,.-]+' | head -1)

    # Remove commas and dollar sign for cleaner CSV
    CLEAN_AMOUNT=$(echo "$CASH_AMOUNT" | tr -d '$,')

    # Write year and amount to CSV
    #echo "${YEAR},${HLEDGER_OUTPUT}" >> "$OUTPUT_FILE"
    #echo "${YEAR},${CASH_AMOUNT}" >> "$OUTPUT_FILE"
    echo "${YEAR},${CLEAN_AMOUNT}" >> "$OUTPUT_FILE"
done

echo "Cash balances written to $OUTPUT_FILE"
