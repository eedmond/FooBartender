#include "RequestResendMessage.h"

RequestResendMessage::RequestResendMessage()
{
	destination = 0x01;
	messageSize = BaseMessageSize + 1;
	payloadId = 0xE0;
	payload = new unsigned char [1];
}

void RequestResendMessage::Send(unsigned char expectedPayloadId)
{
	payloadId[0] = expectedPayloadId;
	checkSum = CalculateCheckSum();
	Send();
}