#include "StartState.h"

StartState::StartState()
{
	cout << "Entering startup state" << endl;
	response = new StartUpResponseMessage();
}

void StartState::Respond()
{
	StartUpSendMessage message;
	
	if (VerifyResponse())
	{
		message.SetResult(PI_STARTUP_SUCCESS);
	}
	else
	{
		message.SetResult(PI_STARTUP_FAILURE);
	}
	
	//message.Send();
}

bool StartState::VerifyResponse()
{
	unsigned char actualDestination = response->destination;
	unsigned char arduinoStartupCode = response->payloadId;
	
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