#include "MoveAndPourState.h"
#include "WaitForStartButtonState.h"
#include <iostream> //TEMP

void MoveAndPourState::Respond()
{
	unsigned char* timesPoured = response->payload;

	orderInfo.UpdateVolumes(timesPoured);
	CreateMessage();
}

void MoveAndPourState::CreateMessage()
{
	MoveAndPourMessage message;
	message.Send();
}

SerialState* MoveAndPourState::NextState()
{
	SerialState* nextState;
	
	if (OrderInfo::IsOrderCompleted())
	{
		cout << "Entering WaitForStartButtonState" << endl;
		OrderInfo::UploadBackToDatabase(); // TODO : Also delete queue
		nextState = new WaitForStartButtonState();
	}
	else
	{
		cout << "Entering MoveAndPourState" << endl;
		nextState = new MoveAndPourState();
	}
	
	return nextState;
}