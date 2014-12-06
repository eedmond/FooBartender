#include "OrderInfo.h"

vector<int> OrderInfo::stationsToVisit;
bool OrderInfo::orderComplete;

void OrderInfo::Initialize()
{
	srand (int (time(0)));
	valveData.Initialize();
	orderComplete = false;

	stationsToVisit.clear();

	for (int i = 0; i < 8; ++i)
	{
		if (valveData.IsStationValid(i))
		{
			stationsToVisit.push_back(i);
		}
	}
	
	random_shuffle (stationsToVisit.begin(), stationsToVisit.end(), Randomize);
}

bool OrderInfo::ExistsStationToMove()
{
	return !stationsToVisit.empty();
}

unsigned char* OrderInfo::GetNextOrderString()
{
	int stationToMove = GetNextStationToVisit();
	unsigned char* payload = new unsigned char [33];

	payload[0] = stationToMove;

	for (int valve = 0; valve < 16; ++valve)
	{
		unsigned char* timeToPour = valveData.PourNextValveAtStation(stationToMove, valve);

		int index = 2*valve + 1; // 2 bytes per valve with station as 1 byte offset
		payload[index] = timeToPour[0];
		payload[index + 1] = timeToPour[1];
		
		if (timeToPour != nullptr)
		{
			delete[] timeToPour;
			timeToPour = nullptr;
		}
	}

	return payload;
}

void OrderInfo::OrderCompleted()
{
	orderComplete = true;
}

bool OrderInfo::IsOrderCompleted()
{
	return orderComplete;
}

void OrderInfo::UpdateVolumes(unsigned char* timesPoured)
{
	valveData.UpdateVolumes(timesPoured);
}

int OrderInfo::GetNextStationToVisit()
{
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