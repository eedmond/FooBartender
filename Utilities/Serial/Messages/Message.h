#ifndef SERIAL_MESSAGE
#define SERIAL_MESSAGE

class Message
{
  public:
	char destination;
	char messageSize; //The size in bytes of the entire message (including the payload).
	char payloadID;
	char* payload;
	char checkSum;
	static char BaseMessageSize = 0x04;

	~Message();
	Message();
	char CalculateCheckSum();
};

#endif