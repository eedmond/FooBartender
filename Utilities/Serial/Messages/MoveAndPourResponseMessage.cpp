#include "MoveAndPourResponseMessage.h"

MoveAndPourResponseMessage::MoveAndPourResponseMessage()
{
	expectedPayloadId = 0xB2;
	expectedPayloadSize = 32;
	#ifndef DEBUG
		messageTimeoutMilliseconds = -1;
	#else
		messageTimeoutMilliseconds = -1;
	#endif
}

void MoveAndPourResponseMessage::HandleUnexpectedResponse()
{
	throw runtime_error("Unexpected response for move and pour state"); // TODO Handle logic
}