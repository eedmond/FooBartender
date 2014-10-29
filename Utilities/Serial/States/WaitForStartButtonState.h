#ifndef WAIT_FOR_START_BUTTON_STATE
#define WAIT_FOR_START_BUTTON_STATE

#include "MoveAndPourState.h"
#include <cstring>
#include <algorithm>
#include <string>

class WaitForStartButtonState : public MoveAndPourState
{
  private:
	char GetInitialContacts();
	void PullOrdersFromQueue(char initialContacts);
	void PullStationVolumes();

  public:
	WaitForStartButtonState();
	~WaitForStartButtonState();
	void Respond();
};

#endif