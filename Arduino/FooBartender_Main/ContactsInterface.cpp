#include "ContactsInterface.h"

ContactsInterface::ContactsInterface()
{
  this->_contactsArray[0] = CONTACT_0_PIN;
  this->_contactsArray[1] = CONTACT_1_PIN;
  this->_contactsArray[2] = CONTACT_2_PIN;
  this->_contactsArray[3] = CONTACT_3_PIN;
  this->_contactsArray[4] = CONTACT_4_PIN;
  this->_contactsArray[5] = CONTACT_5_PIN;
  this->_contactsArray[6] = CONTACT_6_PIN;
  this->_contactsArray[7] = CONTACT_7_PIN;
}

void ContactsInterface::InitContacts()
{
  for (int i = 0; i < NUM_CONTACTS; i++)
  {
    pinMode(this->_contactsArray[i], INPUT);
  }
}

boolean ContactsInterface::isContactActive(int contact)
{
  return digitalRead(this->_contactsArray[contact]);
}
