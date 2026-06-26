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





using namespace std;


int main(int argc, char* argv[])  
{ 
 
try {
  cout << "RTMNT" << endl;





}
catch (const std::exception& e) 
{
  std::cerr << "Ooops!   " << e.what() << std::endl;
  return EXIT_FAILURE;
} 

return EXIT_SUCCESS;
}
