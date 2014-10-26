#ifndef WAIT_FOR_START_BUTTON_STATE
#define WAIT_FOR_START_BUTTON_STATE

#include "MoveAndPourState.h"

class WaitForStartButtonState : public MoveAndPourState
{
  private:	
	char GetInitialContacts();
	void PullOrdersFromQueue(char initialContacts);

  public:
	WaitForStartButtonState() : MoveAndPourState() {}
	void Respond();
	SerialState* NextState();
};

#endif