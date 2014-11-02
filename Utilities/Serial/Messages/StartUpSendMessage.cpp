#include "StartUpSendMessage.h"

StartUpSendMessage::StartUpSendMessage()
{
	destination = 0x01;
	messageSize = Message::BaseMessageSize;
	payloadId = PI_STARTUP_FAILURE;
}

void StartUpSendMessage::SetResult(unsigned char code)
{
	payloadId = code;
	payload = new unsigned char [1];
	payload = '\0';
	checkSum = CalculateCheckSum();
}