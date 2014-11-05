#include "InitialContactCheckMessageSerializer.h"

InitialContactCheckMessageSerializer:: InitialContactCheckMessageSerializer(int contacts[])
{
    this->message.header.destinationAddress = ADDRESS_PI;
    this->message.header.payloadID = PAYLOAD_ARDUINO_INIT_CONTACTS;
    this->message.payloadSize = PAYLOAD_SIZE_ARDUINO_INIT_CONTACTS;
    
    this->message.payload = new unsigned char[1];
    
    for (int i = 0; i < 8; i++)
    {
        if (contacts[i] == 1)
        {
            this->message.payload[0] |= 1 << i;
        }
        else
        {
            this->message.payload[0] &= ~(1 << i);
        }
    }
    
    this->message.header.messageSize = this->message.CalculateSize();
    this->message.checksum = this->message.CalculateChecksum();
}
