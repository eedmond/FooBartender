#include <unistd.h>
#include "../SerialPort.h"
#include "ResponseMessage.h"
#define DEBUG

ResponseMessage* ResponseMessage::WaitForResponse(unsigned char payloadResponseSize)
{
	unsigned char totalMessageSize = BaseMessageSize + payloadResponseSize;

#ifndef DEBUG
	
	/********************* Poll and wait for message *************************/
	unsigned char* messageBuffer = new unsigned char[totalMessageSize];
	
	pollfd parameters;
	parameters.fd = SerialPort::fileDescriptor;
	parameters.events = POLLIN;
	
	ResponseMessage* response = nullptr;
	
	while (true)
	{
		bool success = WaitAndGrabData(parameters, messageBuffer, totalMessageSize);
		if (!success)
		{
			SendResendMessage();
			continue;
		}
		
		response = ConstructResponse(messageBuffer, totalMessageSize, payloadResponseSize);
	
		//Verify checksum
		if (response->checkSum != response->CalculateCheckSum())
		{
			SendResendMessage();
			continue;
		}
		
		try
		{
			response->Verify();
		}
		catch(exception)
		{
			SendResendMessage();
			continue;
		}
		
		break;
	}
	
	return response;
	
#else

	// Poll and build up response of messageSize
	// TEMP *********************************************************
	
	ResponseMessage* response = new ResponseMessage();
	
	int input;
	response->destination = 0x3A;
	cout << "Destination: " << hex << (int)response->destination << endl;
	response->messageSize = totalMessageSize;
	cout << "PayloadID: ";
	cin >> hex >> input;
	response->payloadID = input;
	cout << "Payload size: " << (int)payloadResponseSize << endl;
	cout << "Payload (dec): ";
	response->payload = new unsigned char [payloadResponseSize];
	for (int i = 0; i < (int)payloadResponseSize; ++i)
	{
		cin >> dec >> input;
		response->payload[i] = input;
	}
	
	response->checkSum = response->CalculateCheckSum();
	cout << "checkSum = " << (int) response->checkSum << endl;
	
#endif

	return response;
	// END TEMP *****************************************************
}

bool ResponseMessage::WaitAndGrabData(pollfd& parameters, unsigned char* buffer, unsigned char messageSize)
{
	unsigned char* currentBufferPos = buffer;
	unsigned char messageSizeRemaining = messageSize;
	bool flag = false;
	
	while (messageSizeRemaining > 0)
	{
		//This will block until we can read data
		int success = poll(&parameters, 1, MESSAGE_TIMEOUT_SECONDS * 1000);
		
		//If success is:
		//	0 = time out... try again
		//	negative = error
		
		if (success == 0)
			continue;
			
		if (success < 0)
			throw exception();
		
		//Dumps as many bytes as it can into messageBuffer (up to messageSize)
		int numbRead = read(SerialPort::fileDescriptor, currentBufferPos, messageSizeRemaining);
		
		if (!flag && messageSize - messageSizeRemaining > 2)
		{
			if (buffer[0] != 0x3A)
				return false;
			
			unsigned char sizeByte = buffer[1];
			unsigned char payloadIdByte = buffer[2];
			
			if (!ValidPayload(payloadIdByte, sizeByte))
				return false;
			
			flag = true;
		}
		
		currentBufferPos += numbRead;
		messageSizeRemaining -= numbRead;
	}
	
	serialFlush(SerialPort::fileDescriptor);
	
	return true;
}

ResponseMessage* ResponseMessage::ConstructResponse(unsigned char* message, unsigned char messageSize, unsigned char payloadSize)
{
	ResponseMessage* response = new ResponseMessage();
	
	response->destination = message[0];
	response->messageSize = message[1];
	response->payloadID = message[2];
	for (unsigned char i = 0; i < payloadSize; ++i)
	{
		response->payload[i] = message[i+3];
	}
	response->checkSum = message[response->messageSize-1];

	return response;
}

bool ResponseMessage::ValidPayload(unsigned char payloadID, unsigned char payloadSize)
{
		char byte1 = payloadID >> 8;
		char byte2 = payloadID & 0x0F;
		
		if (byte1 == 0xA)
		{
			if (byte2 > 3)
				return false;
			
			if (payloadSize != 0)
				return false;
		}
		else if (byte1 == 0xB)
		{		
			if (byte2 == 0)
			{
				return payloadSize == 1;
			}
			else if (byte2 == 1)
			{
				return payloadSize == 33;
			}
			else if (byte2 == 2)
			{
				return payloadSize == 32;
			}
			else if (byte2 == 3)
			{
				return payloadSize == 0;
			}
			else
			{
				return false;
			}
		}
		else if (byte1 == 0xF)
		{
			if (byte2 > 2)
				return false;
				
			if (payloadSize != 0)
				return false;
		}
		else
		{
			return false;
		}
		
		return true;
}

void ResponseMessage::Verify()
{
	char calculatedSum = CalculateCheckSum();
	cout << "Expected CheckSum: " << hex << (int) checkSum << endl;
	cout << "Calculated CheckSum: " << hex << (int) calculatedSum << endl;
	
	if (calculatedSum != checkSum)
	{
		throw exception(); // Inconsistent checksums
	}
}

void ResponseMessage::SendResendMessage()
{
	//TODO: Implement this!!!
	throw "death from below :O	>>(||||||||:>"      ;
}