#include <Wire.h>
#include <AccelStepper.h>
#include <Adafruit_MCP23017.h>
#include "FooBartenderUtilities.h"
#include "Pins.h"
#include "ContactsInterface.h"
#include "ValvesInterface.h"
#include "ArduinoStartupMessageSerializer.h"
#include "InitialContactCheckMessageSerializer.h"
#include "ValveTimesFinalMessageSerializer.h"
#include "MovementStringMessageDeserializer.h"
#include "ResendMessageSerializer.h"

/* ~~~~~ function prototypes ~~~~~ */
void InitValves();
void InitMotor();
boolean HomeTable();

void SendMessage(unsigned char command, unsigned char parameter=0x00);
MessageStatus GetMessage();
MessageStatus ParseMessage(unsigned char* data, int datadataSize);

void GetOrder();
void GetValveTimes();
boolean MoveToStation();
void Pour();
void OpenValve(int valve);
void CloseValve(int valve);
void CloseAllValves();

/* ~~~~~ global variables ~~~~~ */
int currentStation = 0;
int nextStation = 0;

boolean isHomeSensorActive = false;

AccelStepper motor(1, MOTOR_STEP_PIN, MOTOR_DIR_PIN);
ContactsInterface contacts;
ValvesInterface valves;
Adafruit_MCP23017 mcp;

MessageSerializer lastSentMessage;
Message lastReceivedMessage;

void setup()
{ 
  Serial.begin(BAUD_RATE);

  InitMotor();

  contacts.InitContacts();
  
  mcp.begin();
  
  InitValves();
  
  CloseAllValves();
  
  /* home the table */
  if ( HomeTable() )
  {
    SendMessage(PAYLOAD_ARDUINO_STARTUP_SUCCESS);
  }
  else
  {
    SendMessage(PAYLOAD_ARDUINO_STARTUP_FAIL); //homing failed
  }
  
  MessageStatus messageStatus = GetMessage();
  while (messageStatus == MESSAGE_STATUS_RESEND)
  {
    MessageStatus messageStatus = GetMessage();
  }
  
  motor.setMaxSpeed(MOTOR_SPEED_FULL);
}


void loop()
{
  if ( CheckStartButton() )
  {
    SendMessage(PAYLOAD_ARDUINO_INIT_CONTACTS);
    MoveAndPour();
  }
}

////////////////////////////////////////
/// SERIAL WRITE / READ FUNCTIONS /////
//////////////////////////////////////

void SendMessage(unsigned char command, unsigned char parameter)
{
  switch (command)
  {
    case PAYLOAD_ARDUINO_STARTUP_SUCCESS:
    {
      ArduinoStartupMessageSerializer startupMessageSuccess(true);
      startupMessageSuccess.CreateMessageString();
      Serial.write(startupMessageSuccess.GetMessageString(), startupMessageSuccess.message.header.messageSize);
      lastSentMessage = startupMessageSuccess;
      break;
    }
      
    case PAYLOAD_ARDUINO_STARTUP_FAIL:
    {
      ArduinoStartupMessageSerializer startupMessageFail(false);
      startupMessageFail.CreateMessageString();
      Serial.write(startupMessageFail.GetMessageString(), startupMessageFail.message.header.messageSize);
      lastSentMessage = startupMessageFail;
      break;
    }
      
    case PAYLOAD_PI_STARTUP_SUCCESS:
      // uhhhhhh
      break;
      
    case PAYLOAD_PI_STARTUP_FAIL:
      // uhhhhhh
      break;
      
    case PAYLOAD_ARDUINO_INIT_CONTACTS:
    {
      int contactsArray[8] = {0};
      for (int i = 0; i < 8; i++)
      {
        contactsArray[i] = contacts.isContactActive(i);
      }
      InitialContactCheckMessageSerializer contactCheckMessage(contactsArray);
      contactCheckMessage.CreateMessageString();
      Serial.write(contactCheckMessage.GetMessageString(), contactCheckMessage.message.header.messageSize);
      lastSentMessage = contactCheckMessage;
      break;
    }
      
    case PAYLOAD_ARDUINO_TIME_POURED:
    {
      int valvesArray[16] = {0};
      for (int i = 0; i < 16; i++)
      {
        valvesArray[i] = valves.GetValveActualTime(i);
      }
      ValveTimesFinalMessageSerializer valveTimesMessage(valvesArray);
      valveTimesMessage.CreateMessageString();
      Serial.write(valveTimesMessage.GetMessageString(), valveTimesMessage.message.header.messageSize);
      lastSentMessage = valveTimesMessage;
      break;
    }
 
    case PAYLOAD_RESEND_MESSAGE:
    {
      ResendMessageSerializer resendMessage(parameter);
      resendMessage.CreateMessageString();
      Serial.write(resendMessage.GetMessageString(), resendMessage.message.header.messageSize);
      lastSentMessage = resendMessage;
      break;
    }
      
    /* invalid payload ID */
    default:
    {
      break;
    }
    Serial.flush();
  } 
}

