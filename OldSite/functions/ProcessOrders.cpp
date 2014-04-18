#include <cstdlib>
#include <stdio.h>
#include <cstring>
#include <string>
#include <algorithm>
#include <vector>
#include <sqlite3.h>

std::vector<std::vector<int>> commands(8, std::vector<int>(16, 0));
int rc, uploadCount=0;
sqlite3 *db;
char *zErrMsg = "Problem with function\n";

static int FormatOrders(void *data, int argc, char **argv, char **azColName){
	int i, j, idIndex, orderIndex, stationIndex, station, volumeToAdd;
	char sql[60];

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
		commands.at(((i/2-station)%8)).at(i-16) = volumeToAdd;
                volume = strtok(NULL, "|");
	}

	strcpy(sql, "DELETE FROM queue WHERE id=");
	strcat(sql, argv[idIndex]);
	rc = sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
        {
           fprintf(stderr, "SQL error: %s\n", zErrMsg);
           sqlite3_free(zErrMsg);
        }

	strcpy(sql, "UPDATE stations SET amount=amount-1 WHERE station=");
	strcat(sql, argv[stationIndex]);
	rc = sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
        {
           fprintf(stderr, "SQL error: %s\n", zErrMsg);
           sqlite3_free(zErrMsg);
        }

   return 0;
}

void UploadOrders ()
{
   int counter = 0;
   char sql[60];
   char *num;

	strcpy(sql, "SELECT * FROM queue");
	rc = sqlite3_exec(db, sql, FormatOrders, NULL, &zErrMsg);
        if( rc != SQLITE_OK )
        {
           fprintf(stderr, "SQL error: %s\n", zErrMsg);
           sqlite3_free(zErrMsg);
        }
}

int main(int argc, char* argv[])
{
   rc = sqlite3_open("/var/www/testdb.db", &db);
   if( rc ){
      fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
      exit(0);
   }

   if (argc == 1) UploadOrders();

	for (rc = 0; rc < 8; ++rc)
	{
		for (int i = 0; i < 16; ++i)
			printf("%i, ", commands.at(rc).at(i));
		printf("\n");
	}

   sqlite3_close(db);
}
