//Functions.h
//Author: Xiaonan Ji, Date: 12/8/2012
//Functions to caculate similarity and construct an article network

#ifndef _FUNCTIONS_H_
#define _FUNCTIONS_H_

#include "Parser.h"
#include "Stemmer.h"
#include <iostream>
#include <vector>
#include <map>
#include <algorithm>
#include <fstream>
#include <string>
#include <sstream>
#include <cctype>
#include <stdio.h>
#include <ctype.h> 
#include <math.h>

using namespace std;

/* ************************************************************non-major functions********************************************************* */
/*------------------------------------------------------------some helpful functions -------------------------------------------------------*/
struct indexmap{
	int index;
	int repeat;
	int weight;
};

// get word from string one by one
bool GetWord(char * string, char * word,int &wordOffset)
{
  if(!string[wordOffset])
      return false; 

  char * p1,* p2;
  p1=p2=string+wordOffset; 

  for(int i=0;i<(int)strlen(p1)&&!isalnum(p1[0]);i++)
  {
      p1++;
  }
  if(!isalnum(p1[0]))
  {
      return false;
  }
  p2=p1;
  while(isalnum(p2[0]))
  {
      p2++;
  }

  int len=int(p2-p1);
  strncpy(word,p1,len); 
  word[len]='\0';
  for(int j=int(p2-string);j<(int)strlen(string)&&isalnum(p2[0]);j++) 
  {
      p2++;
  }

  wordOffset=int(p2-string); 
  return true;
  
  p1 = string;
  p2 = string;
  delete [] p1;
  delete [] p2;
  p1 = NULL;
  p2 = NULL;
}

bool GetWord2(char * string, char * word,int &wordOffset)
{
  if(!string[wordOffset])
      return false; 

  char * p1,* p2;
  p1=p2=string+wordOffset; 

  for(int i=0;i<(int)strlen(p1)&&(p1[0]==' ');i++)
  {
      p1++;
  }
  if(p1[0]==' ')
  {
      return false;
  }

  p2=p1;
  while(p2[0]!=' ')
  {
      p2++;
  }

  int len=int(p2-p1);
  strncpy(word,p1,len); 
  word[len]='\0';
  for(int j=int(p2-string);j<(int)strlen(string)&&isalnum(p2[0]);j++) 
  {
      p2++;
  }

  wordOffset=int(p2-string); 
  return true;
  
  p1 = string;
  p2 = string;
  delete [] p1;
  delete [] p2;
  p1 = NULL;
  p2 = NULL;
}

int CompareWord(const char * a, const char * b) 
{ 
	while( (*a)!= '\0')
	{	
		if (*a == *b) {
			a++;b++; }
		else if(*a!=*b) break; 
	} 
	if((*a-*b)> 0)   return 1; 
	if((*a-*b)==0)   return 0; 
	if((*a-*b) <0)   return -1; 
	else return 2;
} 

int CompareWord2(string term1, string term2) 
{ 
	const char * a = term1.c_str();
	const char * b = term2.c_str();
	while( (*a)!= '\0')
	{	
		if (*a == *b) {
			a++;b++; }
		else if(*a!=*b) break; 
	} 
	if((*a-*b)> 0)   return 1; 
	if((*a-*b)==0)   return 0; 
	if((*a-*b) <0)   return -1; 
	else return 2;
} 

int CompareWord3(int term1, int term2) 
{ 
	if(term1>term2)   return 1; 
	if(term1==term2)   return 0; 
	if(term1<term2)   return -1; 
	else return 2;
} 

vector<string> split(const string& s)
{
   vector<string> ret;
   typedef string::size_type string_size;
   string_size i = 0;

   // invariant: we have processed characters [original value of i, i) 
   while (i != s.size()) {
      // ignore leading blanks
      // invariant: characters in range [original i, current i) are all spaces
      while (i != s.size() && isspace(s[i]))
         ++i;

      // find end of next word
      string_size j = i;
      // invariant: none of the characters in range [original j, current j)is a space
      while (j != s.size() && !isspace(s[j]))
         j++;

      // if we found some nonwhitespace characters 
      if (i != j) {
         // copy from s starting at i and taking j - i chars
         ret.push_back(s.substr(i, j - i));
         i = j;
      }
   }
   return ret;
}

