#include "WaitForStartButtonState.h"

void WaitForStartButtonState::Respond()
{
	char initialContacts = GetInitialContacts();
	PullOrdersFromQueue(initialContacts);
	PullStationVolumes();
	InitializeStationsToVisit();

	MoveAndPourState::Respond();
}

char WaitForStartButtonState::GetInitialContacts()
{
	return response->payload[0];
}

void WaitForStartButtonState::PullOrdersFromQueue(char initialContacts)
{
	char sql[180];

	strcpy(sql, "SELECT * FROM queue WHERE 0");
	
	for (char i = 0; i < 8; ++i)
	{
		char contactHigh = initialContacts & i;
		
		if (contactHigh == 1)
		{
			strcpy(sql, " OR WHERE station=");
			strcpy(sql, to_string(i).c_str());
		}
	}

	sqlite3_exec(db, sql, MoveAndPourState::FormatOrders, NULL, NULL);
}

void WaitForStartButtonState::PullStationVolumes()
{
	sqlite3_exec(db, "SELECT station, volume FROM single WHERE station > -1",
		MoveAndPourState::SetInitialVolumes, NULL, NULL);
	AddQueueVolumesToStations();
}