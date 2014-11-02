#ifndef STARTUP_SEND_MESSAGE
#define STARTUP_SEND_MESSAGE

#ifndef PI_STARTUP_FAILURE
#define ARDUINO_STARTUP_SUCCESS 0xA0
#define ARDUINO_STARTUP_FAILURE 0xA1
#define PI_STARTUP_SUCCESS 0xA2
#define PI_STARTUP_FAILURE 0xA3
#endif

#include "SendMessage.h"

class StartUpSendMessage : public SendMessage
{
  public:
	StartUpSendMessage();

	void SetResult(unsigned char code = PI_STARTUP_FAILURE);
};

#endif