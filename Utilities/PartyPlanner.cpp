#include <sqlite3.h>
#include <vector>
#include <string>
#include <cstdlib>
#include <stdio.h>
#include <unordered_map>
#include <cstring>
#include <set>
#include <utility>
using namespace std;

/* EXPLANATION OF VARIABLES *//*
----- Variable Name ----	----- Contents -------	-> -> -> Desc -> -> -> ->		---- Description ----
ingredient_list			= {"Rum", "Vodka", "Coke", ...}								order from database
ordered_ingredient_list	= {"Vodka", "Rum", ... }									Ordered by frequency used
ingredient_data			= {"Rum" -> ["Rum and Coke", "Long Island Iced Tea", ...]}	Hash of ingredients to drinks appearing in
mixed_drink_data		= {"Rum and Coke" -> (["Rum", Coke"], 0)}					Hash of mixedDrinks to contents as well as
																					the current number of components on the table
*/

unordered_map<string, int> ingredient_count;

class Ingredient_Comparer
{
	public:
		bool operator() (const string& a, const string& b)
		{
			return ingredient_count[a] > ingredient_count[b];
		}
};

sqlite3 *db;
int rc, optimal_count, current_count, TABLE_SIZE = 12;
char *zErrMsg = "Problem with function\n";
vector<string> ingredient_list;
multiset<string, Ingredient_Comparer>::iterator endpoint;
vector<multiset<string, Ingredient_Comparer>::iterator> current_arrangement, optimal_arrangement;
multiset<string, Ingredient_Comparer> ordered_ingredient_list;
unordered_map<string, vector<string> > ingredient_data;
unordered_map<string, pair<vector<string>, int> > mixed_drink_data;

static int GetIngredientNames(void *data, int argc, char **argv, char **azColName);
static int ParseMixedDrinks(void *data, int argc, char **argv, char **azColName);
void GenMixed();
void GetIngredients();
void OrderIngredients();
void OutputInfo();
void RemoveDrink(const multiset<string, Ingredient_Comparer>::iterator& it);
void AddDrink(multiset<string, Ingredient_Comparer>::iterator& it);
void FoundOptimal();
void SetEndpoint();
void InitializeTableSize();
void PrintArrangement();
bool Promising(vector<multiset<string, Ingredient_Comparer>::iterator>::iterator it);
void CalculateSolution();

int main(int argc, char** argv)
{
	// open the database
	rc = sqlite3_open("/var/www/FB.db", &db);
	if( rc ){
      fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
      exit(0);
    }
	
	if (argc > 1)
		TABLE_SIZE = atoi(argv[1]);
	
	GetIngredients();
	GenMixed();
	OrderIngredients();
	//OutputInfo();
	InitializeTableSize();
	CalculateSolution();
	sqlite3_close(db);
	return 0;
}

static int GetIngredientNames(void *data, int argc, char **argv, char **azColName)
{
	string ingred_str = argv[0];
	ingredient_count[ingred_str] = 0;
	ingredient_list.push_back(ingred_str);
	//PrintIngredients();
	return 0;
}

static int ParseMixedDrinks(void *data, int argc, char **argv, char **azColName)
{
    unsigned int nameIndex=0, singleIndex=1;
    vector<string> ingredients;
	char* singleIng;
	string drinkName = argv[nameIndex];

    singleIng = strtok(argv[singleIndex], "|");
	while (singleIng != NULL)
	{
		string singleIng_str = singleIng;
		ingredients.push_back(singleIng_str);
		ingredient_data[singleIng_str].push_back(drinkName);
		++ingredient_count[singleIng_str];
		singleIng = strtok(NULL, "|");
	}

	mixed_drink_data[drinkName].first = ingredients;
	mixed_drink_data[drinkName].second = 0;

	return 0;
}

void GenMixed()
{
	char sql[100];
	strcpy(sql, "SELECT name, ingredients FROM mixed");

	rc = sqlite3_exec(db, sql, ParseMixedDrinks, NULL, &zErrMsg);
}

