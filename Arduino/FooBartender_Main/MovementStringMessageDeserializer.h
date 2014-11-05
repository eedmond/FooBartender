#include "MessageDeserializer.h"

class MovementStringMessageDeserializer : public MessageDeserializer
{
public:
    MovementStringMessageDeserializer(unsigned char* data, int sizeOfData);
    
    int GetStationToMoveTo();
    int GetValveTime(int valve);
    
private:
    int _stationToMoveTo;
    int _valveTimes[16];
};
