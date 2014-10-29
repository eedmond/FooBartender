#include "SendMessage.h"
#include <iostream> //TEMP

void SendMessage::Send()
{
	// TEMP DEBUG OUTPUT
	cout << "Sending Message:\n";
	cout << hex << (int) destination << ", ";
	cout << dec << (int) messageSize << ", ";
	cout << hex << (int) payloadID << ", ";
	for (int i = 0; i < messageSize - BaseMessageSize; ++i)
	{
		cout << dec << (int) payload[i] << ", ";
	}

	cout << hex << (int) checkSum << endl;
	cout << "Message Complete." << endl;
	
	/* ACTUAL OUTPUT
	char concatenatedMessage [messageSize];
	CreateMessageString(concatenatedMessage);
	
	SerialPort::Send(concatenatedMessage, (int) messageSize);
	*/
}

void SendMessage::CreateMessageString(char* concatenatedMessage)
{
	concatenatedMessage[0] = destination;
	concatenatedMessage[1] = messageSize;
	concatenatedMessage[2] = payloadID;
	
	if (messageSize > BaseMessageSize)
	{
		cout << "MessageSize: " << (int) messageSize << endl;
		cout << "BaseMessageSize: " << (int) BaseMessageSize << endl;
		strncpy(concatenatedMessage + 3, payload, messageSize - BaseMessageSize);
	}
	
	char back = messageSize - 1;
	char sum = CalculateCheckSum();
	concatenatedMessage[(int) back] = sum;
}