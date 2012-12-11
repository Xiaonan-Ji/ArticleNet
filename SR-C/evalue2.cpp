// evalue2.cpp
// Author: Xiaonan Ji, Date: 12/8/2012
// Read in article network, interavtively recommend articles and accept user's feedbacks.
// Applied for users' application.

#include "Parser.h"
#include <iostream>
#include <vector>
#include <map>
#include <list>
#include <algorithm>
#include <fstream>
#include <string>
#include <cctype>
#include <cstring>
#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <cmath>
#include <cstdlib>
#include <ctime>

using namespace std;

// get pumid of one article
void display(char * path, int number, char * id){
	
	char filename[80];
	char name[10];
	sprintf(name, "%d",number);
	strcpy(filename, path);
	strcat(filename, name);
	Parser article = Parser(filename);
	article.Output2();
	article.getid(id);
}

int main(int argc, char* argv[]){

	if (argc < 15) {
        printf("please enter enough parameters.");
        exit(-1);
    	}
	int num = atoi(argv[1]);
	int step = atoi(argv[2]);
	int size = atoi(argv[3]);
	char path[80];
	strcpy(path, argv[4]);
	int IIsize = atoi(argv[5]);
	double keyweight = atof(argv[6]);
	double typeweight = atof(argv[7]);
	double abweight = atof(argv[8]);
	double mhweight = atof(argv[9]);
	double tiweight = atof(argv[10]);
	double auweight1 = atof(argv[11]);
	double auweight2 = atof(argv[12]);
	int round = atoi(argv[13]);
	int recommend = atoi(argv[14]);
	int size1 = size;
	int index=1;
		
// read in matrix
	ifstream infile;
	char pathab[80];
	strcpy(pathab, path);
	strcat(pathab, "matrixAB.txt");
	infile.open(pathab);
	
	ifstream infileM;
	char pathmh[80];
	strcpy(pathmh, path);
	strcat(pathmh, "matrixMH.txt");
	infileM.open(pathmh);
	
	ifstream infileT;
	char pathti[80];
	strcpy(pathti, path);
	strcat(pathti, "matrixTI.txt");
	infileT.open(pathti);
	
	ifstream infileAU1;
	char pathau1[80];
	strcpy(pathau1, path);
	strcat(pathau1, "matrixAU.txt");
	infileAU1.open(pathau1);
	
	size = size+1;
	
	vector<vector<double> > matrix(size, vector<double>(size)); 
	vector<vector<double> > matrixMH(size, vector<double>(size)); 
	vector<vector<double> > matrixTI(size, vector<double>(size)); 
	vector<vector<double> > matrixAU(size, vector<double>(size)); 
	
	vector<vector<double> >::iterator itm;
	vector<double>::iterator itr;
	int i, j =1;
	
	char word[15];
	for(i=1;i<size;i++){
		for(j=1;j<size;j++){
			infile >> word;
			matrix[i][j]=atof(word);
		}
	}
	
	char wordM[15];
	for(i=1;i<size;i++){
		for(j=1;j<size;j++){
			infileM >> wordM;
			matrixMH[i][j]=atof(wordM);
		}
	}
	
	char wordT[15];
	for(i=1;i<size;i++){
		for(j=1;j<size;j++){
			infileT >> wordT;
			matrixTI[i][j]=atof(wordT);
		}
	}
	
	char wordAU1[15];
	for(i=1;i<size;i++){
		for(j=1;j<size;j++){
			infileAU1 >> wordAU1;
			matrixAU[i][j]=atof(wordAU1);
		}
	}

//read in TypeScore
	ifstream infile1;
	char pathts[80];
	strcpy(pathts, path);
	strcat(pathts, "TypeScore.txt");
	infile1.open(pathts);
	double TypeScore[size];
	char wordt[5];
	
	for(i=1;i<size;i++){
		infile1 >> wordt;
		TypeScore[i]=atof(wordt);
	}
	
//read in KeyScore
	ifstream infile2;
	char pathks[80];
	strcpy(pathks, path);
	strcat(pathks, "KeyScore.txt");
	infile2.open(pathks);
	double KeyScore[size];
	char wordk[5];
	
	for(i=1;i<size;i++){
		infile2 >> wordk;
		KeyScore[i]=atof(wordk);
	}
	
//read in AuthorScore
	ifstream infile3;
	char pathas[80];
	strcpy(pathas, path);
	strcat(pathas, "AuthorScore.txt");
	infile3.open(pathas);
	double AuthorScore[size];
	char wordas[5];
	
	for(i=1;i<size;i++){
		infile3 >> wordas;
		AuthorScore[i]=atof(wordas);
	}
	
// create an II-list from the user
	vector<int> IIlist;
	ifstream infileI;
	char pathII[80];
	strcpy(pathII, path);
	strcat(pathII, "II.txt");
	infileI.open(pathII);
	
	char wordI[15];
	int IInum = 0;
	int numII = 0;
	while(infileI >> wordI){		
		if(wordI[0]!='\0'){
		IInum = atoi(wordI);
		IIlist.push_back(IInum);
		numII++;
        }
	}
	
// create an EE-list from the user
	vector<int> EElist;
	ifstream infileE;
	char pathEE[80];
	strcpy(pathEE, path);
	strcat(pathEE, "EE.txt");
	infileE.open(pathEE);
	
	char wordE[15];
	int EEnum = 0;
	int numEE = 0;
	while(infileE >> wordE){		
		if(wordE[0]!='\0'){
		EEnum = atoi(wordE);
		EElist.push_back(EEnum);
		numEE++;
        }
	}
	
// read in rest-list, the un-recommended articles
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

	int restnum = numRR;
	int undecide = size1-numRR-numII-numEE;
	recommend = numII+numEE+undecide;
	if(round>=1){
		cout<<"<h4>Total number of articles: "<<size1<<" </h4>"<<endl;
		cout<<"<h4>Total recommended articles: "<<recommend<<" </h4>"<<endl;
		cout<<"<h4>--No.of articles you have included: "<<numII<<" </h4>"<<endl;
		cout<<"<h4>--No.of articles you have excluded: "<<numEE<<" </h4>"<<endl;
		cout<<"<h4>--No.of articles you have not decided: "<<undecide<<" </h4>"<<endl;
		cout<<"<h4>Remaining articles: "<<restnum<<" </h4>"<<endl;
		cout<<"<hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'>"<<endl;
	}

	
// Arrange the rest articles besides II-list and EE-list
	map<int,double> rest;
	vector<int>::iterator itII;
	vector<int>::iterator itEE;
	for(i=1;i<=size;i++){
		rest[i]=0;
	}
	
	for(itII=IIlist.begin(); itII!=IIlist.end();itII++){
		rest.erase(*itII);
	}
	for(itEE=EElist.begin(); itEE!=EElist.end();itEE++){
		rest.erase(*itEE);
	}
	
	vector<int>::iterator it1;
	vector<int>::iterator it2;
	vector<int>::iterator itR;
	map<int,double>::iterator itrest;
	
	int m,n=1;
	int ID1,ID2;
	double highscore=0;
	int recommandID = 0;
	int firstID = 0;
	
// set the weight parameters, system parameter
	double weightAB = 1;
	double weightType = 1;
	double weightKey = 0.015;
	double weightMH = 0.5;
	double weightTI = 0.6;
	double weightAU1 = 0.5;
	double weightAU2 = 0.05;

// combine system parameters with user designed parameters
	weightType = weightType*typeweight;
	weightKey = weightKey*keyweight;
	weightMH = weightMH*mhweight;
	weightTI = weightTI*tiweight;
	weightAU1 = weightAU1*auweight1;
	weightAU2 = weightAU2*auweight2;
	weightAB = weightAB*abweight;

	char pmid[20];
	int firstround=0;
	
// if the II list is empty, find the fisrt articles according to the relevance vector
	if(numII==0){
		if(num > size){
			cout<<"<h4>Sorry, the number of articles we reocmmended in each round should be no more than the entire article list size</h4>"<<endl;
			exit(1);
		}
		firstround=1;
		for(itrest=rest.begin();itrest!=rest.end();itrest++){
				ID2 = (*itrest).first;
				(*itrest).second+=(TypeScore[ID2]*weightType);
				(*itrest).second+=(KeyScore[ID2]*0.02);
				(*itrest).second+=(AuthorScore[ID2]*weightAU2);
				if((*itrest).second<0) (*itrest).second=0;
		}
		
		for(i=1;i<=num;i++){
			for(itrest=rest.begin();itrest!=rest.end();itrest++){
				if((*itrest).second>highscore){
					highscore=(*itrest).second;
					firstID=(*itrest).first;
				}
			}
			IIlist.push_back(firstID);
			rest.erase(firstID);
			for(itR=RRlist.begin(); itR<RRlist.end(); itR++){
				if(*itR == firstID){
					RRlist.erase(itR);
					break;
				}
			}

			cout<<"<h4>Article (based on relevance vector) "<<i<<" : </h4>"<<"(systemID is "<<firstID<<")"<<endl;
			cout<<"<br>";
			display(path, firstID, pmid);
			cout<<"<h9><a href=http://www.ncbi.nlm.nih.gov/pubmed/"<<pmid<<" target='_blank'>"<<"See this article in PubMed</a></h9><br>"<<endl;		
			cout<<"<input type='radio' name='step"<<i<<"' value ='"<<firstID<<"'><h11> Select as Included </h11><br>"<<endl;
			cout<<"<input type='radio' name='step"<<i<<"' value ='0000"<<firstID<<"'><h12> Select as Excluded </h12><br>"<<endl;
			cout<<"<input type='radio' name='step"<<i<<"' value ='clear'><h9> Undecided </h9><br>"<<endl;
			cout<<"<br>";
			cout<<"<br>";
			
			index = index+1;
			numII++;
			highscore=0;
			firstID = 0;
		}
		for(itrest=rest.begin();itrest!=rest.end();itrest++){
			(*itrest).second=0;
		}
	}

// reset the scores	
	for(itrest=rest.begin();itrest!=rest.end();itrest++){
			(*itrest).second=0;
	}
	
// if there are already inlcuded articles, start to recommend based on the user decided II list
	highscore=0;
	recommandID = 0;
	
	if(firstround==0){
		for(i=1;i<=step;i++){
//caculate the socres of each article	
			for(itrest=rest.begin();itrest!=rest.end();itrest++){
				for(it1=IIlist.begin();it1!=IIlist.end();it1++){
					ID1 = *it1;
					ID2 = (*itrest).first;
					(*itrest).second+=(matrix[ID1][ID2]*weightAB);
					(*itrest).second+=(matrixMH[ID1][ID2]*weightMH);
					(*itrest).second+=(matrixTI[ID1][ID2]*weightTI);
					(*itrest).second+=(matrixAU[ID1][ID2]*weightAU1);
				}
				(*itrest).second=((*itrest).second)/numII;
				
				for(it2=EElist.begin();it2!=EElist.end();it2++){
					ID1 = *it2;
					ID2 = (*itrest).first;
// edit is penalty should be applied					
					//(*itrest).second-=(matrix[ID1][ID2]/2);
				}
				(*itrest).second+=(TypeScore[ID2]*weightType);
				(*itrest).second+=(KeyScore[ID2]*weightKey);
				(*itrest).second+=(AuthorScore[ID2]*weightAU2);
				if((*itrest).second<0) (*itrest).second=0;
			}
// find the highest score
			for(itrest=rest.begin();itrest!=rest.end();itrest++){
				if((*itrest).second>highscore){
					highscore=(*itrest).second;
					recommandID=(*itrest).first;
				}
			}
		
			rest.erase(recommandID);
			for(itR=RRlist.begin(); itR<RRlist.end(); itR++){
				if(*itR == recommandID){
					RRlist.erase(itR);
					break;
				}
			}
			
			if(rest.size()<1){
					cout<<"<h4>There is no more article can be recommended, candidate pool is empty now.</h4>"<<endl;
					break;
			}
			
			cout<<"<h4>Article "<<i<<" : </h4>"<<"(systemID is "<<recommandID<<")"<<endl;
			cout<<"<br>";
			display(path, recommandID, pmid);
			cout<<"<h9><a href=http://www.ncbi.nlm.nih.gov/pubmed/"<<pmid<<" target='_blank'>"<<"See this article in PubMed</a></h9><br>"<<endl;
			cout<<"<input type='radio' name='step"<<i<<"' value ='"<<recommandID<<"'><h11> Select as Included </h11><br>"<<endl;
			cout<<"<input type='radio' name='step"<<i<<"' value ='0000"<<recommandID<<"'><h12> Select as Excluded </h12><br>"<<endl;
			cout<<"<input type='radio' name='step"<<i<<"' value ='clear'><h9> Undecided </h9><br>"<<endl;
			cout<<"<br>";
			cout<<"<br>";
				
			for(itrest=rest.begin();itrest!=rest.end();itrest++){
				(*itrest).second=0;
			}
			highscore = 0;
			recommandID = 0;
		}
	}

//update the rest-list	
	ofstream outfileR;
	outfileR.open(pathRR, ios::out);
	if(!outfileR){
		cout<<"Can not open file: rest.txt"<<endl;
		exit(1);
	}
	i=1;
	for(itR=RRlist.begin(); itR<RRlist.end(); itR++){
		outfileR<<*itR;
		outfileR<<endl;
	}
	outfileR.close();
}

