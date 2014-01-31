#include <cstdlib>
#include <stdio.h>
#include <cstring>
#include <string>
#include <algorithm>
#include <vector>
#include <sqlite3.h>

std::vector<std::string> commands;
int rc, uploadCount=0;
sqlite3 *db;
char *zErrMsg = "Problem with function\n";

static int FormatOrders(void *data, int argc, char **argv, char **azColName){
	int i, j;
	int idIndex;
	int orderIndex;
	int stationIndex;
	int station;
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
	
	for (i=8; i<16; ++i)
	{
		for (j = 0; j < 6; ++j)
			commands.at((i - station)%8)[6*(i-8) + j] = argv[orderIndex][6*(i-8) + j];
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

	for (rc = 0; rc < 8; ++rc)
	{
		commands.push_back("000000000000000000000000000000000000000000000000");
	}

   if (argc == 1) UploadOrders();

	for (rc = 0; rc < 8; ++rc)
	{
		printf("%s\n", commands.at(rc).c_str());
	}

   sqlite3_close(db);
}
