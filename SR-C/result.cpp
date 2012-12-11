// result.cpp
// Author: Xiaonan Ji, Date: 12/8/2012
// Show a summary report

#include "Parser.h"
#include <iostream>
#include <vector>
#include <map>
#include <fstream>
#include <string>
#include <algorithm>
#include <cctype>
#include <stdlib.h>  /* for malloc, free */
#include <string.h>  /* for memcmp, memmove */
#include <stdio.h>
#include <ctype.h> 
#include <math.h>

using namespace std;

void display(char * path, int number, char * id){
	
	char filename[80];
	char name[10];
	sprintf(name, "%d",number);
	strcpy(filename, path);
	strcat(filename, name);
		
	Parser article = Parser(filename);
	article.Output1();
	article.getid(id);
}

int main(int argc, char* argv[]){
	if (argc < 6) {
        printf("please enter enough parameters.");
        exit(-1);
    }

	char path[50];
        strcpy (path, argv[1]);
	char filename1[80];
	strcpy(filename1, path);
	strcat(filename1, "II.txt");
	char filename2[80];
	strcpy(filename2, path);
	strcat(filename2, "EE.txt");
	
    ifstream infile1;
	ifstream infile2; 

	infile1.open(filename1);
	infile2.open(filename2);
	string word1;
	vector<string> words1;
	string word2;
	vector<string> words2;
	int num1=0;
	int num2=0;

	if(!infile1){
		cout<<"Can not open file:"<<filename1 <<endl;
		exit(1);
	}
	
	if(!infile2){
		cout<<"Can not open file:"<<filename2 <<endl;
		exit(1);
	}
	
	// read in rest-list
	vector<int> RRlist;	
	ifstream infileR;
	char pathRR[80];
	strcpy(pathRR, path);
	strcat(pathRR, "rest.txt");
	infileR.open(pathRR);
	
	char wordR[15];
	int RRnum = 0;
	int numRR = 0;
	while(infileR >> wordR){		
		if(wordR[0]!='\0'){
		RRnum = atoi(wordR);
		RRlist.push_back(RRnum);
		numRR++;
        }
	}
	infileR.close();
	
	int recommendID; 
	char pmid[20];
	int total = atoi(argv[2]);
	int step = atoi(argv[3]);
	int each = atoi(argv[4]);
	int recommend = total-numRR;

	cout<<"<h4>Total number of articles: "<<total<<" </h4>"<<endl;
	cout<<"<h4>Total recommended articles: "<<recommend<<" </h4>"<<endl;
	cout<<"<hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'>"<<endl;
		
	cout<<"<h15>Articles you have included are: </h15>"<<endl;
	cout<<"<br>";
	while(infile1 >> word1){		
		if(word1[0]!='\0'){
		words1.push_back (word1);
		num1++;
		recommendID = atoi(word1.c_str());
		display(path, recommendID, pmid);
		cout<<"<h7><a href=http://www.ncbi.nlm.nih.gov/pubmed/"<<pmid<<">"<<"See this article in PubMed</a></h7>"<<endl;
		cout<<"<br>";
		cout<<"<br>";
        }
	}
	cout<<"<h17>There are "<<num1<<" articles you have included.</h17>"<<endl;
	cout<<"<br>";
	cout<<"<hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'>";
	
	cout<<"<h16>Articles you have excluded are: </h16>"<<endl;
	cout<<"<br>";
	while(infile2 >> word2){		
		if(word2[0]!='\0'){
		words2.push_back (word2);
		num2++;
		recommendID = atoi(word2.c_str());
		display(path, recommendID, pmid);
		cout<<"<h7><a href=http://www.ncbi.nlm.nih.gov/pubmed/"<<pmid<<">"<<"See this article in PubMed</a></h7>"<<endl;
		cout<<"<br>";
		cout<<"<br>";
        }
	}
	cout<<"<h17>There are "<<num2<<" articles you have excluded.</h17>"<<endl;
	cout<<"<br>";
	cout<<"<hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'>";

	int undecide = recommend-num1-num2;
	int restnum = numRR;
	cout<<"<h4>No.of articles you have not decided: "<<undecide<<" </h4>"<<endl;
	cout<<"<h4>No. of remaining articles: "<<restnum<<" </h4>"<<endl;
	cout<<"<br>";
}
