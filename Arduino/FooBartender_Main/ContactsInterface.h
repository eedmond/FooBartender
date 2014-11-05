#ifndef CONTACTS_INTERFACE_H_
#define CONTACTS_INTERFACE_H_

#include "Pins.h"
#include "Arduino.h"

#define NUM_CONTACTS 8

enum Contact
{
  CONTACT_0 = 0,
  CONTACT_1,
  CONTACT_2,
  CONTACT_3,
  CONTACT_4,
  CONTACT_5,
  CONTACT_6,
  CONTACT_7 
};

class ContactsInterface
{
  public:
    ContactsInterface();
    
    void InitContacts();
    
    boolean isContactActive(int contact);
    
  private:
    int _contactsArray[8];
};

#endif
