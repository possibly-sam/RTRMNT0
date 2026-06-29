#!/usr/bin/env Rscript

# Simple R script to read cash balances and produce a line graph
# Usage: Rscript cash-plot.R

library(readr)
cash_balances <- read_csv("~/Documents/gh-source/RTRMNT0/RTMNT/cash_balances.csv", show_col_types = FALSE)
# View(cash_balances)

cash_balances$Cash = round(cash_balances$Cash/1000)

# Read cash balances from CSV file
# cash_data <- read.csv("cash_balances.csv")# , header=FALSE, col.names="Balance")

# Create a simple line plot
png(filename="cash_balance_plot.png", width=800, height=600)

plot(-Cash~Year, data=cash_balances);

# Add grid lines for better readability
abline(h=0, col="gray", lty=2)
grid()

dev.off()

cat("Cash balance plot saved to cash_balance_plot.png\n")