#include "MessageSerializer.h"

class ArduinoStartupMessageSerializer : public MessageSerializer
{
public:
	ArduinoStartupMessageSerializer(bool isStartupSuccessful);
};
