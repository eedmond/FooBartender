#include "StartUpResponseMessage.h"

StartUpResponseMessage::StartUpResponseMessage()
{
	expectedPayloadId = 0xA0;
	expectedPayloadSize = 0;
	messageTimeoutMilliseconds = -1;
}

void StartUpResponseMessage::HandleUnexpectedResponse()
{
	throw runtime_error("Unexpected response in Startup State"); // TODO Handle logic here
}