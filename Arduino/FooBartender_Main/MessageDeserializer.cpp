#include "MessageDeserializer.h"

MessageDeserializer::MessageDeserializer(unsigned char* data, int sizeOfData)
{
    this->message.header.destinationAddress = 0x00;
    this->message.header.payloadID = 0x00;
    this->message.header.messageSize = 0x00;
    
    if (sizeOfData > 0)
    {
        this->rawString = new unsigned char[sizeOfData];
        for (int i = 0; i < sizeOfData; i++)
        {
            this->rawString[i] = data[i];
        }
    }
}


MessageDeserializer::~MessageDeserializer()
{
    if (this->rawString)
    {
        delete[] this->rawString;
    }
}


Message MessageDeserializer::GetMessage()
{
    return this->message;
}


bool MessageDeserializer::ParseMessage()
{
    int index = 0;
    
    this->message.header.destinationAddress = this->rawString[index++];
    this->message.header.messageSize = this->rawString[index++];
    this->message.header.payloadID = this->rawString[index++];
    
    bool isPayloadIDValid = this->VerifyPayloadID();
    if (!isPayloadIDValid)
    {
        return false;
    }
    
    this->message.payloadSize = this->message.header.messageSize - HEADER_SIZE - FOOTER_SIZE;
    this->message.payload = new unsigned char[this->message.payloadSize];
    
    for (int i = 0; i < this->message.payloadSize; i++)
    {
        this->message.payload[i] = this->rawString[index++];
    }
    
    this->message.checksum = this->rawString[index];
    
    bool isChecksumCorrect = this->VerifyChecksum();
    if (isChecksumCorrect)
    {
        return true;
    }
    else
    {
        return false;
    }
}


bool MessageDeserializer::VerifyPayloadID()
{
    return true;
}


bool MessageDeserializer::VerifyChecksum()
{
    unsigned char calculatedChecksum = 0x00;
    
    calculatedChecksum ^= this->message.header.destinationAddress;
    calculatedChecksum ^= this->message.header.messageSize;
    calculatedChecksum ^= this->message.header.payloadID;
    
    for (int i = 0; i < this->message.payloadSize; i++)
    {
        calculatedChecksum ^= this->message.payload[i];
    }
    
    if (calculatedChecksum == this->message.checksum)
    {
        return true;
    }
    else
    {
        return false;
    }
}
