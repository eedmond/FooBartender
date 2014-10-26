#ifndef MOVE_AND_POUR_STATE
#define MOVE_AND_POUR_STATE

#include "SerialState.h"

class MoveAndPourState : public SerialState
{
  protected:
	static vector<vector<int>> volumeCommands;
	static vector<int> valveVolumes;
	static vector<int> stationsToVisit;
	
	static bool IsOrderCompleted();
	static char* GetNextOrderString();
	static char* VolumeToTime(int volumeToPour, int valveVolume);
	static int TimeToVolume(char timeValveOpened, int valve);
	static int Randomize (int i);
 	static int SetInitialVolumes(void *data, int argc, char **argv, char **azColName);
	static int FormatOrders(void *data, int argc, char **argv, char **azColName);
	static void AddQueueVolumesToStations();
	static void InitializeStationsToVisit();
	void UpdateVolumes();
	bool orderCompleted;

  public:
	MoveAndPourState() : volumeCommands(8, vector<int>(16, 0)),
		stationVolumes(16, 0), payloadResponseSize(1), orderCompleted(false) {}
	void Respond();
	SerialState* NextState();
};

#endif