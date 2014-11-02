OBJECTS = SerialPort.o SerialCommunication.o States/SerialState.o States/StartState.o States/WaitForStartButtonState.o States/MoveAndPourState.o States/ValveData.o States/OrderInfo.o Messages/Message.o Messages/MoveAndPourSendMessage.o Messages/MoveAndPourResponseMessage.o Messages/WaitForStartButtonResponseMessage.o Messages/ResponseMessage.o Messages/SendMessage.o Messages/StartUpSendMessage.o Messages/StartUpResponseMessage.o

FLAGS = -Wall -pedantic
LFLAGS = -lsqlite3 -lwiringPi

debug: FLAGS += -DDEBUG -g

default: SerialComm

debug: SerialComm

SerialComm: $(OBJECTS)
	g++ $(OBJECTS) -std=c++11 $(FLAGS) $(LFLAGS) -o SerialComm

%.o: %.cpp
	g++ $< -c $(FLAGS) -std=c++0x -o $@
	
clean:
	rm ./*/*.o -r -f
	rm ./*.o -r -f
	rm SerialComm -f