MessageStatus GetMessage()
{   
  unsigned char byteArray[64] = {0};
  int byteArrayIndex = 0;
  
  while ( Serial.available() < HEADER_SIZE )
  {
  }
  
  /* read in the header */
  byteArray[byteArrayIndex++] = (unsigned char)Serial.read(); // destination address
  byteArray[byteArrayIndex++] = (unsigned char)Serial.read(); // message dataSize
  byteArray[byteArrayIndex++] = (unsigned char)Serial.read(); // payload id
  
  /* incorrect address or payload ID */
  if (byteArray[0] != ADDRESS_ARDUINO)
  {
    Serial.flush();
    return MESSAGE_STATUS_BAD_MESSAGE;
  }
  
  // TODO : Verify payloadId corresponds to valid size
  while ( Serial.available() < (byteArray[1] - HEADER_SIZE) )
  {
  }
  
  /* read in the payload and checksum */
  for (int i = 0; i < byteArray[1] - HEADER_SIZE; i++)
  {
    byteArray[byteArrayIndex++] = (unsigned char)Serial.read();
  }
  
  return ParseMessage(byteArray, byteArrayIndex);
}

MessageStatus ParseMessage(unsigned char* data, int dataSize)
{
  switch (data[2])
  {
    case PAYLOAD_PI_STARTUP_SUCCESS:
    {
      break;
    } 
    
    case PAYLOAD_PI_MOVEMENT_INFO:
    {
      MovementStringMessageDeserializer movementStringDeserializer(data, dataSize);
      nextStation = movementStringDeserializer.GetStationToMoveTo();
      for (int i = 0; i < 16; i++)
      {
        valves.SetValveFullTime(i, movementStringDeserializer.GetValveTime(i));
      }
      lastReceivedMessage = movementStringDeserializer.message;
      break;
    }
    
    case PAYLOAD_PI_MOVEMENT_COMPLETE:
    {
      MessageDeserializer movementCompleteDeserializer(data, dataSize);
      lastReceivedMessage = movementCompleteDeserializer.message;
      break;
    }
    
    case PAYLOAD_RESEND_MESSAGE:
    {
      SendMessage(data[3]);
      return MESSAGE_STATUS_RESEND;
      break;
    }
    
    /* invalid payload ID */
    default:
    {
      return MESSAGE_STATUS_BAD_MESSAGE;
      break;
    }
    return MESSAGE_STATUS_SUCCESS;
  }
}

////////////////////////////////////////
//////// UTILITY FUNCTIONS ////////////
//////////////////////////////////////

void InitValves()
{
  for (int i = 0; i < NUM_VALVES; i++)
  {
    mcp.pinMode(valves.GetValvePinAtIndex(i), OUTPUT);
  } 
}

void InitMotor()
{
  motor.setMaxSpeed(MOTOR_SPEED_FULL);
  motor.setAcceleration(MOTOR_ACCELERATION);
}

