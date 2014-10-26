#include "SendMessage.h"

void SendMessage::Send()
{
	char concatenatedMessage [messageSize];
	CreateMessageString(concatenatedMessage);
	
	Serial::Send(concatenatedMessage, (int) messageSize);
}

void SendMessage::CreateMessageString(char* concatenatedMessage)
{
	concatenatedMessage[0] = destination;
	concatenatedMessage[1] = messageSize;
	concatenatedMessage[2] = payloadID;
	strcpy(concatenatedMessage + 3, payload, messageSize - BasicMessageSize);
	
	char back = messageSize - 1;
	char sum = CalculateCheckSum();
	concatenatedMessage[back] = sum;
}