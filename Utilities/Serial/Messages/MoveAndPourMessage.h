#ifndef MOVE_AND_POUR_MESSAGE
#define MOVE_AND_POUR_MESSAGE

#ifndef NEXT_MOVE
#define NEXT_MOVE 0xB1
#define ORDER_COMPLETE 0xB3
#endif

#include "SendMessage.h"

class MoveAndPourMessage : public SendMessage
{
  public:
	MoveAndPourMessage();
};

#endif