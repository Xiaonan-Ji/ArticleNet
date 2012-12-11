// pubmed.cpp
// Author: Xiaonan Ji, Date: 12/8/2012
// Link to pubmed, and show all included articles (read in II.txt).

#include "Parser.h"
#include "Functions.h"
#include <iostream>
#include <vector>
#include <string>
#include <string.h>
#include <cstring>

using namespace std;

void showid(char * path, int number, char * id){
	
	char filename[80];
	char name[10];
	sprintf(name, "%d",number);
	strcpy(filename, path);
	strcat(filename, name);
		
	Parser article = Parser(filename);
	article.getid(id);
}

int main(int argc, char* argv[]){
	if(argc<2){
		 cout<<"please enter enough parameters."<<endl;
        exit(-1);
    }

	char path[80];
	strcpy(path, argv[1]);
	char IIpath[100];
	strcpy(IIpath, argv[1]);
	strcat(IIpath, "II.txt");
	
	ifstream infile;
	infile.open(IIpath);
	char address[2000];
	strcpy(address, "http://www.ncbi.nlm.nih.gov/pubmed?term=");
	
	string word;
	int number;
	char pmid[20];
	int count=0;
	while(infile>>word){
		number=atoi(word.c_str());
		showid(path, number, pmid);
		if(count==0){
			strcat(address,pmid);
		}
		else if(count>0){
			strcat(address,"%20");
			strcat(address,pmid);
		}
		count++;
	}
	
	cout<<"<h17>Please click the button below to see all included articles in PubMed and you can download them there.</h17><br>"<<endl;
	cout<<"<h9><a href="<<address<<" target='_blank'>"<<"<input type='button' name='pubmed' class='btn success' value='Go to Pubmed!'/>"<<"</a></h9><br>"<<endl;
	cout<<"<br>";
}
