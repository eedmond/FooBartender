#pragma once

#include "Message.h"
#include "SendMessage.h"
#include "../SerialPort.h"
#include <iostream>
#include <unistd.h>
#include <poll.h>

using namespace std;

typedef struct pollfd pollfd;

class ResponseMessage : public Message
{
  protected:
	static unsigned char PayloadIdToSize(unsigned char payloadId);
	void ConstructResponse(pollfd& parameters);
	static void WaitForDataAvailable(pollfd& parameters, int timeOut);
	void VerifyHeader();
	void VerifyCheckSum();
	void BuildAndVerifyDestination(int nextByte);
	void BuildMessageSizeByte(int nextByte);
	void BuildPayloadIdByte(int nextByte);
	void AddToPayload(int nextByte, int payloadIndex);
	void BuildCheckSumByte(int nextByte);
	void HandleResendRequest();
	void RequestResendMessage();
	virtual void HandleUnexpectedResponse() = 0;

	unsigned char expectedPayloadId;
	unsigned char expectedPayloadSize;
	int messageTimeoutMilliseconds;

  public:
	void WaitForResponse();
};