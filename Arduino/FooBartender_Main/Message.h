#pragma once

#include "Payloads.h"

struct Header
{
    unsigned char destinationAddress;
	unsigned char messageSize;
	unsigned char payloadID;
};

class Message
{
public:
    Header header;
    
    unsigned char* payload;
    
    unsigned char checksum;
    
    unsigned char payloadSize;
    
    /* functions */
    Message();  
    ~Message();
    
    unsigned char CalculateSize();
    unsigned char CalculateChecksum();
};
