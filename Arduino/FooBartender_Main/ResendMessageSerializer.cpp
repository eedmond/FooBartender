#include "ResendMessageSerializer.h"

ResendMessageSerializer::ResendMessageSerializer(unsigned char payloadIDToResend)
{
    this->message.header.destinationAddress = ADDRESS_PI;
    this->message.header.payloadID = PAYLOAD_RESEND_MESSAGE;
    this->message.payloadSize = PAYLOAD_SIZE_RESEND_MESSAGE;
    
    this->message.payload = new unsigned char[1];
    
    this->message.payload[0] = payloadIDToResend;
    
    this->message.header.messageSize = this->message.CalculateSize();
    this->message.checksum = this->message.CalculateChecksum();
}
