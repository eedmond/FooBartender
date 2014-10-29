#include "Message.h"
#include <iostream> //TEMP

unsigned char Message::CalculateCheckSum()
{
	unsigned char calculatedSum = 0;
	
	calculatedSum ^= destination;
	calculatedSum ^= messageSize;
	calculatedSum ^= payloadID;

	for (int i = 0; i < (int) messageSize - 4; ++i)
	{
		calculatedSum ^= payload[i];
	}

	return calculatedSum;
}

Message::~Message()
{
	delete[] payload;
}