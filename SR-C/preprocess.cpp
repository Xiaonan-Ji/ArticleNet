//preprocess.cpp
//Author: Xiaonan Ji, Date: 12/8/2012
//Preprocess the input article list, which is in MEDLINE format.
//Sperate each article into one corresponding file respectively, name from 1 to n.
#include <iostream>
#include <vector>
#include <map>
#include <fstream>
#include <sstream>
#include <string>
#include <cstring>
#include <stdlib.h>
#include <stdio.h>

using namespace std;

int main(int argc, char* argv[]){
	if (argc < 4) {
        printf("please enter enough parameters.");
        exit(-1);
    }

	int len = sizeof argv[1];
	char filename[50];
    strcpy (filename, argv[1]);
    cout<<"Filename1 is: " << filename <<endl;
    cout<<"<br>";
	
    ifstream infile;
	ofstream outfile; 

	infile.open(filename);
	string word;
	vector<string> words;

	if(!infile){
		cout<<"Can not open file:"<<filename <<endl;
		exit(1);
	}
	
	cout<<"start to parse the input article list......"<<endl;
	cout<<"<br>";

    char path[20];
	int i=atoi(argv[2]);
	i = i-1;	
	char finalpath[80];
	strcpy (finalpath, argv[3]);
	
	string wordline;
	string single;
	
	while(!infile.eof()){
		while(getline(infile, wordline) && wordline[0]!='\0'){
			stringstream ss(wordline);
			ss >> single;
			strcpy (finalpath, argv[3]);
			if( single=="PMID-"){
				i++;
				bzero(finalpath, 80);
				strcpy (finalpath, argv[3]);
				sprintf(path, "%d",i);
				strcat(finalpath, path);
				cout <<endl;			
				cout <<"path is: " << finalpath <<" ";
				if(i>1){
					outfile.close();
				}
				outfile.open(finalpath, ios_base::out);
				if (!outfile) cout<< "can not create file "<< finalpath <<endl; 
			}
		
			outfile << wordline <<endl;
			cout << wordline <<endl;
		}
	}
	
	outfile.close();
	cout<<"all done!";
	//cout<<"<br>";
}



