#ifndef MOVE_AND_POUR_STATE
#define MOVE_AND_POUR_STATE

#include "SerialState.h"
#include "OrderInfo.h"
#include "../Messages/MoveAndPourSendMessage.h"
#include "../Messages/MoveAndPourResponseMessage.h"
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
	MoveAndPourState();
	void Respond();
	SerialState* NextState();
};

#endif