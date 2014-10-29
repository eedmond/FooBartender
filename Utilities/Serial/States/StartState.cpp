#include "StartState.h"

void StartState::Respond()
{
	StartUpMessage message;
	
	if (VerifyResponse())
	{
		message.SetResult((char) PI_STARTUP_SUCCESS);
	}
	else
	{
		message.SetResult((char) PI_STARTUP_FAILURE);
	}
	
	message.Send();
}

bool StartState::VerifyResponse()
{
	char actualDestination = response->destination;
	char arduinoStartupCode = response->payloadID;
	
	cout << "destination: " << hex << (int) actualDestination << endl;
	cout << "startCode: " << hex << (int) arduinoStartupCode << endl;
	
	if (arduinoStartupCode == ARDUINO_STARTUP_SUCCESS && actualDestination == 0x3A)
	{
		cout << "Arduino Startup Successful" << endl;
		return true;
	}
	
	cout << "Arduino Startup Failure" << endl;
	
	return false;
}

SerialState* StartState::NextState()
{
	cout << "Entering WaitForStartButtonState" << endl;
	SerialState* nextState = new WaitForStartButtonState();
	return nextState;
}