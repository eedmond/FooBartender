#include "SerialPort.h"
#include <iostream> // TEMP

int SerialPort::fileDescriptor = -1;

void SerialPort::OpenConnection()
{
	//fileDescriptor = serialOpen("/dev/serial/by-id/usb-Arduino__www.arduino.cc__0043_95238343234351013290-if00", 115200);

	if(fileDescriptor == -1)
	{
		throw exception(); //"Could not establish connection to Arduino."
	}
}

void SerialPort::Send(char* buffer, int size)
{
	/*if (fileDescriptor == -1)
	{
		SerialPort::OpenConnection();
	}

	write(fileDescriptor, buffer, size);
	tcdrain(fileDescriptor);*/
	
	write(STDOUT_FILENO, buffer, size);
}