void Filteragain (map<string,int>  &cntlist){
	ifstream infile;
	infile.open("var/www/cgi-bin/SR/SR-C/commonwords.txt");
	string word1;
	vector<string> common;

	if(!infile){
		cout<<"Can not open file: commonwords.txt"<<endl;
		exit(1);
	}
	
	while( infile >> word1){
		if(word1[0]!='\0'){
			common.push_back (word1);
        }
	}
	map<string,int>::iterator it;
	vector<string>::iterator it1;
	string word;
	int flag=0;
	for (it=cntlist.begin();it!=cntlist.end();it++){
		if(flag==1 && it!=cntlist.begin()){
			--it;
			flag=0;
		}
		word=(*it).first;
		it1 = find(common.begin(), common.end(), word);
		if(it1>=common.begin() && it1<common.end()){
			cntlist.erase(it);
			flag=1;
		}
	}
}

/* **********************************************************************major functions********************************************************************/
/*--------------------------------------------------------------------Similarity caculation----------------------------------------------------------------*/

//caculate abstract similarity and title similarity, old version, not applied
double Cal_similarity(map<string,int> &newlist1, map<string,int> &newlist2, vector<string> &medical){		
	double similarity = 0;
	double molecular = 0;
	double denominator,denominator1,denominator2 = 0;
	int flag = 2;
	map<string,int>::iterator it;
	vector<string>::iterator it1;
	map<string,int>::iterator it2;
	it = newlist1.begin();
	it2 = newlist2.begin();
	int num, num2 = 0;
	int weight = 1;
	
	while (it!=newlist1.end() && it2!=newlist2.end() ){
		num =(*it).second;
		num2 =(*it2).second;
		flag = CompareWord2((*it).first, (*it2).first);
		if(flag == 0){
			it1 = find(medical.begin(), medical.end(), (*it).first);
			if(it1>=medical.begin() && it1<medical.end()){
				weight = 2;
			}
			it1 = medical.end();
		
			molecular += num*num2*weight;
			it++;
			it2++;
		}
		if(flag == 1){
			it2++;
		}
		if(flag == -1){
			it++;
		}
		flag = 2;
		weight = 1;
	}

	denominator1 = 0;
	for (it=newlist1.begin(); it!=newlist1.end(); it++){
		num =(*it).second;
		denominator1 += num*num;
	}
	denominator1 = sqrt(denominator1);
	
	for (it2=newlist2.begin(); it2!=newlist2.end(); it2++){
		num2 =(*it2).second;
		denominator2 += num2*num2;
	}
	denominator2 = sqrt(denominator2);
	denominator = denominator1*denominator2;
	similarity = molecular/denominator;
	
	return similarity;
}

//caculate abstract similarity and title similarity, new version, applied. Use index, speed up! 
double Cal_similarity_new(vector<indexmap> &newlist1, vector<indexmap> &newlist2){
	double similarity = 0;
	double molecular = 0;
	double denominator,denominator1,denominator2 = 0;
	int flag = 2;
	vector<indexmap>::iterator it;
	vector<string>::iterator it1;
	vector<indexmap>::iterator it2;
	it = newlist1.begin();
	it2 = newlist2.begin();
	int num, num2 = 0;
	int weight = 1;
	
	while (it!=newlist1.end() && it2!=newlist2.end() ){
		num =(*it).repeat;
		num2 =(*it2).repeat;
		flag = CompareWord3((*it).index, (*it2).index);
		if(flag == 0){
			weight = (*it).weight;
			molecular += num*num2*weight;
			it++;
			it2++;
		}
		if(flag == 1){
			it2++;
		}
		if(flag == -1){
			it++;
		}
		flag = 2;
		weight = 1;
	}

	denominator1 = 0;
	for (it=newlist1.begin(); it!=newlist1.end(); it++){
		num =(*it).repeat;
		if(num!=0){
			denominator1 += num*num;
		}
	}
	denominator1 = sqrt(denominator1);
	
	for (it2=newlist2.begin(); it2!=newlist2.end(); it2++){
		num2 =(*it2).repeat;
		if(num2!=0){
			denominator2 += num2*num2;
		}
	}
	denominator2 = sqrt(denominator2);
	
	denominator = denominator1*denominator2;
	similarity = molecular/denominator;
	
	return similarity;
}

