// Author: Xiaonan Ji, Date: 12/8/2012
// The the main function which processes articles and constructs article network
#include "Parser.h"
#include "Stemmer.h"
#include "Functions.h"
#include <iostream>
#include <vector>
#include <map>
#include <algorithm>
#include <fstream>
#include <string>
#include <cctype>
#include <cstring>
#include <sys/time.h>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <cmath>

using namespace std;

int main(int argc, char* argv[]){

// initial parameters
	if (argc < 3) {
        printf("please enter enough parameters.");
        exit(-1);
    }

	char path[20];
    strcpy (path, argv[1]);
	string pathstr = path;
	int number = atoi(argv[2]);

// process, construct article network (simi matrix) and relevance vectors (keyscore, typescore, authorscore)
	ProcessAll(number, pathstr);

// create a file to recorded the un-recommended articles later
	ofstream outfile;
	char pathRR[80];
	strcpy(pathRR, pathstr.c_str());
	strcat(pathRR, "rest.txt");
	outfile.open(pathRR);
	int i=1;
	for(i=1;i<=number; i++){
		outfile<<i;
		outfile<<endl;
	}
	outfile.close();
}





