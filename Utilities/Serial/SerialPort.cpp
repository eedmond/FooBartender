#include "SerialPort.h"
#include <iostream> // TEMP

int SerialPort::fileDescriptor = -1;

SerialPort::~SerialPort()
{
	if (fileDescriptor == -1)
	{
		serialClose(fileDescriptor);
	}
}

void SerialPort::OpenConnection()
{
	#ifdef DEBUG
		fileDescriptor = STDIN_FILENO;
		return;
	#endif
	fileDescriptor = serialOpen("/dev/serial/by-id/usb-Arduino__www.arduino.cc__0043_95238343334351800171-if00", 115200);

	if (fileDescriptor == -1)
	{
		fileDescriptor = serialOpen("/dev/serial/by-id/usb-Arduino__www.arduino.cc__0043_95238343334351800171-if00", 115200);
		if (fileDescriptor == -1)
		{
			throw runtime_error("Could not establish connection to Arduino.");
		}
	}
}

int SerialPort::GetFileDescriptor()
{
	if (fileDescriptor == -1)
	{
		SerialPort::OpenConnection();
	}
	
	return fileDescriptor;
}

void SerialPort::Send(const unsigned char* buffer)
{
	if (fileDescriptor == -1)
	{
		SerialPort::OpenConnection();
	}
	
	unsigned char size = buffer[1];
	
	cout << "Sending: ";
	for (int i = 0; i < size; ++i)
	{
		cout << hex << (int) buffer[i] << " ";
	}
	cout << endl;

	#ifdef DEBUG
		write(STDOUT_FILENO, buffer, size);
	#else
		write(fileDescriptor, buffer, size);
	#endif
	tcdrain(fileDescriptor);
	
	cout << "Send Complete" << endl;
}