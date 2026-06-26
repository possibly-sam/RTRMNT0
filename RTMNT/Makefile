
CXX = g++
CXXFLAGS = -std=c++23 -fmodules-ts -g -Wall
TARGET = RTMNT
SOURCE = RTMNT.cpp

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
	hledger -f data_/RTMNT.j balance

