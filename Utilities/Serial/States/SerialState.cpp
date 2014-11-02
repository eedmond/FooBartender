#include "SerialState.h"

SerialState::SerialState() : response(nullptr) {}

SerialState::~SerialState()
{
	if (response != nullptr)
	{
		delete response;
		response = nullptr;
	}
}

void SerialState::GetResponse()
{
	response->WaitForResponse();
}