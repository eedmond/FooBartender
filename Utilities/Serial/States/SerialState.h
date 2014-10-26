#ifndef SERIAL_STATE
#define SERIAL_STATE

#include "SerialPort.h"

class SerialState
{
  protected:
	char payloadResponseSize;
	Message* response;

  public:
	virtual void Respond() = 0;
	virtual SerialState* NextState() = 0;

	void GetResponse();
};

#endif