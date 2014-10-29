#include "StartUpMessage.h"

StartUpMessage::StartUpMessage()
{
	destination = 0x01;
	messageSize = Message::BaseMessageSize;
	payloadID = PI_STARTUP_FAILURE;
}

void StartUpMessage::SetResult(char code)
{
	payloadID = code;
	payload = new char [1];
	payload = '\0';
	checkSum = CalculateCheckSum();
}