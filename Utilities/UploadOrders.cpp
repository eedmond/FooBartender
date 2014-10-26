#include <cstdlib>
#include <stdio.h>
#include <cstring>
#include <iostream>
#include <string>
#include <algorithm>
#include <vector>
#include <ctime>
#include <sqlite3.h>
#include <wiringSerial.h>

using namespace std;

vector<vector<int>> volCommands(8, vector<int>(16, 0));
vector<int> stationVolumes(16, 0);
int rc, fd;
static int uploadCount = 0;
sqlite3 *db;
char *zErrMsg = "Problem with function\n";
char sqlPostFix[110];

int randomize (int i) {return rand()%i;}

void WaitForInterrupts ();
void UploadOrders ();
void ReceiveInitialContacts(vector<int>& initialContacts);
void PullStationVolumes();
void CreateSelectString (vector<int>& initialContacts);
void PullOrdersFromQueue ();
void UpdateVolumes(int station);
void SendCmdsToArduino();
void RefreshDatabase();
void ClearVolCommands();
static int FormatOrders (void *data, int argc, char **argv, char **azColName);
static int SetInitialVolumes(void *data, int argc, char **argv, char **azColName);
int VolumeToTime(int volumeToPour, int currentVolume);

int main(int argc, char* argv[])
{
	srand (int (time(0)));
	// open connection to: usb-Arduino__www.arduino.cc__0043_95238343234351013290-if00//
   // open connection to the arduino
	fd = serialOpen("/dev/serial/by-id/usb-Arduino__www.arduino.cc__0043_95238343234351013290-if00", 115200);
   if(fd == -1)
	{
		fprintf(stderr, "Cannot establish connection to arduino\n");
		exit(0);
	}
	for ( int i = 0; i < 100000000; i++);

   // wait for startup sequence from the arduino
   while (1)
	{
		int incoming;
		//printf("waiting for start commands from arduino\n");
		while(serialDataAvail(fd) < 2);

		if (incoming = serialGetchar(fd) != '~') { /*printf("%i", incoming);*/ continue;}
		if (incoming = serialGetchar(fd) != '^') { /*printf("%i", incoming);*/ continue;}
		break;
	}
	//printf("received start commands from arduino\n");
	serialFlush(fd);

	//send startup sequence back to the arduino
	//printf("sending ~^\n");
	serialPuts(fd, "~^");

	WaitForInterrupts();

	serialClose(fd);
}

void WaitForInterrupts()
{
	int incomingChar = 0;

	while (1)
	{
		while (incomingChar != '~' || serialDataAvail(fd) == 0) // ascii 126
		{
			while(serialDataAvail(fd) < 2);
			incomingChar = serialGetchar(fd);
		}

		incomingChar = serialGetchar(fd);
	
		if (incomingChar == 'u') // u is 117 in ascii
			UploadOrders();
		else if (incomingChar == 's') // ascii 115
			break;
	}
	return;
}

void UploadOrders ()
{
	uploadCount = 0;
	// open connection to the database
   rc = sqlite3_open("/var/www/FB.db", &db);
   if( rc ){
      fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
      exit(0);
   }

	vector<int> initialContacts;

	//CloseQueue();
	ReceiveInitialContacts(initialContacts);

	PullStationVolumes();
	CreateSelectString(initialContacts);
	PullOrdersFromQueue();
	SendCmdsToArduino();
//printf("sending: ~f\n"); //TEMP
	serialPuts(fd, "~f");
	ClearVolCommands();
	RefreshDatabase();
	sqlite3_close(db);

	return;
}

/*void CloseQueue ()
{
	rc = sqlite3_exec(db, "UPDATE openQueue SET open=0", NULL, NULL, &zErrMsg);
    if( rc != SQLITE_OK )
    {
    	fprintf(stderr, "SQL error: problem closing queue\n");
    	sqlite3_free(zErrMsg);
 	}
}*/

void ReceiveInitialContacts(vector<int>& contacts)
{	
	//printf("Waiting for contact data from Arduino\n");
	while (serialDataAvail(fd) < 8);
	
	for (int i = 0; i < 8; ++i) {
		contacts.push_back(serialGetchar(fd));
	//printf("%i, ", contacts[i]);
	}
	//printf("\n");//TEMP//
		
	//printf("Contact information received\n");
}

