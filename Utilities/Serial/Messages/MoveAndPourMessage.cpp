#include "MoveAndPourMessage.h"

MoveAndPourMessage::MoveandPourMessage()
{
	destination = 0x01;
	
	if (MoveAndPourState::IsOrderCompleted())
	{
		messageSize = Message::BaseMessageSize;
		payloadID = ORDER_COMPLETE;
	}
	else
	{
		messageSize = Message::BaseMessageSize + 33;
		payloadID = NEXT_MOVE;
		payload = MoveAndPourState::GetNextOrderString();
	}
	
	checkSum = CalculateCheckSum();
}