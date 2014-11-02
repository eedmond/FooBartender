#pragma once

#include "SendMessage.h"

class RequestResendMessage : public SendMessage
{
  public:
	RequestResendMessage();
	void Send(unsigned char expectedPayloadId);
};