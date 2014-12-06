#include "Contacts.h"

unsigned char Contacts::activeContacts = 0;
bool Contacts::isNextMessageInitializer = true;
vector<CONTACT_STATE> Contacts::contactStates(8, LOW);

unsigned char Contacts::GetActiveContacts()
{
	isNextMessageInitializer = true;
	return activeContacts;
}

void Contacts::UpdateContacts(unsigned char contacts)
{
	unsigned char currentRead;
	
	for (int i = 0; i < 8; ++i)
	{
		currentRead = (contacts & (1 << i)) >> i;
		if (currentRead == 0)
		{
			SetContact(i, LOW);
		}
		else
		{
			if (isNextMessageInitializer == true)
			{
				SetContact(i, INVALID);
			}
			else
			{
				if (contactStates[i] == LOW)
				{
					SetContact(i, HIGH);
				}
			}
		}
	}
	isNextMessageInitializer = false;
	cout << "Contacts Updated to " << (int) activeContacts << endl;
}

void Contacts::SetContact(int station, CONTACT_STATE contactState)
{
	contactStates[station] = contactState;
	
	switch (contactState)
	{
		case (INVALID):
		case (LOW):
			activeContacts &= ~(1 << station);
		break;
		case (HIGH):
			activeContacts |= (1 << station);
		break;
	}
}