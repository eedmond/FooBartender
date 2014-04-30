#include <sqlite3.h>
#include <vector>
#include <string>
#include <cstdlib>
#include <stdio.h>
#include <unordered_map>
#include <cstring>
using namespace std;

sqlite3 *db;
bool foundLoc = false;
int rc;
char *zErrMsg = "Problem with function\n";
vector<string> mixedDrinks;
unordered_map<string, int> drinkVolumes;

static int CheckOnTable(void *data, int argc, char **argv, char **azColName)
{
	char* drinkName = argv[0];
        int curVolume = atoi(argv[1]);
        int volumeToPour = drinkVolumes[drinkName];

	//printf("We want %i, we have %i for %s\n", volumeToPour, curVolume, argv[0]);

        if (curVolume < volumeToPour)
		{
        	return 1;
		}
	return 0;
}


static int ParseSingle(void *data, int argc, char **argv, char **azColName)
{
    unsigned int nameIndex=0, singleIndex=1, volumeIndex=2, onTableIndex = 3;
    vector<char*> ingredients;
	char *singleIng, *volume;
    char sql[100];
    char sql2[100];
    bool onTable = false;
	int counter = 0;

    singleIng = strtok(argv[singleIndex], "|");
	while (singleIng != NULL)
	{
		ingredients.push_back(singleIng);
		singleIng = strtok(NULL, "|");
	}

	//printf("For %s: ", argv[nameIndex]);
        volume = strtok(argv[volumeIndex], "|");
        strcpy(sql, "SELECT name, volume FROM single WHERE name=\"");

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

		if (ingredients.size() > 0)
			rc = sqlite3_exec(db, sql, CheckOnTable, NULL, &zErrMsg);
		else
			rc = SQLITE_OK;
			
		//printf("value=%i\n", (!strcmp(argv[onTableIndex] , "0")));
			
        if( rc != SQLITE_OK && !strcmp(argv[onTableIndex] , "1"))
        {
            sqlite3_free(zErrMsg);
            // update table
            strcpy(sql2, "UPDATE mixed SET isOnTable=0 WHERE name=\"");
            strcat(sql2, argv[nameIndex]);
			strcat(sql2, "\";");
		//("%s\n", sql2);
            sqlite3_exec(db, sql2, NULL, NULL, &zErrMsg);
        }
		else if ((bool) data && rc == SQLITE_OK && !strcmp(argv[onTableIndex] , "0"))
		{
        	// update table
            strcpy(sql2, "UPDATE mixed SET isOnTable=1 WHERE name=\"");
            strcat(sql2, argv[nameIndex]);
			strcat(sql2, "\";");
		//printf("%s\n", sql2);
            sqlite3_exec(db, sql2, NULL, NULL, &zErrMsg);
        }

        return 0; // is on the table
}


int GenMixed(bool checkAll = true)
{
	char sql[100];
	strcpy(sql, "SELECT name, ingredients, volume, isOnTable FROM mixed");
	if (!checkAll)
		strcat(sql, " WHERE isOnTable>0");

	rc = sqlite3_exec(db, sql, ParseSingle, &checkAll, &zErrMsg);
}


int main(int argc, char** argv)
{
	// open the database
	rc = sqlite3_open("/var/www/FB.db", &db);
	if( rc ){
      fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
      exit(0);
    }
	GenMixed(!(argc == 2 && !strcmp(argv[1], "checkOn")));
	sqlite3_close(db);
	return 0;
}
