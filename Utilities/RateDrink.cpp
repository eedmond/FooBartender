#include <sqlite3.h>
#include <cstdlib>
#include <stdio.h>
#include <cstring>
using namespace std;

int main(int argc, char** argv)
{
	sqlite3 *db;
	int rc;
	char *zErrMsg = "Problem with function\n";
	char sql[100];
	// open the database
	rc = sqlite3_open("/var/www/FB.db", &db);
	if( rc ){
      fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
      exit(0);
    }
	
	strcpy(sql, "UPDATE mixed SET numRatings=numRatings+1, rating=rating+");
	strcat(sql, argv[2]);
	strcat(sql, " WHERE name=\"");
	strcat(sql, argv[1]);
	strcat(sql, "\"");
	
    sqlite3_exec(db, sql, NULL, NULL, &zErrMsg);
	sqlite3_close(db);
	return 0;
}
