#ifndef ORDER_INFO
#define ORDER_INFO

#include "ValveData.h"
#include <ctime>
#include <algorithm>

class OrderInfo
{
  private:
	static ValveData valveData;
	static bool orderComplete;
	static vector<int> stationsToVisit;
	static int Randomize (int i);
	static int GetNextStationToVisit();

  public:
	static void Initialize(char initialContacts);
	static unsigned char* GetNextOrderString();
	static void UpdateVolumes(unsigned char* timesPoured);
	static void UploadBackToDatabase();
	static bool ExistsStationToMove();
	static bool IsOrderCompleted();
	static void OrderCompleted();
};

#endif