#ifndef MOVE_AND_POUR_STATE
#define MOVE_AND_POUR_STATE

#include "SerialState.h"
#include "OrderInfo.h"
#include "../Messages/MoveAndPourMessage.h"
#include <vector>
#include <sqlite3.h>
#include <cstring>

using namespace std;

class MoveAndPourState : public SerialState
{
  protected:
	OrderInfo orderInfo;
	void CreateMessage();

  public:
	MoveAndPourState(int payloadResponseSize = 32) : SerialState(payloadResponseSize) {}
	void Respond();
	SerialState* NextState();
};

#endif