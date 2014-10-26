#ifndef RESPONSE_MESSAGE
#define RESPONSE_MESSAGE

#include "Message.h"
#include "SerialPort.h"

class ResponseMessage : public Message
{
  protected:
	static Message* WaitForResponse(int messageSize);
	void Verify();
}

#endif