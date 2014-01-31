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
int counter = 0;
unordered_map<char*, int> nameIndices;

static int CheckOnTable(void *data, int argc, char **argv, char **azColName)
{
        int curVolume = atoi(argv[1]);
        int volumeToPour = 0;
	int timeToPour = 0;
	char *durations = (char*)data;

	string time = "000";
	time[0] = durations[nameIndices[argv[0]]*3];
	time[1] = durations[nameIndices[argv[0]]*3+1];
	time[2] = durations[nameIndices[argv[0]]*3+2];
	timeToPour = atoi(time.c_str());
        volumeToPour = timeToPour*15/3.9;

	printf("We want %i, we have %i for %s\n", volumeToPour, curVolume, argv[0]);

        counter += 3;
        if (curVolume < volumeToPour)
        	return 1;
	return 0;
}


static int ParseSingle(void *data, int argc, char **argv, char **azColName)
{
        unsigned int nameIndex, singleIndex, durationIndex;
        char *ingredient;
        char sql[100];
        char sql2[100];
        bool onTable = false;
	int counter = 0;

        // Calculate the indices for each column (should never change, just in case)
        for (nameIndex=0; nameIndex < argc; ++nameIndex)
                if (!strcmp(azColName[nameIndex], "name")) break;
        for (singleIndex=0; singleIndex < argc; ++singleIndex)
                if (!strcmp(azColName[singleIndex], "ingredients")) break;
        for (durationIndex=0; durationIndex < argc; ++durationIndex)
                if (!strcmp(azColName[durationIndex], "duration")) break;

        ingredient = strtok(argv[singleIndex], "|");
        strcpy(sql, "SELECT name, volume FROM single WHERE name=\"");

	// create sql command of all ingredients and
	// generate hash of ingredients to indices
        while (ingredient != NULL)
        {
		nameIndices[ingredient] = counter;
		if (counter != 0)
 			strcat(sql, " OR name=\"");
                strcat(sql, ingredient);
                strcat(sql, "\"");
                ingredient = strtok(NULL, "|");
		++counter;
        }

        rc = sqlite3_exec(db, sql, CheckOnTable, (void*)(argv[durationIndex]), &zErrMsg);
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
	printf("Update Successful");
	return 0;
}
