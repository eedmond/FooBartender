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

	printf("We want %i, we have %i for %s\n", volumeToPour, curVolume, argv[0]);

        if (curVolume < volumeToPour)
        	return 1;
	return 0;
}


static int ParseSingle(void *data, int argc, char **argv, char **azColName)
{
        unsigned int nameIndex, singleIndex, volumeIndex;
        vector<char*> ingredients;
	char *singleIng, *volume;
        char sql[100];
        char sql2[100];
        bool onTable = false;
	int counter = 0;

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

        rc = sqlite3_exec(db, sql, CheckOnTable, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
        {
            sqlite3_free(zErrMsg);
            // update table
            strcpy(sql2, "UPDATE mixed SET isOnTable=0 WHERE name=\"");
            strcat(sql2, argv[nameIndex]);
		strcat(sql2, "\";");
		printf("%s\n", sql2);
            sqlite3_exec(db, sql2, NULL, NULL, &zErrMsg);
        } else {
        	// update table
            strcpy(sql2, "UPDATE mixed SET isOnTable=1 WHERE name=\"");
            strcat(sql2, argv[nameIndex]);
		strcat(sql2, "\";");
		printf("%s\n", sql2);
            sqlite3_exec(db, sql2, NULL, NULL, &zErrMsg);
        }

        return 0; // is on the table
}


int genMixed()
{
	char sql[100];
	strcpy(sql, "SELECT * FROM mixed");
	rc = sqlite3_exec(db, sql, ParseSingle, NULL, &zErrMsg);
}


int main()
{
	// open the database
	rc = sqlite3_open("/var/www/testdb.db", &db);
	if( rc ){
      fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
      exit(0);
    }
	genMixed();
	sqlite3_close(db);
	printf("Update Successful\n");
	return 0;
}
