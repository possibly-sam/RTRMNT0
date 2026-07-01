#include <iostream>
#include "incl_/ledger_entry.h"

using namespace std;

int main()
{
    cout << "Testing single_entry function:" << endl << endl;
    
    // Test case 1: Example from the specification
    cout << "Test 1 - Example from spec:" << endl;
    single_entry(cout, 2029, "revenue:socsecp", "assets:cash", "$", 31800);
    cout << endl << "---" << endl << endl;
    
    // Test case 2: Default parameters
    cout << "Test 2 - Default parameters:" << endl;
    single_entry(cout, 2030, "expenses:food");
    cout << endl << "---" << endl << endl;
    
    // Test case 3: Different currency
    cout << "Test 3 - Different currency:" << endl;
    single_entry(cout, 2031, "income:salary", "assets:bank", "€", 50000);
    cout << endl << "---" << endl << endl;
    
    return 0;
}