#ifndef SEND_MESSAGE
#define SEND_MESSAGE

#include "Message.h"
#include "../SerialPort.h"
#include <cstring>
#include <iostream> // TEMP

class SendMessage : public Message
{
  private:
	static unsigned char previousA2Message[Message::BaseMessageSize];
	static unsigned char previousB1B3Message[Message::BaseMessageSize + 33];
	static unsigned char previousResendMessage[Message::BaseMessageSize + 1];
	static SendMessage requestResendMessage;
	const unsigned char* CreateMessageString();

  public:
	static void RequestResendMessage(unsigned char expectedPayloadId);
	static void AttemptToResendMessage(unsigned char payloadIdToResend);
	void Send();
};

#endif