#include "Message.h"
#include <iostream> //TEMP

using namespace std;

unsigned char Message::CalculateCheckSum()
{
	unsigned char calculatedSum = 0;
	
	calculatedSum ^= destination;
	calculatedSum ^= messageSize;
	calculatedSum ^= payloadId;

	for (int i = 0; i < (int) messageSize - 4; ++i)
	{
		calculatedSum ^= payload[i];
	}

	return calculatedSum;
}

unsigned char Message::CalculateCheckSum(const unsigned char* messageString)
{
	unsigned char calculatedSum = 0;
	unsigned char mMessageSize = messageString[1];
	
	for (int i = 0; i < mMessageSize - 1; ++i)
	{
		calculatedSum ^= messageString[i];
	}
	return calculatedSum;
}

void Message::PrintMessage()
{
	cout << hex << (int) destination << " " << hex << (int) messageSize << " " << hex << (int) payloadId << " ";
}

Message::Message()
{
	this->payload = nullptr;
}

Message::~Message()
{
	if (payload != nullptr)
	{
		delete[] payload;
		payload = nullptr;
	}
}