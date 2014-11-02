#pragma once

#include "ResponseMessage.h"

using namespace std;

class WaitForStartButtonResponseMessage : public ResponseMessage
{
  private:
	void HandleUnexpectedResponse();

  public:
	WaitForStartButtonResponseMessage();
};