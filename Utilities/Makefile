SQL_FLAGS=-lsqlite3 -std=c++0x -O3

all: plan_party
	
plan_party: PartyPlanner.cpp
	g++ $^ $(SQL_FLAGS) -o $@
