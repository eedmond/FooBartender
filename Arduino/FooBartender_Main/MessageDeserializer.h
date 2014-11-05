#pragma once

#include "Message.h"

class MessageDeserializer
{
public:
    Message message;
    
    unsigned char rawString[50];
    
    MessageDeserializer(unsigned char* data, int sizeOfData);
    ~MessageDeserializer();
    
    Message GetMessage();
    
    bool ParseMessage();
    bool VerifyPayloadID();
    bool VerifyChecksum();
};
