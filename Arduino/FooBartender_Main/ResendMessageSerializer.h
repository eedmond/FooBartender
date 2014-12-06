#include "MessageSerializer.h"

class ResendMessageSerializer : public MessageSerializer
{
public:
    ResendMessageSerializer(unsigned char payloadIDToResend);
};