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
#include <unistd.h>

bool foundLoc = false;
std::vector<int> stations;
std::vector<int> orderString(16, 0);
std::vector<std::string> subtractVolsCmd;
std::unordered_map<std::string, int> drinkVolumes;
int rc;
sqlite3 *db;
//TEMP//
char drinkName[100];
int drinkRatio = 1; // 2 if half, 4.5 if taste

int randomize (int i) {return std::rand()%i;}

static int CheckOnTable(void *data, int argc, char **argv, char **azColName)
{
	char* drinkName = argv[0];
	int timeToPour, currentVolume = atoi(argv[2]), station = atoi(argv[1]);
	int volumeToPour = drinkVolumes[drinkName] / drinkRatio;
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
	subtractVolsCmd.push_back(std::string(sql));

	return 0;
}

static int ParseSingle(void *data, int argc, char **argv, char **azColName)
{
	// This is consistent with sql command in PlaceOrder
	unsigned int nameIndex = 0, singleIndex = 1, volumeIndex = 2;
	std::vector<char*> ingredients;
	char *singleIng, *volume;
	int counter = 0;
	char sql[500];
	subtractVolsCmd.clear(); // unnecessary, just in case

    singleIng = strtok(argv[singleIndex], "|");
	while (singleIng != NULL)
	{
		ingredients.push_back(singleIng);
		drinkVolumes[singleIng] = 0;	// Initialize hash to 0
		singleIng = strtok(NULL, "|");
	}

	volume = strtok(argv[volumeIndex], "|");
	
	// DO NOT change order of name, station, volume
	strcpy(sql, "SELECT name, station, volume FROM single WHERE name=\"");

	// create sql command of all ingredients and
	// generate hash of ingredients to indices
	for (char *ingredient : ingredients)
	{
		drinkVolumes[ingredient] += atoi(volume); // this is safe since hash is initialized

		if (counter != 0)
			strcat(sql, " OR name=\"");
		strcat(sql, ingredient);
		strcat(sql, "\"");
		volume = strtok(NULL, "|");
		++counter;
	}
	rc = sqlite3_exec(db, sql, CheckOnTable, NULL, NULL);
	if( rc != SQLITE_OK ) // Not enough quantity
		return 1;

strcpy(drinkName, argv[nameIndex]);
						//// POSSIBILITY ////
// Replace all '-' with '+' to use this vector to undo the order before a station is found //
	// foreach ingredient in the drink, subtract it's volume from the database
	for (std::string sqlCmd : subtractVolsCmd)
		sqlite3_exec(db, sqlCmd.c_str(), NULL, NULL, NULL);

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
        volume = strtok(NULL, "|");

		if (volumeToAdd == 0)
			continue;

		strcpy(sql, "UPDATE single SET volume=volume+");
		strcat(sql, std::to_string(volumeToAdd).c_str());
		strcat(sql, " WHERE station=");
		strcat(sql, std::to_string(i).c_str());

		//printf("adding %s\n", sql);

		rc = sqlite3_exec(db, sql, NULL, NULL, NULL);
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

	rc = sqlite3_exec(db, sql, callback, (void*)data, NULL);

	while (!foundLoc)
	{
		rc = sqlite3_exec(db, sql, callback, (void*)data, NULL);
		usleep(5000000);
		printf("Queue appears to be full. Please wait.\n");
	}
	
	std::random_shuffle (stations.begin(), stations.end(), randomize);

	strcpy(sql, "UPDATE stations SET amount=amount+1 WHERE station=");
	strcat(sql, std::to_string(stations.front()).c_str());

	printf("Placing your order on station %i\n", stations.front());
	//OLD VERSION//printf("Placing your %s on station %i\n", drinkName, stations.front());

	rc = sqlite3_exec(db, sql, NULL, NULL, NULL);
}

static int RandomShot(void *data, int argc, char **argv, char **azColName)
{
	char* shotName = (char*) data;
	strcpy(shotName, argv[0]);
}

void GetSurpriseShot(char** customOrder)
{
	sqlite3_exec(db,
		"SELECT name FROM single WHERE station>-1 AND volume>=35 ORDER BY Random() LIMIT 1",
		RandomShot, customOrder[0], NULL);
}

bool PlaceOrder(char* orderCmd, char** customOrder, char* drinkAmount)
{
	char sql[150];

	// update drink ratio based on what amount the user wanted
	if (!strcmp(drinkAmount, "half")) {
		drinkRatio = 2;
	}
	else if (!strcmp(drinkAmount, "taste")) {
		drinkRatio = 4.5;
	}

	if (customOrder == NULL)
	{
		// DO NOT change the order of these, they are hardcoded indices in the callback
		if (strstr(orderCmd, "Eric's Jamaican Surprise") != NULL)
		{
			strcpy(sql, "SELECT name, ingredients, volume FROM mixed WHERE isOnTable>0 AND proof=");
			if (strstr(orderCmd, "Mixed Drink") != NULL)
				strcat(sql, "1");
			else
				strcat(sql, "0");
			strcat(sql, " ORDER BY RANDOM() LIMIT 1");
		}
		else
		{
			strcpy(sql, "SELECT name, ingredients, volume FROM mixed WHERE name=\"");
			strcat(sql, orderCmd);
			strcat(sql, "\"");
		}
		rc = sqlite3_exec(db, sql, ParseSingle, NULL, NULL);
		if( rc != 0 )
		{
			printf("Your order could not be processed, please try again\n");
			return false;
		}
	}
	// Custom Drink
	else
	{
		if (strstr(customOrder[1], "Eric's Jamaican Surprise") != NULL)
			GetSurpriseShot(&customOrder[1]);
			
		rc = ParseSingle(NULL, 0, customOrder, NULL);
		if( rc != 0 )
		{
			printf("Your order could not be processed, please try again\n");
			return false;
		}
	}

	for (int vol : orderString)
		if (vol != 0) return true;

	printf("Your order could not be processed, please try again\n");
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
	if (argc == 0) return 0;
   std::srand (int (std::time(0)));

   rc = sqlite3_open("/var/www/FB.db", &db);
   while ( rc ){
   	printf("Can't open database: %s, attempting to reconnect\n", sqlite3_errmsg(db));
	usleep(5000);
   	rc = sqlite3_open("/var/www/FB.db", &db);
   }

	if (!strcmp(argv[1], "clear"))
	{
		ResetTable();
		system("/var/www/functions/updateMixed");
	}
	else
	{
		// NULL indicates non-custom order
		char** customOrder = NULL;
		if (!strcmp(argv[1], "custom"))
			customOrder = &argv[1];

		if (PlaceOrder(argv[1], customOrder, argv[2]))
		{
			system("/var/www/functions/updateMixed checkOn");
			GetStation();
			if (foundLoc)
				SendOrderToQueue();
		}
	}
	sqlite3_close(db);
	return 0;
}
