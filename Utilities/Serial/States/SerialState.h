#ifndef SERIAL_STATE
#define SERIAL_STATE

#include "../SerialPort.h"
#include "../Messages/ResponseMessage.h"

class SerialState
{
  protected:
	ResponseMessage* response;

  public:
	SerialState();
	~SerialState();
	virtual void Respond() = 0;
	virtual SerialState* NextState() = 0;
	void GetResponse();
};

#endif