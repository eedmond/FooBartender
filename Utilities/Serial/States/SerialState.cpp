#include "SerialState.h"

void SerialState::GetResponse()
{
	response = ResponseMessage::WaitForResponse(responseSize);
	
	try
	{
		response->Verify();
	}
	catch (Exception e)
	{
		// TODO
		// ack for reattempt
	}
}