
CXX = g++
CXXFLAGS = -std=c++23 -fmodules-ts -g -Wall
TARGET = RTMNT
SOURCE = RTMNT.cpp

YEAR ?=2060
CASH ?=cash

all:

$(TARGET): $(SOURCE)
	$(CXX) $(CXXFLAGS) -o $(TARGET) $(SOURCE)

clean:
	rm -f $(TARGET)

run:
	./$(TARGET)

install:
	cp $(TARGET) ~/.local/bin


bal:
	hledger -f data_/RTMNT.j balance  date:2026-01-01..$(YEAR)-12-25 --strict


cash:  
	hledger -f data_/RTMNT.j balance $(CASH)  --begin=2026-06-01 --end=$(YEAR)-12-25 -X $


# hledger -f data_/RTMNT.j balance $(CASH) date:2026-01-01..$(YEAR)-12-25 -X $ 
