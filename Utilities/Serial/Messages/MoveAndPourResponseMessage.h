#pragma once

#include "ResponseMessage.h"

using namespace std;

class MoveAndPourResponseMessage : public ResponseMessage
{
  private:
	void HandleUnexpectedResponse();

  public:
	MoveAndPourResponseMessage();
};