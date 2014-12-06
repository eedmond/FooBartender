#pragma once

#include "MoveAndPourState.h"
#include "../Messages/WaitForStartButtonResponseMessage.h"
#include <cstring>
#include <algorithm>
#include <string>

class WaitForStartButtonState : public MoveAndPourState
{
  public:
	WaitForStartButtonState();
	void Respond();
};