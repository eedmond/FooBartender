#include "SerialPort.h"
#include "./Messages/Message.h"
#include "./States/StartState.h"
#include <exception>

using namespace std;

void HandleNextState(SerialState* state);

int main()
{
	SerialState* state = new StartState();

	try
	{
		HandleNextState(state);
	}
	catch (exception e)
	{
		//error handling
	}
	
	return 0;
}

void HandleNextState(SerialState* state)
{
	state->GetResponse();
	state->Respond();
	
	SerialState* nextState = state->NextState();
	delete state;
	
	return HandleNextState(nextState);
}