#ifndef SERIAL_PORT
#define SERIAL_PORT

#include <termios.h>
#include <unistd.h>
#include <cstdio>
#include <wiringSerial.h>
#include <stdexcept>

using namespace std;

class SerialPort
{
  private:
	static void OpenConnection();
	static int fileDescriptor;

  public:
	~SerialPort();
	static int GetFileDescriptor();
	static void Send(const unsigned char* buffer);
};

#endif