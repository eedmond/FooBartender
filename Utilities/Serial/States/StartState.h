#ifndef START_STATE
#define START_STATE

#ifndef PI_STARTUP_FAILURE
#define ARDUINO_STARTUP_SUCCESS 0xA0
#define ARDUINO_STARTUP_FAILURE 0xA1
#define PI_STARTUP_SUCCESS 0xA2
#define PI_STARTUP_FAILURE 0xA3
#endif

#include "SerialState.h"
#include "WaitForStartButtonState.h"
#include "../Messages/StartUpSendMessage.h"
#include "../Messages/StartUpResponseMessage.h"
#include "../SerialPort.h"

class StartState : public SerialState
{
  private:
	bool VerifyResponse();

  public:
	StartState();
	void Respond();
	SerialState* NextState();
};

#endif