#include <cstdlib>
#include <stdio.h>
#include <cstring>
#include <string>
#include <algorithm>
#include <vector>
#include <ctime>
#include <string>
#include <unordered_map>
#include <sqlite3.h>

bool foundLoc = false;
std::vector<int> stations;
std::vector<int> orderString(16, 0);
std::unordered_map<std::string, int> drinkVolumes;
int rc;
sqlite3 *db;

int randomize (int i) {return std::rand()%i;}

static int CheckOnTable(void *data, int argc, char **argv, char **azColName)
{
	char* drinkName = argv[0];
	int timeToPour, currentVolume = atoi(argv[2]), station = atoi(argv[1]);
	int volumeToPour = drinkVolumes[drinkName];
	char sql[60];

	if (station == -1)
		return 1;

	orderString[station] = volumeToPour;

	// Not enough of ingredient on the table
	if (volumeToPour > currentVolume) return 1;

	strcpy(sql, "UPDATE single SET volume=volume-");
	strcat(sql, std::to_string(volumeToPour).c_str());
	strcat(sql, " WHERE station=");
	strcat(sql, argv[1]);
	rc = sqlite3_exec(db, sql, NULL, NULL, NULL);

	return 0;
}

static int ParseSingle(void *data, int argc, char **argv, char **azColName)
{
	unsigned int nameIndex, singleIndex, volumeIndex;
	std::vector<char*> ingredients;
	char *singleIng, *volume;
	int counter = 0;
	char sql[500];

	// Calculate the indices for each column (should never change, just in case)
	for (nameIndex=0; nameIndex < argc; ++nameIndex)
		if (!strcmp(azColName[nameIndex], "name")) break;
	for (singleIndex=0; singleIndex < argc; ++singleIndex)
		if (!strcmp(azColName[singleIndex], "ingredients")) break;
	for (volumeIndex=0; volumeIndex < argc; ++volumeIndex)
		if (!strcmp(azColName[volumeIndex], "volume")) break;

        singleIng = strtok(argv[singleIndex], "|");
	while (singleIng != NULL)
	{
		ingredients.push_back(singleIng);
		singleIng = strtok(NULL, "|");
	}

	volume = strtok(argv[volumeIndex], "|");
	strcpy(sql, "SELECT name, station, volume FROM single WHERE name=\"");

	// create sql command of all ingredients and
	// generate hash of ingredients to indices
        for (char *ingredient : ingredients)
        {
		drinkVolumes[ingredient] = atoi(volume);
		if (counter != 0)
 			strcat(sql, " OR name=\"");
                strcat(sql, ingredient);
                strcat(sql, "\"");
                volume = strtok(NULL, "|");
		++counter;
        }
	rc = sqlite3_exec(db, sql, CheckOnTable, NULL, NULL);
        if( rc != SQLITE_OK )
        {
           printf("Sorry, I wasn't able to process your request, please order a different drink\n");
	   return 1;
        }
	return 0;
}

static int callback(void *data, int argc, char **argv, char **azColName){
   int i;
   foundLoc = true;
   stations.push_back(atoi(argv[0]));

   return 0;
}

static int AddBackToSingle(void *data, int argc, char **argv, char **azColName){
   int volumeToAdd, i;
   char sql[70];

	char* volume = strtok(argv[0], "|");

	for (i = 0; i < 16; i++)
	{
		volumeToAdd = atoi(volume);

		strcpy(sql, "UPDATE single SET volume=volume+");
		strcat(sql, std::to_string(volumeToAdd).c_str());
		strcat(sql, " WHERE station=");
		strcat(sql, std::to_string(i).c_str());

		printf("adding %i to station %i\n", volumeToAdd, i);

		rc = sqlite3_exec(db, sql, NULL, data, NULL);
                volume = strtok(NULL, "|");
	}

   return 0;
}

void ResetTable()
{
   int counter = 0;
   char* sql = "UPDATE stations SET amount=0 WHERE amount>0";
   const char* data = "Callback function called";

	rc = sqlite3_exec(db, sql, NULL, (void*)data, NULL);

	sql = "SELECT orderString FROM queue";
	rc = sqlite3_exec(db, sql, AddBackToSingle, NULL, NULL);
	sql = "DELETE FROM queue";

	if ( rc == SQLITE_OK)
		rc = sqlite3_exec(db, sql, NULL, NULL, NULL);
}

void GetStation ()
{
   char sql[50] = "SELECT station FROM stations WHERE amount=0";
   const char* data = "Callback function called";

   while (!foundLoc)
   {
	rc = sqlite3_exec(db, sql, callback, (void*)data, NULL);

	if (!foundLoc)
		for(int i = 0; i < 50000000; ++i);
   }

	std::random_shuffle (stations.begin(), stations.end(), randomize);

	strcpy(sql, "UPDATE stations SET amount=amount+1 WHERE station=");
	strcat(sql, std::to_string(stations.front()).c_str());

	printf("Placing order on %i\n", stations.front());

	rc = sqlite3_exec(db, sql, NULL, NULL, NULL);
}

bool PlaceOrder(char* mixed)
{
	char sql[150];

	strcpy(sql, "SELECT * FROM mixed WHERE name=\"");
	strcat(sql, mixed);
	strcat(sql, "\"");
	rc = sqlite3_exec(db, sql, ParseSingle, NULL, NULL);
        if( rc != SQLITE_OK )
	   return false;

	for (int vol : orderString)
		if (vol != 0) return true;

	printf("Sorry, I wasn't able to process your request, please order a different drink\n");
	return false;
}

void SendOrderToQueue()
{
	char sql[200];
	char *num;

	strcpy(sql, "INSERT INTO queue (orderString, station) values(\"");
	for (int vol : orderString)
	{
		strcat(sql, std::to_string(vol).c_str());
		strcat(sql, "|");
	}
	strcat(sql, "\", ");
	strcat(sql, std::to_string(stations.front()).c_str());
	strcat(sql, ")");
	rc = sqlite3_exec(db, sql, NULL, NULL, NULL);
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