void PullStationVolumes()
{
	rc = sqlite3_exec(db, "SELECT station, volume FROM single WHERE station > -1", SetInitialVolumes, NULL, &zErrMsg);
	if( rc != SQLITE_OK )
	{
		fprintf(stderr, "SQL error in FormatOrders\n", zErrMsg);
		sqlite3_free(zErrMsg);
	}

	for ( int i = 0; i < 16; ++i)
	{
		for ( int j = 0; j < 8; ++j)
			stationVolumes[i/2] += volCommands[j][i];
	}
}

void CreateSelectString (vector<int>& initialContacts)
{
	strcpy(sqlPostFix, " where 0");
	
	for (int i = 0; i < 8; i++)
	{
		if (initialContacts[i] == 1)
		{
			strcat(sqlPostFix, " OR station=");
			strcat(sqlPostFix, to_string(i).c_str());
		}
	}
	//printf("postfix cmd: %s\n", sqlPostFix);
}

void PullOrdersFromQueue()
{
	char sql[140];

	strcpy(sql, "SELECT * FROM queue");
	strcat(sql, sqlPostFix);
	//printf("running: %s\n", sql);
	rc = sqlite3_exec(db, sql, FormatOrders, NULL, &zErrMsg);
	if( rc != SQLITE_OK )
	{
		fprintf(stderr, "SQL error in FormatOrders\n", zErrMsg);
		sqlite3_free(zErrMsg);
	}
}

void SendCmdsToArduino()
{
	string cmd;
	vector<int> stationsToVisit;
	int timeToPour;
	
	for (int i = 0; i < 8; ++i)
		stationsToVisit.push_back(i);

	random_shuffle (stationsToVisit.begin(), stationsToVisit.end(), randomize);

	for (int station : stationsToVisit)
	{
		cmd = "~m";
		cmd += to_string(station);

		for (int i = 0; i < 16; i++)
		{
			timeToPour = VolumeToTime(volCommands[station][i], stationVolumes[i]);
			string num = to_string(timeToPour);
			while (num.size() < 3) num.insert(0, "0");
			cmd += num;
		}
		
		if (cmd.find_first_not_of("0", 3) == string::npos) continue;
		
		//printf("sending: %s\n", cmd.c_str());
		serialPuts(fd, cmd.c_str());

		UpdateVolumes(station);
	}
}

void UpdateVolumes(int station)
{
	int fractionPoured, checkCmd = 0;
	
	// Wait for ~c
	while (checkCmd != 'c')
	{
		while (checkCmd != '~')
		{
			while(serialDataAvail(fd) < 2);
			checkCmd = serialGetchar(fd);
			//printf("received %i\n", checkCmd);
		}
		checkCmd = serialGetchar(fd);
			//printf("received %i\n", checkCmd);
	}

	while(serialDataAvail(fd) < 8);
	
	for (int i = 0; i < 8; ++i)
	{
		fractionPoured = serialGetchar(fd);
		stationVolumes[(i+station)%8] -= volCommands[station][i]*fractionPoured;
	}
}

void RefreshDatabase()
{
	char sql[150];
	
	strcpy(sql, "DELETE FROM queue");
	strcat(sql, sqlPostFix);
	//printf("running: %s\n", sql);
	rc = sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
	if( rc != SQLITE_OK )
	{
		fprintf(stderr, "SQL error: %s\n", zErrMsg);
		sqlite3_free(zErrMsg);
	}

	strcpy(sql, "UPDATE stations SET amount = 0");
	strcat(sql, sqlPostFix);
	//printf("running: %s\n", sql);
	rc = sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
	if( rc != SQLITE_OK )
	{
		fprintf(stderr, "SQL error: %s\n", zErrMsg);
		sqlite3_free(zErrMsg);
	}
}

void ClearVolCommands()
{
	for (int i; i < 8; ++i)
	{
		for (int j = 0; j < 16; ++j)
			volCommands[i][j] = 0;
	}
}

static int SetInitialVolumes(void *data, int argc, char **argv, char **azColName)
{
	int stationIndex = 0, volumeIndex = 1;
	
	stationVolumes.at(atoi(argv[stationIndex])) = atoi(argv[volumeIndex]);
	return 0;
}

static int FormatOrders(void *data, int argc, char **argv, char **azColName)
{
	int i, j, idIndex, orderIndex, stationIndex, station, volumeToAdd;

	if (uploadCount >= 8) return 1;
	++uploadCount;

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
		volCommands.at(((i/2-station)%8)).at(i-16) = volumeToAdd;
                volume = strtok(NULL, "|");
	}

   return 0;
}

int VolumeToTime(int volumeToPour, int currentVolume)
{
	if (volumeToPour == 0) return 0;
	return 50;//(-.3*currentVolume+615)*volumeToPour/150;
}