void GetIngredients()
{
	char sql[100];
	strcpy(sql, "SELECT name FROM single");

	rc = sqlite3_exec(db, sql, GetIngredientNames, NULL, &zErrMsg);
}

void OrderIngredients()
{
	for (vector<string>::iterator it = ingredient_list.begin(); it != ingredient_list.end(); ++it)
	{
		if (ingredient_data[*it].size() > 0)
		{
			ordered_ingredient_list.insert(*it);
			//printf("inserting %s into ordered_list\n", (*it).c_str());
		}
	}
}

void OutputInfo()
{
	for (multiset<string, Ingredient_Comparer>::iterator it = ordered_ingredient_list.begin(); it != ordered_ingredient_list.end(); ++it)
	{
		printf("%s: %i\n", (*it).c_str(), ingredient_count[*it]);
	}
}

// Subtracts from current count if this was the last ingredient necessary
// to make any of it's mixeddrinks that it's contained in
void RemoveDrink(const multiset<string, Ingredient_Comparer>::iterator& it)
{
	for (string drinkName : ingredient_data[*it])
	{
		if (mixed_drink_data[drinkName].first.size() == mixed_drink_data[drinkName].second--)
			--current_count;
	}
}

// Adds to current count if this was the last ingredient necessary
// to make any of it's mixeddrinks that it's contained in
void AddDrink(multiset<string, Ingredient_Comparer>::iterator& it)
{
	for (string drinkName : ingredient_data[*it])
	{
		if (mixed_drink_data[drinkName].first.size() == ++mixed_drink_data[drinkName].second)
			++current_count;
	}
}

void FoundOptimal()
{
	optimal_count = current_count;
	optimal_arrangement = current_arrangement;
	printf("New optimal solution of size: %i\nContains: ", current_count);
	for (multiset<string, Ingredient_Comparer>::iterator it : current_arrangement)
	{
		printf("%s, ", (*it).c_str());
	}
	printf("\n");
}

void SetEndpoint()
{
	endpoint = --ordered_ingredient_list.end();
	for (int i = 1; i < TABLE_SIZE; ++i)
		--endpoint;
}

void InitializeTableSize()
{
	multiset<string, Ingredient_Comparer>::iterator it = ordered_ingredient_list.begin();
	for (int i=0; i < TABLE_SIZE; ++i, ++it)
	{
		current_arrangement.push_back(it);
		AddDrink(it);
	}
}

void PrintArrangement()
{
	for (vector<multiset<string, Ingredient_Comparer>::iterator>::iterator iter = current_arrangement.begin(); iter != current_arrangement.end(); ++iter)
		printf("%s, ", (**iter).c_str());

	printf("\n");
}

bool Promising(vector<multiset<string, Ingredient_Comparer>::iterator>::iterator it)
{
	int promising_count = current_count;
	for (; it != current_arrangement.end(); ++it)
		promising_count += ingredient_data[**it].size();
		
	return promising_count >= optimal_count;
}

void CalculateSolution()
{
	SetEndpoint();
	FoundOptimal();
	while (*current_arrangement.begin() != endpoint)
	{
		vector<multiset<string, Ingredient_Comparer>::iterator>::iterator iter = current_arrangement.end();
		multiset<string, Ingredient_Comparer>::iterator curEndpoint = --ordered_ingredient_list.end();
		do
		{
			if (iter == current_arrangement.begin())
				return;
			--iter;
			RemoveDrink((*iter)++);
		} while (*iter == curEndpoint-- || (++curEndpoint, !Promising(iter)));
		
		curEndpoint = *iter;
		for (; iter != current_arrangement.end(); ++iter)
		{
			*iter = curEndpoint++;
			AddDrink(*iter);
		}
		if (current_count >= optimal_count)
			FoundOptimal();
	}
}
