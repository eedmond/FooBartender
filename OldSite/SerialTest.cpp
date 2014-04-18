#include <cstdlib>
#include <stdio.h>
#include <sqlite3.h>

int main(int argc, char* argv[])
{
   sqlite3 *db;
   char *zErrMsg = 0;
   int rc;

	int a;
	for (a = 0; a < 250000000; ++a);

	if (argc == 2)
	{
		fprintf(stdout, "%s", argv[1]);
		return 0;
	}

   rc = sqlite3_open("/var/www/testdb.db", &db);

   if( rc ){
      fprintf(stdout, "Can't open database: %s\n", sqlite3_errmsg(db));
      exit(0);
   }else{
      fprintf(stdout, "Opened database successfully\n");
   }
   sqlite3_close(db);
}
