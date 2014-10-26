#ifndef STARTUP_MESSAGE
#define STARTUP_MESSAGE

#define PI_STARTUP_SUCCESS 0xA0
#define PI_STARTUP_FAILURE 0xA1

#include "SendMessage.h"

class StartUpMessage : public SendMessage
{
  public:
	StartUpMessage(int code = PI_STARTUP_FAILURE);
}

#endif