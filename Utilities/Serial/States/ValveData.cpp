#include "ValveData.h"
#include <iostream> // TEMP

vector< vector<int> > ValveData::volumeCommands(8, vector<int>(16, 0));
vector<int> ValveData::databaseValveVolumes(16, 0);
vector<int> ValveData::queueValveVolumes(16, 0);
sqlite3* ValveData::db;

void ValveData::Initialize()
{
	OpenDatabase();

	ZeroVectors();
	PullDatabaseValveVolumes();
	PullOrdersFromQueue();
	cout << "Volume Commands: " << endl;
	for (int i = 0; i < 8; ++i)
	{
		for (int j = 0; j < 16; ++j)
		{
			cout << volumeCommands[i][j] << ", ";
		}
		cout << endl;
	}

	CloseDatabase();
}

void ValveData::OpenDatabase()
{
	// Open Database
	int rc = sqlite3_open("/var/www/FB.db", &db);
	if( rc ){
		throw runtime_error("Could not connect to database");
	}
}

void ValveData::CloseDatabase()
{
	sqlite3_close(db);
}

void ValveData::ZeroVectors()
{
	for (int valve = 0; valve < 16; ++valve)
	{
		databaseValveVolumes[valve] = 0;
		queueValveVolumes[valve] = 0;

		for (int station = 0; station < 8; ++station)
		{
			volumeCommands[station][valve] = 0;
		}
	}
}

int ValveData::VolumeToTime(int volumeToPour, int currentValveVolume)
{
	long double x = currentValveVolume;
	long double y = volumeToPour;
	long double t = y * (43.5L + y * (0.01L + (.000003L + .0000000005L * y) * y)) + x * (y * (-0.02L + (-.000009L - .000000002L * y) * y) + x * (-.000000002L * x * y + (.000009L + .000000003L * y) * y));

	if (t < 0)
	{
		t = 0;
	}
	int timeToPour = t;
	return timeToPour;
}

unsigned char* ValveData::IntToUnsignedCharStar(int valueAsInt)
{
	unsigned char* valueAsCharStar = new unsigned char [2];
	
	short mask = 0xFF;
	
	valueAsCharStar[1] = valueAsInt & mask;
	mask = mask << 8;
	valueAsCharStar[0] = (valueAsInt & mask) >> 8;
	
	return valueAsCharStar;
}

unsigned char* ValveData::PourNextValveAtStation(int stationToMove, int valve)
{
	int volumeToPour = volumeCommands[stationToMove][valve];
	int currentValveVolume = databaseValveVolumes[valve] + queueValveVolumes[valve];

	int timeAsInt = VolumeToTime(volumeToPour, currentValveVolume);
	unsigned char* timeToPour = IntToUnsignedCharStar(timeAsInt);

	return timeToPour;
}

// TODO : Make timeToVolume functional
int ValveData::TimeToVolume(int timeValveOpened, int valve)
{
	int currentVolume = databaseValveVolumes[valve] + queueValveVolumes[valve];
	int amountPouredGuess = 100;
	double errorRatio;
	
	for (int i = 0; i < 50 && (errorRatio < 0.95 || errorRatio > 1.05); ++i)
	{
		double guessTime = (double) VolumeToTime(amountPouredGuess, currentVolume);
		errorRatio = timeValveOpened / guessTime;
		amountPouredGuess *= (timeValveOpened / guessTime);
	}

	return (int) amountPouredGuess;
}

void ValveData::UpdateVolumes(unsigned char* timesPoured)
{	
	for (int valve = 0; valve < 16; ++valve)
	{
		int timeValveOpened = 0;
		int index = 2*valve;

		timeValveOpened |= ((int)timesPoured[index]) << 8;
		timeValveOpened |= timesPoured[index + 1];
		cout << "Poured " << dec << timeValveOpened << " at valve " << valve << endl;
		int volumePouredAtValve = TimeToVolume(timeValveOpened, valve);
		queueValveVolumes[valve] -= volumePouredAtValve;
	}
}

void ValveData::UploadBackToDatabase()
{
	OpenDatabase();

	char sql [150];

	for (int valve = 0; valve < 16; ++valve)
	{
		int volumeToAddBack = queueValveVolumes[valve];
		if (volumeToAddBack != 0)
		{
			strncpy(sql, "UPDATE single SET volume = volume + \0", 37);
			strcat(sql, to_string(volumeToAddBack).c_str());
			strcat(sql, " WHERE station = ");
			strcat(sql, to_string(valve).c_str());

			cout << "sql: " << sql << endl;
			sqlite3_exec(db, sql, NULL, NULL, NULL);
		}
	}
	
	CloseDatabase();
}

int ValveData::PullQueueVolumes(void *data, int argc, char **argv, char **azColName)
{
	int idIndex, orderIndex, stationIndex, volumeToAdd;

	for (idIndex = 0; idIndex < argc; ++idIndex)
		if (!strcmp(azColName[idIndex], "id")) break;
	for (orderIndex = 0; orderIndex < argc; ++orderIndex)
		if (!strcmp(azColName[orderIndex], "orderString")) break;
	for (stationIndex = 0; stationIndex < argc; ++stationIndex)
		if (!strcmp(azColName[stationIndex], "station")) break;

	int cupToPourIn = atoi(argv[stationIndex]);
	char* volume = strtok(argv[orderIndex], "|");

	for (int valve = 0; valve < 16; ++valve)
	{
		int cupZeroStation = (valve/2 - cupToPourIn + 8) % 8;
		
		volumeToAdd = atoi(volume);
		volumeCommands.at(cupZeroStation).at(valve) = volumeToAdd;
		queueValveVolumes.at(valve) += volumeToAdd;
		volume = strtok(NULL, "|");
	}

	return 0;
}

void ValveData::PullOrdersFromQueue()
{
	unsigned char contactList = Contacts::GetActiveContacts();
	char sqlSelect[180];
	char sqlDelete[180];

	strncpy(sqlSelect, "SELECT * FROM queue WHERE 0\0", 28);
	strncpy(sqlDelete, "DELETE FROM queue WHERE 0\0", 26);

	for (char i = 0; i < 8; ++i)
	{
		char contactHigh = contactList & (1 << i);

		if (contactHigh != 0)
		{
			strncat(sqlSelect, " OR station=", 18);
			strncat(sqlSelect, to_string(i).c_str(), 1);
			strncat(sqlDelete, " OR station=", 18);
			strncat(sqlDelete, to_string(i).c_str(), 1);
		}
	}

	cout << "sql: " << sqlSelect << endl;

	sqlite3_exec(db, sqlSelect, PullQueueVolumes, NULL, NULL);
	sqlite3_exec(db, sqlDelete, NULL, NULL, NULL);
}

int ValveData::DatabaseValveVolumesCallback(void *data, int argc, char **argv, char **azColName)
{
	int stationIndex = 0, volumeIndex = 1;
	int station = atoi(argv[stationIndex]);
	int volume = atoi(argv[volumeIndex]);
	
	databaseValveVolumes.at(station) = volume;
	return 0;
}

void ValveData::PullDatabaseValveVolumes()
{
	sqlite3_exec(db, "SELECT station, volume FROM single WHERE station > -1", DatabaseValveVolumesCallback, NULL, NULL);
}

bool ValveData::IsStationValid(int station)
{
	bool nonZeroValueFound = false;

	for (int valveTime : volumeCommands[station])
	{
		if (valveTime != 0)
		{
			nonZeroValueFound = true;
		}
	}
	
	return nonZeroValueFound;
}