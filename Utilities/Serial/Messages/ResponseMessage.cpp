#include "ResponseMessage.h"

static ResponseMessage::ResponseMessage* WaitForResponse(char payloadResponseSize)
{
	// TODO
	
	// Poll and build up response of messageSize
}

void ResponseMessage::Verify()
{
	char calculatedSum = CalculateCheckSum();
	
	if (calculatedSum != checkSum)
	{
		throw new Exception("Inconsistent checksums");
	}
}