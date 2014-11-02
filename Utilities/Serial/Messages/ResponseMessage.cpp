#include "ResponseMessage.h"

unsigned char ResponseMessage::PayloadIdToSize(unsigned char payloadId)
{
	unsigned char payloadIdSize;

	switch (payloadId)
	{
		case (0xA0):
			payloadIdSize = 0;
			break;
		case (0xA1):
			payloadIdSize = 0;
			break;
		case (0xB0):
			payloadIdSize = 1;
			break;
		case (0xB2):
			payloadIdSize = 32;
			break;
		case (0xE0):
			payloadIdSize = 1;
			break;
		default:
			payloadIdSize = -1;
			break;
	}

	return payloadIdSize;
}

void ResponseMessage::WaitForResponse()
{	
	/********************* Poll and wait for message *************************/	
	pollfd parameters;
	parameters.fd = SerialPort::GetFileDescriptor();
	parameters.events = POLLIN;
	
	while (true)
	{
		try
		{
			ConstructResponse(parameters);
			
			cout << "My payloadId: " << hex << (int) this->payloadId << endl;
			cout << "expectedID: " << hex << (int) this->expectedPayloadId << endl;
			// Verify Payload
			if (this->payloadId != this->expectedPayloadId)
			{
				if (this->payloadId == 0xE0)
				{
					HandleResendRequest();
					continue;
				}
				HandleUnexpectedResponse();
			}
		}
		catch (runtime_error& e)
		{
			cout << "Caught Exception: " << e.what() << endl;
			PrintMessage();
			if (this->payload != nullptr)
			{
				delete[] this->payload;
				this->payload = nullptr;
			}
			RequestResendMessage();
			continue;
		}

		break;
	}
}

void ResponseMessage::ConstructResponse(pollfd& parameters)
{
	int fd = SerialPort::GetFileDescriptor();
	int countOfBytesRead = 0;
	int timeOut = messageTimeoutMilliseconds;
	bool buildMessageCompleted = false;
	this->destination = -1;
	this->messageSize = -1;
	this->payloadId = -1;

	//This will block until we can read 
	cout << "Starting Header Poll" << endl;
	do
	{
		WaitForDataAvailable(parameters, timeOut);
		
		// TODO : Export to function
		int numbToRead = serialDataAvail(fd);
		if (numbToRead == 0)
		{
			throw exception();
		}
		cout << "Reading " << numbToRead << " bytes" << endl;
		for (int i = 0; i < numbToRead && !buildMessageCompleted; ++countOfBytesRead, ++i)
		{
			int nextByte = serialGetchar(fd);
			#ifdef DEBUG
				if (i == numbToRead - 1)
				{
					if (nextByte == 10)
					{
						--countOfBytesRead;
						continue;
					}
				}
			#endif
			switch (countOfBytesRead)
			{
				// Destination Byte
				case (0):
					BuildAndVerifyDestination(nextByte);
					break;
				// MessageSize Byte
				case (1):
					BuildMessageSizeByte(nextByte);
					break;
				// PayloadId Byte
				case (2):
					BuildPayloadIdByte(nextByte);
					VerifyHeader();
					#ifndef DEBUG
						timeOut = 500;
					#endif
					break;
				default:
					//Handle logic of building payload/checkSum;
					if (countOfBytesRead < this->messageSize - 1)
					{
						AddToPayload(nextByte, countOfBytesRead - HEADER_SIZE);
					}
					else
					{
						BuildCheckSumByte(nextByte);
						#ifndef DEBUG // Local is unable to make values > 127
							VerifyCheckSum();
						#endif
						buildMessageCompleted = true;
					}
					break;
			}
		}
	} while (!buildMessageCompleted);
	cout << "Message Received" << endl;
}

void ResponseMessage::WaitForDataAvailable(pollfd& parameters, int timeOut)
{
	cout << "Reading..." << endl;
	int success = poll(&parameters, 1, timeOut);
	
	if (success == 0)
		throw runtime_error("Message timeout"); // Message Timeout

	if (success < 0)
		throw exception(); // TODO : Create special exception for this
}

void ResponseMessage::VerifyHeader()
{
	cout << "Header Poll Complete" << endl;
	if (this->destination != 0x3A)
	{
		throw runtime_error("Incorrect Destination Address");
	}
	unsigned char validSize = PayloadIdToSize(this->payloadId) + BaseMessageSize;

	if (validSize != this->messageSize || this->messageSize == -1)
	{
		cout << "PayloadID: " << dec << (int) this->payloadId << endl;
		cout << "Size: " << dec << (int) (PayloadIdToSize(this->payloadId) + BaseMessageSize) << endl;
		throw runtime_error("Incompatible ID/Size"); // Incompatible ID/Size
	}
}

void ResponseMessage::VerifyCheckSum()
{
	cout << "this checkSum: " << hex << (int)this->checkSum << endl;
	if (this->checkSum != CalculateCheckSum())
	{
		cout << "Expected checksum " << hex << (int) CalculateCheckSum() << endl;
		cout << "Given Checksum " << hex << (int) this->checkSum << endl;
		cout << "Payload: ";
		for (int i = 0; i < this->messageSize - BaseMessageSize; ++i)
		{
			cout << this->payload[i] << " ";
		}
		cout << endl;
		throw runtime_error("Inconsistent Checksum"); // Inconsistent CheckSum
	}
}

void ResponseMessage::BuildAndVerifyDestination(int nextByte)
{
	this->destination = nextByte;
	cout << "destination = " << hex << (int) nextByte << endl;
}

void ResponseMessage::BuildMessageSizeByte(int nextByte)
{
	this->messageSize = nextByte;
	cout << "size = " << hex << (int) nextByte << endl;
}

void ResponseMessage::BuildPayloadIdByte(int nextByte)
{
	this->payloadId = nextByte;
	cout << "id = " << hex << (int) nextByte << endl;
}

void ResponseMessage::AddToPayload(int nextByte, int payloadIndex)
{
	unsigned char verifiedMessageSize = this->messageSize;

	if (this->payload == nullptr)
	{
		this->payload = new unsigned char [verifiedMessageSize];
	}
	
	if (payloadIndex >= verifiedMessageSize)
	{
		throw runtime_error("Added too many bytes to payload");
	}

	this->payload[payloadIndex] = nextByte;
}

void ResponseMessage::BuildCheckSumByte(int nextByte)
{
	this->checkSum = nextByte;
}

void ResponseMessage::HandleResendRequest()
{
	unsigned char payloadIdToResend = this->payload[0];
	cout << "Resending Message " << hex << (int) payloadIdToResend << endl;
	SendMessage::AttemptToResendMessage(payloadIdToResend);
}

void ResponseMessage::RequestResendMessage()
{
	SendMessage::RequestResendMessage(expectedPayloadId);
}