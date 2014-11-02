#include "SendMessage.h"
#include <iostream> //TEMP

unsigned char SendMessage::previousA2Message[Message::BaseMessageSize];
unsigned char SendMessage::previousB1B3Message[Message::BaseMessageSize + 33];
unsigned char SendMessage::previousResendMessage[Message::BaseMessageSize + 1];
SendMessage SendMessage::requestResendMessage;

void SendMessage::Send()
{
	serialFlush(SerialPort::GetFileDescriptor());
	const unsigned char* concatenatedMessage;
	concatenatedMessage = CreateMessageString();

	SerialPort::Send(concatenatedMessage);

	cout << "Sending Message:\n";
	cout << hex << (int) destination << ", ";
	cout << dec << (int) messageSize << ", ";
	cout << hex << (int) payloadId << ", ";
	for (int i = 0; i < messageSize - BaseMessageSize; ++i)
	{
		cout << dec << (int) payload[i] << ", ";
	}

	cout << hex << (int) checkSum << endl;
	cout << "Message Complete." << endl;
	
}

void SendMessage::RequestResendMessage(unsigned char expectedPayloadId)
{
	previousResendMessage[0] = 0x01;
	previousResendMessage[1] = BaseMessageSize + 1;
	previousResendMessage[2] = 0xE0;
	previousResendMessage[BaseMessageSize - 1] = expectedPayloadId;
	previousResendMessage[BaseMessageSize] = CalculateCheckSum(previousResendMessage);
	cout << "About To Resend Message" << hex << (int) expectedPayloadId << endl;
	SerialPort::Send(previousResendMessage);
}

const unsigned char* SendMessage::CreateMessageString()
{
	unsigned char* concatenatedMessage;
	switch (payloadId)
	{
		case(0xA2):
			concatenatedMessage = SendMessage::previousA2Message;
			break;
		case(0xB1):
		case(0xB3):
			concatenatedMessage = SendMessage::previousB1B3Message;
			break;
		case(0xE0):
			concatenatedMessage = SendMessage::previousResendMessage;
			break;
		default:
			throw runtime_error("Asked for invalid message");
	}
	concatenatedMessage[0] = destination;
	concatenatedMessage[1] = messageSize;
	concatenatedMessage[2] = payloadId;
	
	if (messageSize > BaseMessageSize)
	{
		cout << "MessageSize: " << (int) messageSize << endl;
		cout << "BaseMessageSize: " << (int) BaseMessageSize << endl;
		for (int i = BaseMessageSize; i < messageSize; ++i)
		{
			int concatIndex = i - 1;
			int zeroedIndex = i - BaseMessageSize;
			concatenatedMessage[concatIndex] = payload[zeroedIndex];
		}
	}
	
	unsigned char back = messageSize - 1;
	unsigned char sum = CalculateCheckSum();
	concatenatedMessage[(int) back] = sum;
	
	return concatenatedMessage;
}

void SendMessage::AttemptToResendMessage(unsigned char payloadIdToResend)
{
	unsigned char* messageToResend;

	switch (payloadIdToResend)
	{
		case (0xA2):
	for (int i = 0; i < 37; ++i)
	{
		cout << hex << (int) previousA2Message[i] << " ";
	}
	cout << endl;
			messageToResend = previousA2Message;
			break;
		case (0xB1):
		case (0xB3):
	for (int i = 0; i < 5; ++i)
	{
		cout << hex << (int) previousB1B3Message[i] << " ";
	}
	cout << endl;
			messageToResend = previousB1B3Message;
			break;
		default:
			throw runtime_error("Arduino requested resend of invalid message");
			break;
	}
	
	SerialPort::Send(messageToResend);
}