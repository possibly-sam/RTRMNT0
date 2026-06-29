
CXX = g++
CXXFLAGS = -std=c++23 -fmodules-ts -g -Wall
TARGET = RTMNT
SOURCE = RTMNT.cpp

YEAR ?=2060
CASH ?=cash
BEGIN ?=2026
END   ?=2060

help:
	@echo bal YEAR=2034            :  balance in year 2034
	@echo cash YEAR=2034 CASH=cash :  cash balance in year 2034 in $
	@echo ccash                    :  cash balance year over year to csv file


all:  $(TARGET)

$(TARGET): $(SOURCE)
	$(CXX) $(CXXFLAGS) -o $(TARGET) $(SOURCE)

clean:
	rm -f $(TARGET)

run:
	./$(TARGET)

install:
	cp $(TARGET) ~/.local/bin


bal:
	hledger -f data_/RTMNT.j balance  date:$(BEGIN)-01-01..$(YEAR)-12-25 --strict -X $


cash:  
	hledger -f data_/RTMNT.j balance $(CASH)  --begin=$(BEGIN)-01-01 --end=$(YEAR)-12-31  --strict   -X $


ccash:
	./cash.sh
	Rscript cash-plot.R

# hledger -f data_/RTMNT.j balance $(CASH) date:2026-01-01..$(YEAR)-12-25 -X $ 

