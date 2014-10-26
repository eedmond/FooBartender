#include "MoveAndPourState.h"

static bool MoveAndPourState::IsOrderCompleted()
{
	return stationsToVisit.empty();
}

static char* MoveAndPourState::GetNextOrderString()
{
	char* payload = new char [33];
	char stationToMove = stationsToVisit.back();
	
	payload[0] = stationToMove;
	
	int currentValve = 0;
	for (int volumeToPour : volumeCommands[stationToMove])
	{
		char* timeToPour = VolumeToTime(volumeToPour, valveVolumes[currentValve]);
		
		int index = 2*currentValve + 1; // 2 bytes per valve with station as 1 byte offset
		payload[index] =  timeToPour[0];
		payload[index + 1] = timeToPour[1];
		
		delete[] timeToPour;
		
		++currentValve;
	}
	
	return payload;
}

// TODO : Make volumeToTime functional
static char* MoveAndPourState::VolumeToTime(int volumeToPour, int valveVolume)
{
	short timeAsShort = 500; // always half a second
	char* timeToPour = new char [2];
	
	short mask = 0xFF;
	
	timeToPour[1] = timeAsShort & mask;
	mask << 8;
	timeToPour[0] = timeAsShort & mask;
	
	return timeToPour;
}

// TODO : Make timeToVolume functional
int MoveAndPourState::TimeToVolume(char timeValveOpened, int valve)
{
	int currentVolume = valveVolumes[valve];
	
	int amountPoured = (int) timeValveOpened * 50;
	
	return amountPoured;
}

void MoveAndPourState::Respond()
{
	UpdateVolumes();
	MoveAndPourMessage message;
	message.Send();

	if (message.payloadID == ORDER_COMPLETE)
	{
		orderCompleted = true;
	}
}

void MoveAndPourState::UpdateVolumes()
{
	char* timesPoured = response->payload;
	
	for (int valve = 0; valve < 16; ++i)
	{
		char timeValveOpened = timesPoured[valve];
		int volumeValvePoured = TimeToVolume(timeValveOpened, valve);
		valveVolumes[valve] -= volumeValvePoured;
	}
}

SerialState* MoveAndPourState::NextState()
{
	SerialState* nextState;
	
	if (orderCompleted)
	{
		nextState = new WaitForStartButtonState();
	}
	else
	{
		nextState = new MoveAndPourState();
	}
	
	return nextState;
}

static int MoveAndPourState::Randomize (int i)
{
	return rand()%i;
}

static int MoveAndPourState::SetInitialVolumes(void *data, int argc, char **argv, char **azColName)
{
	int stationIndex = 0, volumeIndex = 1;
	int station = atoi(argv[stationIndex]);
	int volume = atoi(argv[volumeIndex]);
	
	valveVolumes.at(station) = volume;
	return 0;
}

static int MoveAndPourState::FormatOrders(void *data, int argc, char **argv, char **azColName)
{
	int i, j, idIndex, orderIndex, stationIndex, station, volumeToAdd;

	for (idIndex = 0; idIndex < argc; ++idIndex)
		if (!strcmp(azColName[idIndex], "id")) break;
	for (orderIndex = 0; orderIndex < argc; ++orderIndex)
		if (!strcmp(azColName[orderIndex], "orderString")) break;
	for (stationIndex = 0; stationIndex < argc; ++stationIndex)
		if (!strcmp(azColName[stationIndex], "station")) break;

	station = atoi(argv[stationIndex]);
	char* volume = strtok(argv[orderIndex], "|");

	for (i = 16; i < 32; i++)
	{
		volumeToAdd = atoi(volume);
		volumeCommands.at(((i/2-station)%8)).at(i-16) = volumeToAdd;
		volume = strtok(NULL, "|");
	}

   return 0;
}

static void MoveAndPourState::AddQueueVolumesToStations()
{
	for (int valve = 0; valve < 16; ++valve)
	{
		for (int station = 0; station < 8; ++j)
		{
			valveVolumes[valve] += volumeCommands[station][valve];
		}
	}
}

static void MoveAndPourState::InitializeStationsToVisit()
{
	for (int i = 0; i < 8; ++i)
	{
		stationsToVisit.push_back(i);
	}

	random_shuffle (stationsToVisit.begin(), stationsToVisit.end(), Randomize);
}