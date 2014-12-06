#include "WaitForStartButtonResponseMessage.h"

WaitForStartButtonResponseMessage::WaitForStartButtonResponseMessage()
{
	expectedPayloadId = 0xB0;
	expectedPayloadSize = 0;
	messageTimeoutMilliseconds = -1;
}

void WaitForStartButtonResponseMessage::HandleUnexpectedResponse()
{
	if (payloadId == 0xC0)
	{
		Contacts::UpdateContacts(payload[0]);
		cout << "Contact Payload: " << (int) payload[0] << endl;
	}
	else
	{
		throw runtime_error("Unexpected response in WaitForStartButtonState"); // TODO Handle logic here
	}
}