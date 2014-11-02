#include "MoveAndPourSendMessage.h"

MoveAndPourSendMessage::MoveAndPourSendMessage()
{
	destination = 0x01;

	try
	{
		payload = OrderInfo::GetNextOrderString(); // throws exception when order completed
		messageSize = Message::BaseMessageSize + 33;
		payloadId = NEXT_MOVE;
	}
	catch (runtime_error& e)
	{
		payload = nullptr;
		cout << "Caught exception: " << e.what() << endl;
		messageSize = Message::BaseMessageSize;
		payloadId = ORDER_COMPLETE;
	}

	checkSum = CalculateCheckSum();
}