#pragma once

#include <vector>
#include <iostream>

enum CONTACT_STATE {INVALID, LOW, HIGH};

using namespace std;

class Contacts
{
  private:
	static unsigned char activeContacts;
	static bool isNextMessageInitializer;
	static vector<CONTACT_STATE> contactStates;
	static void SetContact(int station, CONTACT_STATE contactState);

  public:
	static void UpdateContacts(unsigned char contacts);
	static unsigned char GetActiveContacts();
};