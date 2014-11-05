#include "MessageSerializer.h"

MessageSerializer::MessageSerializer()
{
    this->messageString = 0;
}


MessageSerializer::~MessageSerializer()
{
    if (this->messageString)
    {
        delete[] this->messageString;
        this->messageString = 0;
    }
}


unsigned char* MessageSerializer::GetMessageString()
{
    return this->messageString;
}


void MessageSerializer::CreateMessageString()
{
    this->messageString = new unsigned char[this->message.header.messageSize];
    
    int index = 0;
    
    /* add header to string */
    this->messageString[index++] = this->message.header.destinationAddress;
    this->messageString[index++] = this->message.header.messageSize;
    this->messageString[index++] = this->message.header.payloadID;
    
    /* add payload to string */
    for (int i = 0; i < this->message.payloadSize; i++)
    {
        this->messageString[index++] = this->message.payload[i];
    }
    
    /* add footer to string */
    this->message.checksum = this->message.CalculateChecksum();
    this->messageString[index] = this->message.checksum;
 
}
