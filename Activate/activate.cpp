#include <sys/types.h>
#include <unistd.h>
#include <fstream>
#include <cstdio>
#include <string>
#include <cstring>
#include <iostream>

using namespace std;

int main()
{

	char fileName[] = "/var/www/Activate/pid_store";
	fstream file(fileName);
	file.open(fileName, fstream::in | fstream::out | fstream::trunc);

	cout << "Starting..." << endl;
	
	//If file exists
	if (file)
	{
		cout << "File exists! Looking at process id." << endl;
		//Grab the pid
		pid_t pastPid;
		file >> pastPid;
		
		cout << "Killing: " << pastPid << endl;
		
		//Kill the process with pid of pastPid
		string killString = "kill " + to_string(pastPid);
		
		system(killString.c_str());
		
		//Delete the file (i.e. clear it)
		file.close();
		remove(fileName);
		file.open(fileName, fstream::in | fstream::out | fstream::trunc);
	}
	
	pid_t pid = fork();
	
	if (pid == -1)
	{
		//fork failed... do something smart here
		cerr << "Fork failed :(" << endl;
		exit(-1);
	}
	
	if (pid)
	{
		//parent process
		cout << "Parent\n" << pid << endl;
		
		//write the current process to the file
		file << pid << flush;
		file.close();
	}
	else
	{
		file.close();
		//child process
		cout << "Child\n";
		
		//Start up SerialComm
		char* args[2];
		args[0] = new char[100];
		
		strcpy(args[0],"/var/www/Utilities/Serial/SerialComm");
		
		args[1] = nullptr;
		
		//Suppress output to the screen
		freopen("/var/www/Activate/comm_log", "w", stdout);
		freopen("/var/www/Activate/comm_log", "w", stderr);
		
		int error = execv(args[0], args);
		//exec does not return unless it errors
		cout << "Child error!!\t" << error << endl;
		cout << errno << endl;
	}
}