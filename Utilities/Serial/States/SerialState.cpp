#include "SerialState.h"

void SerialState::GetResponse()
{
	response = ResponseMessage::WaitForResponse(payloadResponseSize);
	
	try
	{
		response->Verify();
	}
	catch (exception e)
	{
		// TODO
		// ack for reattempt
	}
}