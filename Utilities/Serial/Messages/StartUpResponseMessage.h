#ifndef START_UP_RESPONSE_MESSAGE
#define START_UP_RESPONSE_MESSAGE

#include "ResponseMessage.h"

using namespace std;

class StartUpResponseMessage : public ResponseMessage
{
  private:
	void HandleUnexpectedResponse();

  public:
	StartUpResponseMessage();
};

#endif