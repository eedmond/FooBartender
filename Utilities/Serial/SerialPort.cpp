#include "SerialPort.h"

int SerialPort::fileDescriptor = -1;

void SerialPort::OpenConnection()
{
	fileDescriptor = serialOpen("/dev/serial/by-id/usb-Arduino__www.arduino.cc__0043_95238343234351013290-if00", 115200);

	if(fileDescriptor == -1)
	{
		throw new Exception("Could not establish connection to Arduino.");
	}
}

void SerialPort::Send(char* buffer, int size)
{
	if (fileDescriptor == -1)
	{
		SerialPort::OpenConnection();
	}

	write(fileDescriptor, buffer, size);
	tcdrain(fileDescriptor);
}