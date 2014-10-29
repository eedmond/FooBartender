#include "WaitForStartButtonState.h"

WaitForStartButtonState::WaitForStartButtonState()
{
	payloadResponseSize = 1;
}

void WaitForStartButtonState::Respond()
{
	char initialContacts = GetInitialContacts();
	cout << "InitialContacts: " << hex << (int) initialContacts << endl;
	orderInfo.Initialize(initialContacts);

	CreateMessage();
}

char WaitForStartButtonState::GetInitialContacts()
{
	return response->payload[0];
}