boolean HomeTable()
{ 
  /* set up motor to home table */
  motor.setMaxSpeed(MOTOR_SPEED_HOMING);
  motor.move(-MOTOR_FULL_REVOLUTION);
  
  /* move the motor until we've gone the full revolution or seen the home sensor */
  while (motor.distanceToGo() != 0)
  {
    motor.run(); 
    if (CheckHomeSensor())
    {
      isHomeSensorActive = true;
      break;
    }
  }
  
  motor.setCurrentPosition(motor.currentPosition()); // is this necessary?
  
  /* if we saw the home sensor, move back one full station */
  if (isHomeSensorActive == true)
  {
    motor.move(MOTOR_ONE_STATION);
    
    while (motor.distanceToGo() != 0)
    {
      motor.run(); 
      if ( CheckOpticalSensor() )
      {
        break; 
      }
    }
  }
  else
  {
    return false;
  }
  
  /* reset motor speed */
  motor.setMaxSpeed(MOTOR_SPEED_HOMING);
  
  /* make sure we're at the home station */
  if ( CheckOpticalSensor() )
  {
    currentStation = 0;
    return true; 
  }
  else
  {
    return false; 
  }
}

//////////////////////////////////////////
////////// MOVE / POUR FUNCTIONS ////////
////////////////////////////////////////

void MoveAndPour()
{
  MessageStatus messageStatus = GetMessage();
  while (messageStatus == MESSAGE_STATUS_RESEND)
  {
    messageStatus = GetMessage();
  }
  
  while(lastReceivedMessage.header.payloadID == PAYLOAD_PI_MOVEMENT_INFO)
  {
//    if (lastReceivedMessage.header.payloadID != PAYLOAD_PI_MOVEMENT_INFO || lastReceivedMessage.header.payloadID != PAYLOAD_PI_MOVEMENT_COMPLETE)
//    {
//       SendMessage(PAYLOAD_RESEND_MESSAGE, PAYLOAD_PI_MOVEMENT_INFO);
//    }
    MoveToStation();
    Pour();
    SendMessage(PAYLOAD_ARDUINO_TIME_POURED);
    
    MessageStatus messageStatus = GetMessage();
    while (messageStatus == MESSAGE_STATUS_RESEND)
    {
      messageStatus = GetMessage();
    }
  }
  
  /* go back to station 0 */
  nextStation = 0;
  MoveToStation();
}

boolean MoveToStation()
{
  //TODO: make sure we're at a station with optical sensor
  
  if (nextStation < 0 || nextStation > 7)
  {
    return false; 
  }
  
  int distanceToMove = (nextStation - currentStation)*(MOTOR_ONE_STATION);
  motor.move(distanceToMove);
  while (motor.distanceToGo() != 0)
  {
    motor.run(); 
  }

  if ( true /*CheckOpticalSensor()*/ )
  {
    currentStation = nextStation;
    return true;
  }
  else
  {
    return false; 
  }
}

int getContactFromValve(int valve)
{
  return ( ((valve/2 - currentStation) + 8) % 8 );
}

void Pour()
{
  valves.InitializePour();
  int maxTime = valves.GetMaxValveTime();
  unsigned long loopTime = 0; // counter for current time
  unsigned long startTime = millis(); // time we started at
  
  do
  {
    /* update current time */
    loopTime = millis() - startTime;
    
    for (int i = 0; i < NUM_VALVES; i++)
    {
      /* check if the valve should be closed */
      if (loopTime < valves.GetValveFullTime(i) && contacts.isContactActive(getContactFromValve(i)) )
      {
        if (valves.GetValveOpen(i) == false)
        {
          valves.IncrementNumValvesOpen();
          valves.SetValveOpen(i, true);
          OpenValve(i);
        }
      }
      else if (valves.GetValveOpen(i) == true)
      {
        valves.DecrementNumValvesOpen();
        valves.SetValveOpen(i, false);
        CloseValve(i);
        
        /* set the time that the valve was actually open for */
        valves.SetValveActualTime(i, loopTime);
      }
    }
  } while ( valves.GetNumValvesOpen() > 0 && loopTime <= maxTime );
  
  CloseAllValves();
}

void OpenValve(int valve)
{
  mcp.digitalWrite(valves.GetValvePinAtIndex(valve), HIGH);
}

void CloseValve(int valve)
{
  mcp.digitalWrite(valves.GetValvePinAtIndex(valve), LOW);
}

void CloseAllValves()
{
  for (int i = 0; i < 16; i++)
  {
    mcp.digitalWrite(valves.GetValvePinAtIndex(i), LOW);
  }
}
