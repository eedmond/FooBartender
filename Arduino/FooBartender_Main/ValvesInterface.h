#ifndef VALVES_INTERFACE_H_
#define VALVES_INTERFACE_H_

#include "Pins.h"
#include "Arduino.h"

#define NUM_VALVES 16

class ValvesInterface
{
  public:
    ValvesInterface();
    
    void OpenValve(int valve);
    void CloseValve(int valve);
    
    void SetValveFullTime(int valve, int time);
    void SetValveActualTime(int valve, int time);
    
    void InitializePour();
    
    int GetValvePinAtIndex(int index);
    
    unsigned int GetValveFullTime(int valve);
    unsigned int GetValveActualTime(int valve);
    unsigned int GetMaxValveTime();
    
    void SetValveOpen(int valve, boolean isOpen);
    boolean GetValveOpen(int valve);
    
    int GetNumValvesOpen();
    void IncrementNumValvesOpen();
    void DecrementNumValvesOpen();
  
  private:
    int _valvesArray[NUM_VALVES];
  
    unsigned int _valveOpenFullTimes[NUM_VALVES];
    unsigned int _valveOpenActualTimes[NUM_VALVES];
    
    boolean _valvesOpen[NUM_VALVES];
    
    int _numValvesOpen;
};

#endif
