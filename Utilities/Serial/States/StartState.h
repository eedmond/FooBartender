#ifndef START_STATE
#define START_STATE

#include "SerialState.h"
#include "WaitForStartButtonState.h"
#include "StartUpMessage.h"
#include "SerialPort.h"

#define ARDUINO_STARTUP_SUCCESS 0xA0
#define ARDUINO_STARTUP_FAILURE 0xA1
#define PI_STARTUP_SUCCESS 0xA2
#define P1_STARTUP_FAILURE 0xA3

class StartState : public SerialState
{
  private:
	bool VerifyResponse();

  public:
	StartState() : payloadResponseSize(0) {}
	void Respond();
	SerialState* NextState();
};

#endif