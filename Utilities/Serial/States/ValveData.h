#ifndef VALVE_DATA
#define VALVE_DATA

#include <vector>
#include <cstring>
#include <string>
#include <stdexcept>
#include <sqlite3.h>

using namespace std;

class ValveData
{
  private:
	static sqlite3* db;
	static vector< vector<int> > volumeCommands;
	static vector<int> databaseValveVolumes;
	static vector<int> queueValveVolumes;

	static void ZeroVectors();
	static void PullStationVolumes();
	static void PullOrdersFromQueue(unsigned char initialContacts);
	static int VolumeToTime(int volumeToPour, int currentValveVolume);
	static unsigned char* IntToUnsignedCharStar(int valueAsInt);
	static int TimeToVolume(int timeValveOpened, int valve);
	static int PullQueueVolumes(void *data, int argc, char **argv, char **azColName);
	static void PullOrdersFromQueue(char initialContacts);
	static int DatabaseValveVolumesCallback(void *data, int argc, char **argv, char **azColName);
	static void PullDatabaseValveVolumes();
	static void OpenDatabase();
	static void CloseDatabase();

  public:
	static void Initialize(unsigned char initialContacts);
	static bool IsStationValid(int station);
	static unsigned char* PourNextValveAtStation(int stationToMove, int valve);
	static void UpdateVolumes(unsigned char* timesPoured);
	static void UploadBackToDatabase();
};

#endif