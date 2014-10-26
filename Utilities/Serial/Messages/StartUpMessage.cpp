#include "StartUpMessage.h"

StartUpMessage::StartUpMessage(char code = PI_STARTUP_FAILURE)
{
	destination = 0x01;
	messageSize = Message::BaseMessageSize;
	payloadID = code;
	payload = new char [1];
	payload[0] = '\0';
	checkSum = CalculateCheckSum();
}