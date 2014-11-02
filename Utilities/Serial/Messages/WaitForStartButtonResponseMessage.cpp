#include "WaitForStartButtonResponseMessage.h"

WaitForStartButtonResponseMessage::WaitForStartButtonResponseMessage()
{
	expectedPayloadId = 0xB0;
	expectedPayloadSize = 1;
	messageTimeoutMilliseconds = -1;
}

void WaitForStartButtonResponseMessage::HandleUnexpectedResponse()
{
	throw runtime_error("Unexpected response in WaitForStartButtonState"); // TODO Handle logic here
}