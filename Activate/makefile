FLAGS= -Wall -Werror -std=c++0x

all: activateSerialComm

activateSerialComm: activate.cpp
	g++ $(FLAGS) $^ -o $@

clean:
	rm activateSerialComm