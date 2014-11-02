#include "OrderInfo.h"

vector<int> OrderInfo::stationsToVisit;

void OrderInfo::Initialize(char initialContacts)
{
	srand (int (time(0)));
	valveData.Initialize(initialContacts);

	if (stationsToVisit.size() != 8)
	{
		stationsToVisit.clear();
		for (int i = 0; i < 8; ++i)
		{
			stationsToVisit.push_back(i);
		}
	}
	
	random_shuffle (stationsToVisit.begin(), stationsToVisit.end(), Randomize);
}

unsigned char* OrderInfo::GetNextOrderString()
{
	int stationToMove = GetNextStationToVisit();
	unsigned char* payload = new unsigned char [33];
	bool nonZeroValueFound = false;

	payload[0] = stationToMove;

	for (int valve = 0; valve < 16; ++valve)
	{
		unsigned char* timeToPour = valveData.PourNextValveAtStation(stationToMove, valve);

		int index = 2*valve + 1; // 2 bytes per valve with station as 1 byte offset
		payload[index] = timeToPour[0];
		payload[index + 1] = timeToPour[1];
		
		if (payload[index] != 0 || payload[index + 1] != 0)
		{
			nonZeroValueFound = true;
		}
		
		if (timeToPour != nullptr)
		{
			delete[] timeToPour;
			timeToPour = nullptr;
		}
	}
	
	if (!nonZeroValueFound)
	{
		if (payload != nullptr)
		{
			delete[] payload;
			payload = nullptr;
		}
		return GetNextOrderString();
	}

	return payload;
}

bool OrderInfo::IsOrderCompleted()
{
	return stationsToVisit.empty();
}

void OrderInfo::UpdateVolumes(unsigned char* timesPoured)
{
	valveData.UpdateVolumes(timesPoured);
}

int OrderInfo::GetNextStationToVisit()
{
	if (stationsToVisit.empty())
	{
		throw runtime_error("No more stations to move to");
	}
	int nextStation =  stationsToVisit.back();
	stationsToVisit.pop_back();
	
	return nextStation;
}

void OrderInfo::UploadBackToDatabase()
{
	valveData.UploadBackToDatabase();
}

int OrderInfo::Randomize (int i)
{
	return rand() % i;
}