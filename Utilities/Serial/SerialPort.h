#ifndef SERIAL_PORT
#define SERIAL_PORT

#include <termios.h>
#include <unistd.h>
#include <cstdio>
#include <wiringSerial.h>
#include <exception>

using namespace std;

class SerialPort
{
  private:
	static void OpenConnection();

  public:
	static int fileDescriptor; // TODO Set back to private later
	static void Send(char* buffer, int messageSize);
};

#endif