
CXX = g++
CXXFLAGS = -std=c++23 -fmodules-ts -g -Wall
TARGET = RTMNT
SOURCE = RTMNT.cpp

YEAR ?=2060
CASH ?=cash
BEGIN ?=2026
END   ?=2060

help:
	@echo bal YEAR=2060 BEGIN=2026         :  balance in year 2034
	@echo cash YEAR=2060 CASH=cash BEGIN=2026:  cash balance in year 2034 in $
	@echo ccash                    :  cash balance year over year to csv file
	@echo start year is 2026 unless overridden with BEGIN


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
	cp cash_balance_plot.png ../../tycho-station/pix_/

# hledger -f data_/RTMNT.j balance $(CASH) date:2026-01-01..$(YEAR)-12-25 -X $ 

