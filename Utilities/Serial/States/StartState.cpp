#include "StartState.h"

void StartState::Respond()
{
	StartUpMessage message;
	
	if (VerifyResponse())
	{
		message = StartUpMessage(PI_STARTUP_SUCCESS);
	}
	else
	{
		message = StartUpMessage(PI_STARTUP_FAILURE);
	}
	
	message.Send();
}

bool StartState::VerifyResponse()
{
	char actualDestination = response->destination;
	char arduinoStartupCode = response->payloadID;
	
	if (arduinoStartupCode == ARDUINO_STARTUP_SUCCESS)
	{
		return true;
	}
	
	return false;
}

SerialState* StartState::NextState()
{
	SerialState* nextState = new WaitForStartButtonState();
	return nextState;
}