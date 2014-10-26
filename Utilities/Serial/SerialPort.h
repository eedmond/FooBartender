#ifndef SERIAL_PORT
#define SERIAL_PORT

#include "termios.h"
#include "unistd.h"

class SerialPort
{
  private:
	static int fileDescriptor;
	static void OpenConnection();

  public:
	static void Send(char* buffer, int messageSize);
}

#endif