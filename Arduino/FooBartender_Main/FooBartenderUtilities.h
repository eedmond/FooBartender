#ifndef FOOBARTENDER_UTILITIES_H_
#define FOOBARTENDER_UTILITIES_H_

#include "Arduino.h"

/* ~~~~~ motor and communication defines ~~~~~ */
#define MOTOR_SPEED_FULL 3000
#define MOTOR_SPEED_HOMING 550
#define MOTOR_ACCELERATION 8000

#define MOTOR_FULL_REVOLUTION 13440
#define MOTOR_ONE_STATION (MOTOR_FULL_REVOLUTION / 8)

#define BAUD_RATE 115200

#define ANALOG_READ_MIN_VALUE 15

#define DEBOUNCE_MAX_COUNT 70
#define DEBOUNCE_MIN_COUNT 20

#define MESSAGE_SIZE_COMMAND 2
#define MESSAGE_SIZE_ORDER 51

#define MIN_CHARS_COMMAND 2
#define MIN_CHARS_ORDER 45

#define NUM_VALVES 16

enum Protocol_Command
{
  COMMAND_HOMING_SUCCESS = 0,
  COMMAND_HOMING_FAIL,
  COMMAND_PI_STARTUP,
  COMMAND_PI_STARTUP_FAIL, 
  COMMAND_START_BUTTON_PRESSED,
  COMMAND_PI_INCORRECT_STRING,
  COMMAND_CONTACT_CHECK,
  COMMAND_VALVE_FRACTION,
  COMMAND_BEGIN_ORDER,
  COMMAND_END_ORDER,
  COMMAND_RESEND_MESSAGE
};

enum MessageStatus
{
  MESSAGE_STATUS_SUCCESS = 0,
  MESSAGE_STATUS_RESEND,
  MESSAGE_STATUS_BAD_MESSAGE
};

enum Station
{
  STATION_0 = 0,
  STATION_1,
  STATION_2,
  STATION_3,
  STATION_4,
  STATION_5,
  STATION_6,
  STATION_7 
};

/* ~~~~~ bit operation macros ~~~~~ */
#define SET(x,y) (x |=(1<<y))
#define CLR(x,y) (x &= (~(1<<y)))
#define CHK(x,y) (x & (1<<y))
#define TOG(x,y) (x^=(1<<y))

/* ~~~~~ function prototypes ~~~~~ */
boolean CheckStartButton();
boolean CheckHomeSensor();
boolean CheckOpticalSensor();
boolean CheckStationZeroWithDebounce(int &debounceLowCount, int &debounceHighCount, int &debounceStatusCount);

#endif
