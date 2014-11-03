#include "MoveAndPourSendMessage.h"

MoveAndPourSendMessage::MoveAndPourSendMessage()
{
	destination = 0x01;
	
	if (OrderInfo::ExistsStationToMove())
	{
		payload = OrderInfo::GetNextOrderString();
		messageSize = Message::BaseMessageSize + 33;
		payloadId = NEXT_MOVE;
	}
	else
	{
		payload = nullptr;
		messageSize = Message::BaseMessageSize;
		payloadId = ORDER_COMPLETE;
		OrderInfo::OrderCompleted();
	}

	checkSum = CalculateCheckSum();
}