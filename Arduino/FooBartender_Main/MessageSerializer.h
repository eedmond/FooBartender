#pragma once

#include "Message.h"

class MessageSerializer
{
public:
	Message message;
    
    unsigned char* messageString;
    
	MessageSerializer();
    ~MessageSerializer();
    
    unsigned char* GetMessageString();
    void CreateMessageString();
};
