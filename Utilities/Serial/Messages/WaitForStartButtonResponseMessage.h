#pragma once

#include "ResponseMessage.h"
#include "../States/Contacts.h"

using namespace std;

class WaitForStartButtonResponseMessage : public ResponseMessage
{
  private:
	void HandleUnexpectedResponse();

  public:
	WaitForStartButtonResponseMessage();
};