// caculate MeSH similarity
double Cal_similarity_MH(map<string,int> &newlist1, map<string,int> &newlist2){
	double similarity = 0;
	double molecular = 0;
	double denominator,denominator1,denominator2 = 0;
	int flag = 2;
	map<string,int>::iterator it;
	vector<string>::iterator it1;
	map<string,int>::iterator it2;
	it = newlist1.begin();
	it2 = newlist2.begin();
	int num, num2 = 0;
	int weight = 1;
	
	while (it!=newlist1.end() && it2!=newlist2.end() ){
		num =(*it).second;
		num2 =(*it2).second;
		flag = CompareWord2((*it).first, (*it2).first);
		if(flag == 0){	
			molecular += num*num2;
			it++;
			it2++;
		}
		if(flag == 1){
			it2++;
		}
		if(flag == -1){
			it++;
		}
		flag = 2;
		weight = 1;
	}
	
	denominator1 = 0;
	for (it=newlist1.begin(); it!=newlist1.end(); it++){
		num =(*it).second;
		denominator1 += num*num;
	}
	denominator1 = sqrt(denominator1);
	
	for (it2=newlist2.begin(); it2!=newlist2.end(); it2++){
		num2 =(*it2).second;
		denominator2 += num2*num2;
	}
	denominator2 = sqrt(denominator2);
	
	denominator = denominator1*denominator2;
	similarity = molecular/denominator;

	return similarity;
}

//caculate author similarity
double Cal_similarity_AU(vector<vector<string> > &newlist1, vector<vector<string> > &newlist2){
	double similarity = 0;
	double molecular = 0;
	double denominator,denominator1,denominator2 = 0;
	vector<vector<string> >::iterator it;
	vector<vector<string> >::iterator it2;
	vector<string>::iterator it3;
	vector<string>::iterator it4;

	for(it=newlist1.begin();it<newlist1.end();it++){
		for(it2=newlist2.begin();it2<newlist2.end();it2++){
			it3=(*it).begin();
			it4=(*it2).begin();
			while(it3<(*it).end() && it4<(*it2).end()){
				if(*it3==*it4){ 
					it3++;
					it4++;
				}
				else {break;}
			}
			if(it3==(*it).end() || it4==(*it2).end()){
				molecular++;
			}
		}
	}
	
	denominator1 = newlist1.size();
	denominator1 = sqrt(denominator1);
	denominator2 = newlist2.size();
	denominator2 = sqrt(denominator2);
	
	denominator = denominator1*denominator2;
	similarity = molecular/denominator;
	return similarity;
}


