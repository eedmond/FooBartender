#ifndef SERIAL_MESSAGE
#define SERIAL_MESSAGE

#define MESSAGE_TIMOUT_SECONDS 10

class Message
{
  public:
	unsigned char destination;
	unsigned char messageSize; //The size in bytes of the entire message (including the payload).
	unsigned char payloadId;
	unsigned char* payload;
	unsigned char checkSum;
	static const unsigned char BaseMessageSize = 0x04;
	static const unsigned char HEADER_SIZE = 0x03;

	Message();
	~Message();
	unsigned char CalculateCheckSum();
	static unsigned char CalculateCheckSum(const unsigned char* messageString);
	void PrintMessage();
};

#endif