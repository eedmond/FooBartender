#ifndef MOVE_AND_POUR_SEND_MESSAGE
#define MOVE_AND_POUR_SEND_MESSAGE

#ifndef NEXT_MOVE
#define NEXT_MOVE 0xB1
#define ORDER_COMPLETE 0xB3
#endif

#include "SendMessage.h"
#include "../States/OrderInfo.h"

class MoveAndPourSendMessage : public SendMessage
{
  public:
	MoveAndPourSendMessage();
};

#endif