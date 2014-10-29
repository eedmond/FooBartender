#include "MoveAndPourMessage.h"
#include "../States/WaitForStartButtonState.h"

MoveAndPourMessage::MoveAndPourMessage()
{
	destination = 0x01;
	
	try
	{
		// TODO : Make sure it listens for arduino response on final step!!!!!!!!!!!

		payload = OrderInfo::GetNextOrderString(); // throws exception when order completed
		messageSize = Message::BaseMessageSize + 33;
		payloadID = NEXT_MOVE;
	}
	catch (exception e)
	{
		messageSize = Message::BaseMessageSize;
		payloadID = ORDER_COMPLETE;
	}
	
	checkSum = CalculateCheckSum();
}