/* ******************************************************************************************************************************************** */
/*---------------------------------------------------------------Process all articles-----------------------------------------------------------*/
void ProcessAll(int number, string path){
	
	cout<<"readin files..."<<endl;
	cout<<"<br>";
//read in common word list
	ifstream infile;
	infile.open("/var/www/cgi-bin/SR/SR-C/commonwords.txt");
	string wordc;
	vector<string> common;
	int i;
	int j;

	if(!infile){
		cout<<"Can not open file: commonwords.txt"<<endl;
		exit(1);
	}
	
	while( infile >> wordc){
		if(wordc[0]!='\0'){
			common.push_back (wordc);
        }
	}
//read in PubType list
	ifstream infile1;
	char pubpath[80];
	strcpy(pubpath, path.c_str());
	strcat(pubpath, "PubType.txt");
	infile1.open(pubpath);
	string wordt;
	string wordline;
	string wordsingle;
	// for single word version
	vector<string> type;
	// for multiple words combination version
	vector<vector<string> > typegroup;

	if(!infile1){
		cout<<"Can not open file: PubType.txt"<<endl;
		exit(1);
	}
	
//single word version
/*
	while( infile1 >> wordt){
		if(wordt[0]!='\0'){
			type.push_back (wordt);
        }
	}
*/
//multiple words phrase version
	i=0;
	vector<string>::iterator test;
	while(getline(infile1, wordline) && wordline[0]!='\0'){
		vector<string> row;
		stringstream ss(wordline);
		while (ss >> wordsingle) {
			if(wordsingle[0]!='\0'){
				transform(wordsingle.begin(), wordsingle.end(), wordsingle.begin(), ::tolower);
				row.push_back(wordsingle);
			}
		}
		typegroup.push_back(row);
		i++;
	}
	int typesize=i;	
	
//read in keywords list
	ifstream infile2;
	char keypath[80];
	strcpy(keypath, path.c_str());
	strcat(keypath, "keywords.txt");
	infile2.open(keypath);
	string wordk;
	// for single word version
	vector<string> keys;
	// for multiple words combination version
	vector<vector<string> > keygroup;

	if(!infile2){
		cout<<"Can not open file: keywords.txt"<<endl;
		exit(1);
	}
	
//read in single keywords
	//while( infile2 >> wordk){
	//	if(wordk[0]!='\0'){
	//		transform(wordk.begin(), wordk.end(), wordk.begin(), ::tolower);
	//		keys.push_back (wordk);
	//		//cout << wordk <<" ";
			//cout<<endl;
     //   }
	//}
	//infile2.seekg(ios::beg);
	
// read in phrase (multiple words)
	i=0;
	while(getline(infile2, wordline) && wordline[0]!='\0'){
		vector<string> row;
		stringstream ss(wordline);
		while (ss >> wordsingle) {
			if(wordsingle[0]!='\0'){
				transform(wordsingle.begin(), wordsingle.end(), wordsingle.begin(), ::tolower);
				row.push_back(wordsingle);
			}
		}
		keygroup.push_back(row);
		i++;
	}
	int keysize=i;	
	
// read in medicalwords list
	ifstream infile3;
	infile3.open("/var/www/cgi-bin/SR/SR-C/medicalwords.txt");
	string wordm;
	vector<string> medical;

	if(!infile3){
		cout<<"Can not open file: medicalwords.txt"<<endl;
		exit(1);
	}
	
	while( infile3 >> wordm){
		if(wordm[0]!='\0'){
			medical.push_back (wordm);
        }
	}

//read in author list
	ifstream infile4;
	char authorpath[80];
	strcpy(authorpath, path.c_str());
	strcat(authorpath, "authors.txt");
	infile4.open(authorpath);
	string wordau;
	// for single word version
	vector<string> authors;
	// for multiple words combination version
	vector<vector<string> > authorgroup;

	if(!infile4){
		cout<<"Can not open file: keywords.txt"<<endl;
		exit(1);
	}
	
// signle word version
/*
	while( infile4 >> wordau){
		if(wordau[0]!='\0'){
			transform(wordau.begin(), wordau.end(), wordau.begin(), ::tolower);
			authors.push_back (wordau);
        }
	}
*/	

// multiple words version
	i=0;
	size_t found;
	string lastname;
	string lastname1;
	string firstname;
	while(getline(infile4, wordline) && wordline[0]!='\0'){
		vector<string> row;
		stringstream ss(wordline);
		found=wordline.find_last_of(" ");
		lastname=wordline.substr(found+1);
		lastname1=lastname;
		transform(lastname.begin(), lastname.end(), lastname.begin(), ::tolower);
		row.push_back(lastname);
		while (ss >> wordsingle) {
			if(wordsingle[0]!='\0' && wordsingle!=lastname1){
				transform(wordsingle.begin(), wordsingle.end(), wordsingle.begin(), ::tolower);
				firstname+=wordsingle[0];
			}
		}
		row.push_back(firstname);
		authorgroup.push_back(row);
		lastname.clear();
		firstname.clear();
		lastname1.clear();
		found=-1;
		i++;
	}
	int authorsize=i;

	
//read in keylist for MeshTerms, not applied at this time
	/*ifstream infile4;
	infile3.open("/var/www/cgi-bin/SR/SR-C/MHkeylist.txt");
	string wordMH;
	vector<string> MHkeylist;

	if(!infile4){
		cout<<"Can not open file: MHkeylist.txt"<<endl;
		exit(1);
	}
	
	while( infile4 >> wordMH){
		if(wordMH[0]!='\0'){
			MHkeylist.push_back (wordMH);
        }
	} */
	
	
//process each article
	string filename;
	char name[20];
	string namestr;
	char input[50];

	int articlenum = number+1;
	Parser article[articlenum];
	
	map<string,int> wordlist[number+1];
	map<string,int> MHlist[number+1];
	map<string,int> TIlist[number+1];
	vector<vector<string> > AUlist[number+1];
	double TypeScore[number+1];
	double KeyScore[number+1];
	double AuthorScore[number+1];
	
	map<string,int>::iterator it;
	map<string,vector<int> >::iterator itpos;
	map<string,vector<int> >::iterator itpos2;
	vector<int>::iterator counter; 
	
	struct stemmer * z = create_stemmer();
    s = (char *) malloc(i_max + 1);
	string stemlist;
	string stemlist2;
	string stemlist_s;
	string stemlist_s2;
	int listlengthorg1 = 0;
	int listlengthorg2 = 0;
	int listlength1 = 0;
	int listlength2 = 0;
	
//wordlistAll, an overall counts for each word, abstract
	map<string, vector<int> > wordlistAll;

//wordlistAll2, an overall counts for each word, title
	map<string, vector<int> > wordlistAll2;
	
	cout<<"process articles..."<<endl;
	cout<<"<br>";
	for (i=1; i<=number; i++){
		sprintf(name, "%d",i);
		namestr = name;
		filename=path+namestr;
		strcpy(input,filename.c_str());
		
		//cout<<"process article "<<i<<endl;
		//cout<<"<br>";
		article[i] = Parser(input);
		//article[i].Output();
		article[i].Cal_Frequency();
		//article[i].Output_Frequency();
		article[i].Filter(common);
		article[i].Filter_Number();
		
		article[i].Cal_Frequency_MH();
		article[i].Filter_Number_MH();
		//article[i].Output_Frequency_MH();
		
		article[i].Cal_Frequency_TI();
		article[i].FilterTI(common);
		article[i].Filter_Number_TI();
		
		article[i].Cal_Frequency_AU();
		//article[i].Output_Frequency_AU();
		
		wordlist[i] = article[i].getlist();
		MHlist[i] = article[i].getMeshTerm();
		TIlist[i] = article[i].getTI();
		AUlist[i] = article[i].getAU2();
			
//wordlistAll, an overall counts for each word
		for(it=wordlist[i].begin();it!=wordlist[i].end();it++){
			stemlist.append((*it).first);
			stemlist.append(" ");		
			listlengthorg1++;
		
			itpos = wordlistAll.find((*it).first);
			if(itpos == wordlistAll.end()){
				wordlistAll[(*it).first].assign(number+1, 0);
				wordlistAll[(*it).first][i] += (*it).second;
			}
			if(itpos != wordlistAll.end()){
				wordlistAll[(*it).first][i] += (*it).second;
			}
		}

//wordlistAll2, an overall counts for each word
		for(it=TIlist[i].begin();it!=TIlist[i].end();it++){
			stemlist2.append((*it).first);
			stemlist2.append(" ");		
			listlengthorg2++;
			
			itpos = wordlistAll2.find((*it).first);
			if(itpos == wordlistAll2.end()){
				wordlistAll2[(*it).first].assign(number+1, 0);
				wordlistAll2[(*it).first][i] += (*it).second;
			}
			if(itpos != wordlistAll2.end()){
				wordlistAll2[(*it).first][i] += (*it).second;
			}
		}
		
// get the TypeScore, keyscore and authorscore (relevance vector)
		TypeScore[i] = article[i].getTypeScore2(typegroup, typesize);
		KeyScore[i] = article[i].getKeyScore3(keygroup, keysize);
		AuthorScore[i] = article[i].getAuthorScore2(authorgroup, authorsize);
		
	}
	
//Test wordlistAll, generate stemmer list(short version) for abstract
	for(itpos=wordlistAll.begin(); itpos!=wordlistAll.end(); itpos++){
		stemlist_s.append((*itpos).first);
		stemlist_s.append(" ");
		listlength1++;
	}
	
//Test wordlistAll2, generate stemmer list(short version) for title
	for(itpos=wordlistAll2.begin(); itpos!=wordlistAll2.end(); itpos++){
		stemlist_s2.append((*itpos).first);
		stemlist_s2.append(" ");
		listlength2++;
	}
	
	cout<<"listlength org1: "<<listlengthorg1<<endl;
	cout<<"listlength1: "<<listlength1<<endl;
	cout<<"listlength org2: "<<listlengthorg2<<endl;
	cout<<"listlength2: "<<listlength2<<endl;
	
//stemming
// call stemmer for abstract
	int size = stemlist_s.length();
	char list[size];
	strncpy(list, stemlist_s.c_str(),size);
	string temp = list;
	
	cout<<endl;	
	cout << "process stemming stage1..."<<endl;
	cout<<"<br>";
	char stemmedlist[4194304] = {0};
	stemfile(z,list,size,stemmedlist); 
	
// refine the wordlist after stemming
	map<string, vector<int> > newlist;
	char word[60];
	int wordOffset=0;

	itpos2 = wordlistAll.begin();
	for (i=1; i<=listlength1; i++){
		GetWord(stemmedlist,word,wordOffset);
		itpos = newlist.find(word);
		if(itpos == newlist.end()){
			newlist[word].assign(number+1, 0);
			newlist[word] = (*itpos2).second;
		}
		if(itpos != newlist.end()){
			for(j=1; j<=number; j++){
				newlist[word][j] += ((*itpos2).second)[j];
			}
		}
		itpos2++;
	}
	
//construct a word(index)-frequency map for each article's abstract
	vector<indexmap> abmap[number+1];
	int indexNO = 0;
	int weight = 1; //consider medical words
	vector<string>::iterator it1;
	for(itpos=newlist.begin(); itpos!=newlist.end(); itpos++){
		weight = 1;
		it1 = find(medical.begin(), medical.end(), (*itpos).first);
		if(it1>=medical.begin() && it1<medical.end()){
			weight = 2;
		}
		it1 = medical.end();
		
		for(i=1; i<=number; i++){
			if(((*itpos).second)[i]>0){
				indexmap myindex = {indexNO, ((*itpos).second)[i], weight};
				abmap[i].push_back(myindex);
			}
		}
		indexNO++;
	}
	cout<<"listlength new1: "<<indexNO<<endl;

// call stemmer for title
	int size2 = stemlist_s2.length();
	char list2[size2];
	strncpy(list2, stemlist_s2.c_str(),size2);
	string temp2 = list2;
	
	cout<<endl;	
	cout << "process stemming stage2..."<<endl;
	cout<<"<br>";
	char stemmedlist2[2000000] = {0};
	stemfile(z,list2,size2,stemmedlist2); 

	free(s);
    free_stemmer(z);
	
// refine the wordlist after stemming
	map<string, vector<int> > newlist2;
	char word2[60];
	int wordOffset2=0;
	
	itpos2 = wordlistAll2.begin();
	for (i=1; i<=listlength2; i++){
		GetWord(stemmedlist2,word2,wordOffset2);
		itpos = newlist2.find(word2);
		if(itpos == newlist2.end()){
			newlist2[word2].assign(number+1, 0);
			newlist2[word2] = (*itpos2).second;
		}
		if(itpos != newlist2.end()){
			for(j=1; j<=number; j++){
				newlist2[word2][j] += ((*itpos2).second)[j];
			}
		}	
		itpos2++;
	}
	
//construct a word(index)-frequency map for each article's title
	vector<indexmap> titlemap[number+1];
	indexNO = 0;
	weight = 1;
	for(itpos=newlist2.begin(); itpos!=newlist2.end(); itpos++){
		weight = 1;
		it1 = find(medical.begin(), medical.end(), (*itpos).first);
		
		if(it1>=medical.begin() && it1<medical.end()){
			weight = 2;
		}
		it1 = medical.end();
	
		for(i=1; i<=number; i++){
			if(((*itpos).second)[i]>0){
				indexmap myindex = {indexNO, ((*itpos).second)[i], weight};
				titlemap[i].push_back(myindex);
			}
		}
		indexNO++;
	}
	cout<<"listlength new2: "<<indexNO<<endl; 
	
// caculate similarity, abstract
	cout<<endl;
	cout<<"caculate the abstract similarity..."<<endl;
	cout<<"<br>";
	vector<vector<double> > matrix(number+1, vector<double>(number+1)); 
	for(i=1;i<=number;i++){
		for(j=1;j<=i;j++){
			//cout<<"The similarity between "<< i <<" and "<< j << " is: ";
			matrix[i][j]=Cal_similarity_new(abmap[i], abmap[j]);
		}
	}
	
	for(i=1;i<=number;i++){
		for(j=i+1;j<=number;j++){
			//cout<<"The similarity between "<< i <<" and "<< j << " is: ";
			matrix[i][j]=matrix[j][i];
		}
	}

//output Similarity Matrix
	ofstream outfile;
	char abpath[80];
	strcpy(abpath, path.c_str());
	strcat(abpath, "matrixAB.txt");
	outfile.open(abpath);
	if(!outfile){
		cout<<"Can not open file: matrixAB.txt"<<endl;
		exit(1);
	}
	
	for(i=1;i<=number;i++){
		for(j=1;j<=number;j++){
			outfile<<matrix[i][j]<<" ";
		}
		outfile<<endl;
	}			
	outfile.close();		

//output Type acore
	ofstream outfile1;
	char typescorepath[80];
	strcpy(typescorepath, path.c_str());
	strcat(typescorepath, "TypeScore.txt");
	outfile1.open(typescorepath);
	if(!outfile1){
		cout<<"Can not open file: TypeScore.txt"<<endl;
		exit(1);
	}
	
	for(i=1;i<=number;i++){
		outfile1<<TypeScore[i]<<endl;;
	}			
	outfile1.close();	

//output Keywords Score
	ofstream outfile2;
	char keyscorepath[80];
	strcpy(keyscorepath, path.c_str());
	strcat(keyscorepath, "KeyScore.txt");
	outfile2.open(keyscorepath);
	if(!outfile2){
		cout<<"Can not open file: KeyScore.txt"<<endl;
		exit(1);
	}
	
	for(i=1;i<=number;i++){
		outfile2<<KeyScore[i]<<endl;;
	}			
	outfile2.close();	
	
//output Author Score
	ofstream outfile5;
	char auscorepath[80];
	strcpy(auscorepath, path.c_str());
	strcat(auscorepath, "AuthorScore.txt");
	outfile5.open(auscorepath);
	if(!outfile5){
		cout<<"Can not open file: KeyScore.txt"<<endl;
		exit(1);
	}
	
	for(i=1;i<=number;i++){
		outfile5<<AuthorScore[i]<<endl;;
	}			
	outfile5.close();	
	

// caculate MeshTerm similarity matrix and output it
	cout<<endl;
	cout<<"caculate the meshterm similarity..."<<endl;
	cout<<"<br>";	

	vector<vector<double> > matrixMH(number+1, vector<double>(number+1)); 
	for(i=1;i<=number;i++){
		for(j=1;j<=i;j++){
			//cout<<"The Mesh Term similarity between "<< i <<" and "<< j << " is: ";
			matrixMH[i][j]=Cal_similarity_MH(MHlist[i], MHlist[j]);
		}
	}
	
	for(i=1;i<=number;i++){
		for(j=i+1;j<=number;j++){
			//cout<<"The Mesh Term similarity between "<< i <<" and "<< j << " is: ";
			matrixMH[i][j]=matrixMH[j][i];
		}
	}
	
	ofstream outfile3;
	char mhpath[80];
	strcpy(mhpath, path.c_str());
	strcat(mhpath, "matrixMH.txt");
	outfile3.open(mhpath);
	if(!outfile3){
		cout<<"Can not open file: matrixMH.txt"<<endl;
		exit(1);
	}
	
	for(i=1;i<=number;i++){
		for(j=1;j<=number;j++){
			outfile3<<matrixMH[i][j]<<" ";
		}
		outfile3<<endl;		
	}
		outfile3.close();	
		
// caculate TI similarity matrix and output it	
	cout<<endl;
	cout<<"caculate the title similarity..."<<endl;
	cout<<"<br>";
	vector<vector<double> > matrixTI(number+1, vector<double>(number+1)); 
	for(i=1;i<=number;i++){
		for(j=1;j<=i;j++){
			//cout<<"The similarity between "<< i <<" and "<< j << " is: ";
			matrixTI[i][j]=Cal_similarity_new(titlemap[i], titlemap[j]);
		}
	}
	
	for(i=1;i<=number;i++){
		for(j=i+1;j<=number;j++){
			//cout<<"The similarity between "<< i <<" and "<< j << " is: ";
			matrixTI[i][j]=matrixTI[j][i];
		}
	}

//output Similarity Matrix	
	ofstream outfile4;
	char timatrixpath[80];
	strcpy(timatrixpath, path.c_str());
	strcat(timatrixpath, "matrixTI.txt");
	outfile4.open(timatrixpath);
	if(!outfile4){
		cout<<"Can not open file: TypeScore.txt"<<endl;
		exit(1);
	}
	
	for(i=1;i<=number;i++){
		for(j=1;j<=number;j++){
			outfile4<<matrixTI[i][j]<<" ";
		}
		outfile4<<endl;		
	}
		outfile4.close();	
		
		
// caculate Author similarity matrix and output it
	cout<<endl;
	cout<<"caculate the author similarity..."<<endl;
	cout<<"<br>";	

	vector<vector<double> > matrixAU(number+1, vector<double>(number+1)); 
	for(i=1;i<=number;i++){
		for(j=1;j<=i;j++){
			//cout<<"The Mesh Term similarity between "<< i <<" and "<< j << " is: ";
			matrixAU[i][j]=Cal_similarity_AU(AUlist[i], AUlist[j]);
		}
	}
	
	for(i=1;i<=number;i++){
		for(j=i+1;j<=number;j++){
			//cout<<"The Mesh Term similarity between "<< i <<" and "<< j << " is: ";
			matrixAU[i][j]=matrixAU[j][i];
		}
	}
	
	ofstream outfile6;
	char aupath[80];
	strcpy(aupath, path.c_str());
	strcat(aupath, "matrixAU.txt");
	outfile6.open(aupath);
	if(!outfile6){
		cout<<"Can not open file: matrixMH.txt"<<endl;
		exit(1);
	}
	
	for(i=1;i<=number;i++){
		for(j=1;j<=number;j++){
			outfile6<<matrixAU[i][j]<<" ";
		}
		outfile6<<endl;		
	}
		outfile6.close();			
		
	cout<<"all done.";
	//free (article);
}	

#endif
