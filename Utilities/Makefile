SQL_FLAGS=-lsqlite3 -std=c++0x -O3

all: updateMixed order upload rate plan_party

updateMixed: UpdateMixed.cpp
	g++ $^ $(SQL_FLAGS) -o $@

order: PlaceOrder.cpp
	g++ $^ $(SQL_FLAGS) -o $@

upload: UploadOrders.cpp
	g++ $^ $(SQL_FLAGS) -lwiringPi -o $@

rate: RateDrink.cpp
	g++ $^ $(SQL_FLAGS) -lwiringPi -o $@
	
plan_party: PartyPlanner.cpp
	g++ $^ $(SQL_FLAGS) -o $@
