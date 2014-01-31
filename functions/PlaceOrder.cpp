#include <cstdlib>
#include <stdio.h>
#include <cstring>
#include <string>
#include <algorithm>
#include <vector>
#include <ctime>
#include <string>
#include <sqlite3.h>

bool foundLoc = false;
std::vector<int> stations;
std::string orderString = "000000000000000000000000000000000000000000000000";
int rc;
sqlite3 *db;
char *zErrMsg = "Problem with function\n";

int randomize (int i) {return std::rand()%i;}

static int CheckOnTable(void *data, int argc, char **argv, char **azColName)
{
	int station = atoi(argv[0]);
	int timeToPour, volumeToPour, currentVolume = atoi(argv[1]);
	char* durations = (char*) data;
	char sql[60];

	if (station == -1)
		return 1;

	orderString[station*3]   = durations[0];
	orderString[station*3+1] = durations[1];
	orderString[station*3+2] = durations[2];

	std::string num(orderString, station*3, 3);
	timeToPour = std::stoi(num, nullptr, 10);
	volumeToPour = timeToPour*15/3.9;

	// Not enough of ingredient on the table
	if (volumeToPour > currentVolume) return 1;

	strcpy(sql, "UPDATE single SET volume=volume-");
	strcat(sql, std::to_string(volumeToPour).c_str());
	strcat(sql, " WHERE station=");
	strcat(sql, argv[0]);

	rc = sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
        {
		fprintf(stderr, "SQL error: %s\n", zErrMsg);
		sqlite3_free(zErrMsg);
	}

	return 0;
}

static int ParseSingle(void *data, int argc, char **argv, char **azColName)
{
	unsigned int nameIndex, singleIndex, durationIndex;
	char *ingredient;
	int counter = 0;
	char sql[100];

	// Calculate the indices for each column (should never change, just in case)
	for (nameIndex=0; nameIndex < argc; ++nameIndex)
		if (!strcmp(azColName[nameIndex], "name")) break;
	for (singleIndex=0; singleIndex < argc; ++singleIndex)
		if (!strcmp(azColName[singleIndex], "ingredients")) break;
	for (durationIndex=0; durationIndex < argc; ++durationIndex)
		if (!strcmp(azColName[durationIndex], "duration")) break;

	ingredient = strtok(argv[singleIndex], "|");
	while (ingredient != NULL)
	{
		strcpy(sql, "SELECT station, volume FROM single WHERE name=\"");
		strcat(sql, ingredient);
		strcat(sql, "\"");
		rc = sqlite3_exec(db, sql, CheckOnTable, (void*)(argv[durationIndex]+3*counter), &zErrMsg);
        	if( rc != SQLITE_OK )
        	{
        	   printf("There ain't enough %s to go around. May I suggest going back and trying something else? :)\n", ingredient);
        	   sqlite3_free(zErrMsg);
		   return 1;
        	}

		ingredient = strtok(NULL, "|");
		++counter;
	}
}

static int callback(void *data, int argc, char **argv, char **azColName){
   int i;
   foundLoc = true;
   stations.push_back(atoi(argv[0]));

   return 0;
}

static int AddBackToSingle(void *data, int argc, char **argv, char **azColName){
   int volumeToAdd, i, orderIndex = 0;
   char num[5];
   char sql[70];

	for (i = 0; i < 16; i++)
	{
		strcpy(num, "");
		strncat(num, argv[orderIndex] + 3*i, 3);
		volumeToAdd = atoi(num)*15/3.9;

		strcpy(sql, "UPDATE single SET volume=volume+");
		strcat(sql, std::to_string(volumeToAdd).c_str());
		strcat(sql, " WHERE station=");
		strcat(sql, std::to_string(i).c_str());


		rc = sqlite3_exec(db, sql, NULL, data, &zErrMsg);
        	if( rc != SQLITE_OK )
        	{
        	   fprintf(stderr, "SQL error: %s\n", zErrMsg);
        	   sqlite3_free(zErrMsg);
        	}
	}

   return 0;
}

void ResetTable()
{
   int counter = 0;
   char* sql = "UPDATE stations SET amount=0 WHERE amount>0";
   const char* data = "Callback function called";

	rc = sqlite3_exec(db, sql, NULL, (void*)data, &zErrMsg);
        if( rc != SQLITE_OK )
        {
           fprintf(stderr, "SQL error: %s\n", zErrMsg);
           sqlite3_free(zErrMsg);
        }

	sql = "SELECT orderString FROM queue";
	rc = sqlite3_exec(db, sql, AddBackToSingle, NULL, &zErrMsg);
	sql = "DELETE FROM queue";

	if ( rc == SQLITE_OK)
		rc = sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
        {
           fprintf(stderr, "SQL error: %s\n", zErrMsg);
           sqlite3_free(zErrMsg);
        }
}

void GetStation ()
{
   char sql[50] = "SELECT station FROM stations WHERE amount=0";
   const char* data = "Callback function called";

   while (!foundLoc)
   {
	rc = sqlite3_exec(db, sql, callback, (void*)data, &zErrMsg);
        if( rc != SQLITE_OK )
        {
           fprintf(stderr, "SQL error: %s\n", zErrMsg);
           sqlite3_free(zErrMsg);
        }

	if (!foundLoc)
		for(int i = 0; i < 50000000; ++i);
   }

	std::random_shuffle (stations.begin(), stations.end(), randomize);

	strcpy(sql, "UPDATE stations SET amount=amount+1 WHERE station=");
	strcat(sql, std::to_string(stations.front()).c_str());

	printf("Placing order on %i\n", stations.front());

	rc = sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
        {
           fprintf(stderr, "SQL error: %s\n", zErrMsg);
           sqlite3_free(zErrMsg);
        }
}

bool PlaceOrder(char* mixed)
{
   char sql[60];

	strcpy(sql, "SELECT * FROM mixed WHERE name=\"");
	strcat(sql, mixed);
	strcat(sql, "\"");
	rc = sqlite3_exec(db, sql, ParseSingle, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
        {
           sqlite3_free(zErrMsg);
	   return false;
        }

	if (orderString.find_first_not_of("0") == std::string::npos)
		return false;

	return true;
}

void SendOrderToQueue()
{
	char sql[200];
	char *num;

	strcpy(sql, "INSERT INTO queue (orderString, station) values(\"");
	strcat(sql, orderString.c_str());
	strcat(sql, "\", ");
	strcat(sql, std::to_string(stations.front()).c_str());
	strcat(sql, ")");
	rc = sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
           sqlite3_free(zErrMsg);
}

int main(int argc, char* argv[])
{
	if (argc != 2) return 0;
   std::srand (int (std::time(0)));

   rc = sqlite3_open("/var/www/testdb.db", &db);
   if( rc ){
      fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
      exit(0);
   }

	if (!strcmp(argv[1], "clear"))
		ResetTable();
	else
	{
		if (PlaceOrder(argv[1]))
		{
			GetStation();
			SendOrderToQueue();
		}
		else
			return 1;
	}
	sqlite3_close(db);
	system("/var/www/functions/updateMixed");
}
