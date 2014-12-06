#include "WaitForStartButtonState.h"

WaitForStartButtonState::WaitForStartButtonState()
{
	response = new WaitForStartButtonResponseMessage();
}

void WaitForStartButtonState::Respond()
{
	orderInfo.Initialize();
	CreateMessage();
}