OBJECTS = SerialPort.o SerialCommunication.o States/SerialState.o States/StartState.o States/WaitForStartButtonState.o States/MoveAndPourState.o States/ValveData.o States/OrderInfo.o Messages/Message.o Messages/MoveAndPourMessage.o Messages/ResponseMessage.o Messages/SendMessage.o Messages/StartUpMessage.o

FLAGS = -Wall -lsqlite3 -lwiringPi -g


SerialComm: $(OBJECTS)
	g++ $(OBJECTS) -std=c++11 $(FLAGS) -o SerialComm

%.o: %.cpp %.h
	g++ $< -c $(FLAGS) -std=c++0x -o $@
	
clean:
	rm *.o -r
	rm SerialComm