#include <iostream>
#include <exception>
#include <stdexcept>
#include <cstdlib>
#include <filesystem>
#include <map>
#include <string>
#include <vector>
#include <algorithm>
#include <sstream>
#include <fstream>

#include "incl_/ledger_entry.h"



using namespace std;


void entry_file(string acct, string currency, int start, int amt, string category = "revenue"  )
{
  ofstream   out(acct + ".j") ;

  annual_entries( out,  category + ":" + acct , "assets:cash", currency, 
		     start, amt, 
		     2026, 2060  ) ;


}


int main(int argc, char* argv[])
{

try {
  cout << "RTMNT" << endl << endl;
single_entry(cout, 2029, "revenue:socsecp", "assets:cash", "£", 31800);
  cout  << endl << endl;



ofstream   out("socsecp.j") ;

//  annual_entries( out,  "revenue:socsecp" , "assets:cash", "$", 2030, 31800, 2026, 2040  ) ;

entry_file("socsecp", "$", 228, 31800);
entry_file("socsecg", "$", 2032, 15900);
// entry_file("tkpensp", "$", 2028, 15900);

// take 30k out,to get  through 27
// take 99k for 28
entry_file("irap", "$", 2029, 13000,  "assets:grodin"); // ( / 453 33 ) 
entry_file("irag", "$", 2030, 21000 , "assets:grodin"); // (/ 708 33) 

entry_file("tkpensp", "$", 2028, 9000); //
entry_file("tkpensg", "$", 2030, 4500); //

entry_file("ukpensp", "£",  2028, 5700); //
entry_file("ukpensg", "£",  2028, 26400); //







}
catch (const std::exception& e)
{
  std::cerr << "Ooops!   " << e.what() << std::endl;
  return EXIT_FAILURE;
}

return EXIT_SUCCESS;
}
