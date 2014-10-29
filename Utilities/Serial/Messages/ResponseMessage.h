#ifndef RESPONSE_MESSAGE
#define RESPONSE_MESSAGE

#include "Message.h"
#include "../SerialPort.h"
#include <iostream>
#include <poll.h>
#define MESSAGE_TIMEOUT_SECONDS 3

using namespace std;

typedef struct pollfd pollfd;

class ResponseMessage : public Message
{
  public:
	static ResponseMessage* WaitForResponse(unsigned char payloadResponseSize);
	static bool ValidPayload(unsigned char payloadID, unsigned char payloadSize);
	void Verify();
	
  private:
	static bool WaitAndGrabData(pollfd& parameters, unsigned char* buffer, unsigned char messageSize);
	static ResponseMessage* ConstructResponse(unsigned char* message, unsigned char messageSize, unsigned char payloadSize);
	static void SendResendMessage();
};

#endif