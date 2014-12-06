#include "ValveTimesFinalMessageSerializer.h"

ValveTimesFinalMessageSerializer::ValveTimesFinalMessageSerializer(int times[])
{
    this->message.header.destinationAddress = ADDRESS_PI;
    this->message.header.payloadID = PAYLOAD_ARDUINO_TIME_POURED;
    this->message.payloadSize = PAYLOAD_SIZE_ARDUINO_TIME_POURED;
    
    this->message.payload = new unsigned char[32];
    
    int payloadIndex = 0;
    unsigned char byteOne = 0x00, byteTwo = 0x00;
    for (int i = 0; i < 16; i ++) // two bytes per station
    {
        byteOne = (unsigned char)(0xFF & times[i]);
        byteTwo = (unsigned char)((0xFF00 & times[i]) >> 8);
        
        this->message.payload[payloadIndex++] = byteTwo;
        this->message.payload[payloadIndex++] = byteOne;
    }
    
    this->message.header.messageSize = this->message.CalculateSize();
    this->message.checksum = this->message.CalculateChecksum();
}

