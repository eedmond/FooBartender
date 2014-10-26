#include "Message.h"

char Message::CalculateCheckSum()
{
	char calculatedSum = 0;
	
	calculatedSum ^= destination;
	calculatedSum ^= messageSize;
	calculatedSum ^= payloadID;

	for (int i = 0; i < messageSize - 4; ++i)
	{
		calculatedSum ^= payload[i];
	}

	return calculatedSum;
}

Message::~Message()
{
	delete[] payload;
}