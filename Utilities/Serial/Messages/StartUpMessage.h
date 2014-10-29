#ifndef STARTUP_MESSAGE
#define STARTUP_MESSAGE

#ifndef PI_STARTUP_FAILURE
#define ARDUINO_STARTUP_SUCCESS 0xA0
#define ARDUINO_STARTUP_FAILURE 0xA1
#define PI_STARTUP_SUCCESS 0xA2
#define PI_STARTUP_FAILURE 0xA3
#endif

#include "SendMessage.h"

class StartUpMessage : public SendMessage
{
  public:
	StartUpMessage();

	void SetResult(char code = PI_STARTUP_FAILURE);
};

#endif