#ifndef SERIAL_STATE
#define SERIAL_STATE

#include "../SerialPort.h"
#include "../Messages/ResponseMessage.h"

class SerialState
{
  protected:
	SerialState(char x) : payloadResponseSize(x) {}
	char payloadResponseSize;
	ResponseMessage* response;

  public:
	virtual void Respond() = 0;
	virtual SerialState* NextState() = 0;

	void GetResponse();
};

#endif