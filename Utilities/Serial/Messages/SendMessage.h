#ifndef SEND_MESSAGE
#define SEND_MESSAGE

#include "Message.h"
#include "../SerialPort.h"
#include <cstring>

class SendMessage : public Message
{
  private:
	void CreateMessageString(char* concatenatedMessage);

  public:
	void Send();
};

#endif