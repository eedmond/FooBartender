#include "MovementStringMessageDeserializer.h"

MovementStringMessageDeserializer::MovementStringMessageDeserializer(unsigned char* data, int sizeOfData)
: MessageDeserializer(data, sizeOfData)
{
    this->ParseMessage();
    
    /* parse the payload and store in the valveTimes array */
    int payloadIndex = 0;
    
    this->_stationToMoveTo = this->message.payload[payloadIndex++];
    for (int i = 0; i < 16; i++)
    {
        unsigned int byteOne = this->message.payload[payloadIndex++];
        unsigned int byteTwo = this->message.payload[payloadIndex++];
        this->_valveTimes[i] = (byteOne*256 + byteTwo);
    }
}


int MovementStringMessageDeserializer::GetValveTime(int valve)
{
    return this->_valveTimes[valve];
}


int MovementStringMessageDeserializer::GetStationToMoveTo()
{
    return this->_stationToMoveTo;
}
