#include "ArduinoStartupMessageSerializer.h"

ArduinoStartupMessageSerializer::ArduinoStartupMessageSerializer(bool isStartupSuccessful)
{
	this->message.header.destinationAddress = ADDRESS_PI;
        this->message.payloadSize = PAYLOAD_SIZE_EMPTY;
    
	if (isStartupSuccessful)
	{
		this->message.header.payloadID = PAYLOAD_ARDUINO_STARTUP_SUCCESS;
	}
	else
	{
		this->message.header.payloadID = PAYLOAD_ARDUINO_STARTUP_FAIL;
	}
    
        this->message.header.messageSize = this->message.CalculateSize();
	this->message.CalculateChecksum();
}
