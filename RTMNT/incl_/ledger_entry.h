#ifndef LEDGER_ENTRY_H
#define LEDGER_ENTRY_H

#include <iostream>
#include <string>
#include <iomanip>

using namespace std;

/**
 * Prints a single hledger entry to the specified ostream
 * 
 * @param out The output stream (default: cout)
 * @param year The year for the transaction
 * @param source The source account
 * @param target The target account (default: "assets:cash")
 * @param currency The currency symbol (default: "$")
 * @param amount The amount (default: 0)
 * 
 * Example usage:
 * single_entry(cout, 2029, "revenue:socsecp", "assets:cash", "$", 31800)
 * 
 * Output:
 * 2029-06-01  In
 *     revenue:socsecp   $31800
 *     assets:cash
 */
void single_entry(ostream& out = cout, int year = 0, string source = "", 
                  string target = "assets:cash", string currency = "$", 
                  int amount = 0)
{
    // Format the date as YYYY-MM-01
    out << year << "-06-01  In" << endl;
    
    // Format the source account with proper indentation and amount
    out << "    " << source;
    
    // Add spacing between account and amount
    out << string(20 - min(20, static_cast<int>(source.length())), ' ');
    
    // Format the amount with currency
    out << currency << amount << endl;
    
    // Format the target account with proper indentation
    out << "    " << target << endl;
}


void annual_entries(ostream& out,  string source, string target , string currency, 
		     int start, int amount, 
		     int begin, int end  ) {

  for (int year = begin; year != end; ++year) {
    if (year <  start) single_entry(out, year, source, target,  currency, 0);
    if (year == start) single_entry(out, year, source, target,  currency, amount/2);
    if (year >  start) single_entry(out, year, source, target,  currency, amount);


  }

}


#endif // LEDGER_ENTRY_H
