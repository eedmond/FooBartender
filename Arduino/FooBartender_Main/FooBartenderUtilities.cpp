#include "FooBartenderUtilities.h"
#include "Pins.h"

boolean CheckStartButton()
{
  return (digitalRead(START_PIN) == HIGH)?true:false;
}

boolean CheckHomeSensor()
{
  return (analogRead(HOMING_PIN) >= 15)?true:false;
}

boolean CheckOpticalSensor()
{
  /* todo: implement debouncing */
  static int opticalSensorCounter = 0;
  static boolean isOpticalSensorPreviousHigh = false;
  
  int value = analogRead(OPTICAL_PIN);
  if (value >= 40 && isOpticalSensorPreviousHigh)
  {
    opticalSensorCounter++;
  }
  else if (value >= 40)
  {
     isOpticalSensorPreviousHigh = true;
  }
  else
  {
     isOpticalSensorPreviousHigh = false;
     opticalSensorCounter = 0;
  }
  return (opticalSensorCounter >= DEBOUNCE_MIN_COUNT)?true:false;
}

boolean DebounceHomeTable(int &debounceLowCount, int &debounceHighCount, int &debounceStatusCount)
{
  if (CheckOpticalSensor())
  {
    if (debounceStatusCount == 1)
    {
      debounceHighCount++;
      if (debounceHighCount >= DEBOUNCE_MAX_COUNT)
      {
        return true; 
      }
    } 
  }
  else
  {
    if (debounceStatusCount == 0)
    {
      debounceLowCount++;
      if (debounceLowCount >= DEBOUNCE_MIN_COUNT)
      {
        debounceStatusCount = 1;
      }
    } 
  }
  return false;
}
