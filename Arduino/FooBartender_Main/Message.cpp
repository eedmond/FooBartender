#include "Message.h"

Message::Message()
{
    this->header.destinationAddress = 0x00;
    this->header.messageSize = 0x00;
    this->header.payloadID = 0x00;
    
    this->payload = 0;
}

Message::~Message()
{
    if (this->payload)
    {
        delete[] this->payload;
        this->payload = 0;
    }
}


unsigned char Message::CalculateSize()
{
    unsigned char sizeOfData = HEADER_SIZE + this->payloadSize + FOOTER_SIZE;
    return sizeOfData;
}


unsigned char Message::CalculateChecksum()
{
	char runningChecksum = 0;
    
    /* header checksum */
	runningChecksum ^= this->header.destinationAddress;
	runningChecksum ^= this->header.messageSize;
	runningChecksum ^= this->header.payloadID;
    
    /* payload checksum */
	for (int i = 0; i < this->payloadSize; i++)
	{
		runningChecksum ^= this->payload[i];
	}
    
	return runningChecksum;
}
