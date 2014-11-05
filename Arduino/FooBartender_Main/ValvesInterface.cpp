#include "ValvesInterface.h"

ValvesInterface::ValvesInterface()
{ 
  this->_valvesArray[0] = VALVE_0_PIN;
  this->_valvesArray[1] = VALVE_1_PIN;
  this->_valvesArray[2] = VALVE_2_PIN;
  this->_valvesArray[3] = VALVE_3_PIN;
  this->_valvesArray[4] = VALVE_4_PIN;
  this->_valvesArray[5] = VALVE_5_PIN;
  this->_valvesArray[6] = VALVE_6_PIN;
  this->_valvesArray[7] = VALVE_7_PIN;
  this->_valvesArray[8] = VALVE_8_PIN;
  this->_valvesArray[9] = VALVE_9_PIN;
  this->_valvesArray[10] = VALVE_10_PIN;
  this->_valvesArray[11] = VALVE_11_PIN;
  this->_valvesArray[12] = VALVE_12_PIN;
  this->_valvesArray[13] = VALVE_13_PIN;
  this->_valvesArray[14] = VALVE_14_PIN;
  this->_valvesArray[15] = VALVE_15_PIN;
  
  for (int i = 0; i < NUM_VALVES; i++)
  {
    this->_valveOpenFullTimes[i] = 0;
    this->_valveOpenActualTimes[i] = 0;
  }
}

void ValvesInterface::SetValveFullTime(int valve, int time)
{
  this->_valveOpenFullTimes[valve] = time;
}

void ValvesInterface::SetValveActualTime(int valve, int time)
{
  this->_valveOpenActualTimes[valve] = time;
}

int ValvesInterface::GetValvePinAtIndex(int index)
{
  return this->_valvesArray[index];
}

unsigned int ValvesInterface::GetValveFullTime(int valve)
{
  return this->_valveOpenFullTimes[valve];
}

unsigned int ValvesInterface::GetValveActualTime(int valve)
{
  return this->_valveOpenActualTimes[valve]; 
}

unsigned int ValvesInterface::GetMaxValveTime()
{
  int maxTime = 0;
  for (int i = 0; i < NUM_VALVES; i++)
  {
    if (this->_valveOpenFullTimes[i] > maxTime)
    {
      maxTime = this->_valveOpenFullTimes[i]; 
    }
  }
  return maxTime;
}

void ValvesInterface::InitializePour()
{
  for (int i = 0; i < NUM_VALVES; i++)
  {
    this->_valveOpenActualTimes[i] = 0;
    this->_valvesOpen[i] = false;
  }
  this->_numValvesOpen = 0;
}

void ValvesInterface::SetValveOpen(int valve, boolean isOpen)
{
  this->_valvesOpen[valve] = isOpen;
}

boolean ValvesInterface::GetValveOpen(int valve)
{
  return this->_valvesOpen[valve];
}

int ValvesInterface::GetNumValvesOpen()
{
  return this->_numValvesOpen;
}

void ValvesInterface::IncrementNumValvesOpen()
{
  this->_numValvesOpen++;
}

void ValvesInterface::DecrementNumValvesOpen()
{
  this->_numValvesOpen--;
}
