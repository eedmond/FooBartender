#pragma once

#include "MoveAndPourState.h"
#include "../Messages/WaitForStartButtonResponseMessage.h"
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