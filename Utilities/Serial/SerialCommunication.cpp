#include "SerialPort.h"
#include "Message.h"
#include "StartState.h"

int main()
{
	SerialPort serial;
	SerialState* state = new StartState(serial);

	try
	{
		HandleNextState(state);
	}
	catch (Exception e)
	{
		//error handling
	}
	
	return 0;
}

HandleNextState(SerialState* state)
{
	state->GetResponse();
	state->Respond();
	
	SerialState* nextState = state->NextState();
	delete state;
	
	return HandleNextState(nextState);
}