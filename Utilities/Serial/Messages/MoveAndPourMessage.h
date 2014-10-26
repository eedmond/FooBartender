#ifndef STARTUP_MESSAGE
#define STARTUP_MESSAGE

#define NEXT_MOVE 0xB1
#define ORDER_COMPLETE 0xB3

#include "SendMessage.h"

class StartUpMessage : public SendMessage
{
  public:
	StartUpMessage(int code = PI_STARTUP_FAILURE);
}

#endif