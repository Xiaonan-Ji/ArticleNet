//Parser.h
// Author: Xiaonan Ji, Date: 12/8/2012
/*Parse one file (MEDLINE format for on article) according to different article features, i.e., title, abstract, MeSH, author, pubtype*/
#ifndef _PARSER_H_
#define _PARSER_H_

#include <iostream>
#include <vector>
#include <map>
#include <list>
#include <algorithm>
#include <string>
#include <fstream>
#include <sstream>
#include <cctype>
#include <stdio.h>
#include <ctype.h> 
#include <math.h>

using namespace std;

/* ****************************************************************************************************************************************** */
/*----------------------------------------------------The Parser of one article start here-----------------------------------------------------*/
class Parser{
        string PMID;
	    vector<string> Title;
	    vector<string> Abstract;
		
	    vector<string> Author; // consider an author name as a single word, filter firstname, only use lastname (not applied)
		vector<vector<string> > Author2; // consider an author name as a vector of string, IN USE and Applied.
		vector<string> Author3; // consider an author name as a single word, "lastname, firstname" format (for the display of author name) 
		vector<string> Author4; // consider an author name as a single word, not filter firstname, use full name (combine into one word) (not applied)
		int num_Author;
		
	    vector<string> PubType; // consider one pubtype as a single word(combine into one word) 
		vector<vector<string> > PubType2; // consider one pubtype as a multiple word phrase
		int num_PubType;
		
		vector<string> MeshTerm;
		int num_MeshTerm;
		
		map<string, int> cnt; // frequency map of abstract
		map<string, int> cntTI; // frequency map of title
		map<string, int> cntMH; // frequency map of MeSH
		map<string, int> cntAU; // frequency map of author
		
	public:
	    Parser (const char *); // Input file name is the parameter
		Parser ();
		
		// Display a brief description of one article
		void Output ();
		void Output1();
		void Output2(); //revised format for authors name
		
		// caculate/output the frequency of each word/phrase
		void Cal_Frequency (); 
		void Output_Frequency (); 
		void Cal_Frequency_MH ();
		void Output_Frequency_MH ();
		void Cal_Frequency_TI ();
		void Output_Frequency_TI ();
		void Cal_Frequency_AU ();
		void Output_Frequency_AU ();
		
		// Filter common words/stop-words; Filter numbers...
		void Filter (vector<string>); // general
		void FilterTI (vector<string>); 
		void Filter_Number (); // general
		void Filter_Number_MH (); 
		void Filter_Number_TI (); 
		
		// get the well formed frequency map
		map<string, int> getlist(); //abstract
		map<string, int> getMeshTerm();
		map<string, int> getTI(); 
		map<string, int> getAU(); // based on lastname
		vector<vector<string> > getAU2(); // based on full name full names
		vector<string> getPubType (); // based on combine single word i.e. systematicreview
		vector<vector<string> > getPubType2(); // based on full pubtype name (multiple word) i.e. systematic review
		
		// get each dimension of relevance vector, i.e. pubtype score, keywords score(applied) and author score.
		double getTypeScore (vector<string>); //for single word
		double getTypeScore2 (vector<vector<string> >, int); //can handle multiple words phrases
		double getKeyScore (vector<string>); //match for single word format, i.e. qualityoflife (not applied)
		double getKeyScore2 (vector<string>, string); //hard coded multiple words phrases (not applied)
		double getKeyScore3 (vector<vector<string> >, int);  //can handle multiple words phrases, i.e. quality of life (in use)
		double getAuthorScore (vector<string>); //match for single word, lastname match (not applied)
		double getAuthorScore2 (vector<vector<string> >, int); //can handle multiple words phrases, full name match (in use)
		
		// get pudmed id/title
		void getid (char * id);
		void JustTI (string & title);
};

Parser::Parser (){
}

Parser::Parser (const char* path){
    ifstream infile;
	infile.open(path);
	string word;
	vector<string> words;
	string punctuation("\" ,£¬¡¾¡¿./;'\\¡£¡¢£¡@#£¤¡­_&:£º*£¨£©+||+_)(*&^%$#@!~={}[]<>¡¶¡·\?£¿");
	string punctuation2("-");
	string bigletter("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
	string::size_type pos=0;
	int i;

	if(!infile){
		cout<<"Can not open file:"<<path <<endl;
		exit(1);
	}
	
//read in the file into a wordlist("words") and filter all punctuations
	while( infile >> word){
        while((pos = word.find_first_of(punctuation,pos))!= string::npos){
			word.erase(pos,1);
        }
		pos=0;
		if(word[0]!='\0'){
			words.push_back (word);
        }
	}
	infile.close();
  
//idenify different parts (different article features)
//get pmid
    vector<string>::iterator it;
	vector<string>::iterator tmp;
	
	it = find(words.begin(),words.end(),"PMID-");
	if(it!=words.end()){
		++it;
		PMID = *it;
	}

//get title
	vector<string>::iterator flag; // a pointer indicates what we have read so far
	vector<string>::iterator temp;
	// split a word with "-" into two words (applied)
	char longword[60];
	char* splitword;
	flag = it;
    it = find(flag,words.end(),"TI");
	if(it!=words.end()&&*(++it)=="-"){
		++it;
		tmp=it+1;
		while(((*it!="PG")&&(*it!="AB")&&(*it!="AD")) ||(*tmp!="-")){
			// erase "-" and combine into one word (not applied)
			/*while((pos = (*it).find_first_of(punctuation2,pos))!= string::npos){
				(*it).erase(pos,1);
			}
			pos=0;*/
			
			// split a word with "-" into two words (applied)
			strcpy(longword, (*it).c_str());
			splitword = strtok (longword,"-");
			while (splitword!= NULL)
			{
				if(splitword[0]!='\0'){
					string newword = splitword;
					Title.push_back(newword);
				}
				splitword = strtok (NULL, "-");
			}
			++it;
			tmp=it+1;
		}
		flag = it;
	}
	else it = flag;

//get abstract
	it = find(flag,words.end(),"AB");
	if(it!=words.end()&&*(++it)=="-"){
		++it;
		tmp=it+1;
		while(!((*it=="AD")||(*it=="CI")||(*it=="LA")||(*it=="FAU")||(*it=="AU")) || (*tmp!="-")){
			//--it;
			while((pos = (*it).find_first_of(punctuation2,pos))!= string::npos){
				(*it).erase(pos,1);
			}
			pos=0;
			if((*it)[0]!='\0'){
				Abstract.push_back(*it);}
			++it;
			tmp=it+1;
		}
		flag = it;
	}
	else{
		Abstract.push_back("empty");
		it = flag;
	}

//get authors (in latname: Auhtor; in full name: Author4; in format: Author3; in vector: Author2)
	num_Author=(int) count(flag, words.end(), "AU");
	int num_Author1=(int) count(flag, words.end(), "FAU"); // not applied

	if(num_Author==0){
		Author.push_back("null");
		Author3.push_back("null");
		Author4.push_back("null");
		vector<string> row;
		row.push_back("null");
		Author2.push_back(row);	
	}
	
	string wordau;
	int visit=0;
    for(i=0;i<num_Author;i++){
		vector<string> row;
	    it = find(flag,words.end(),"AU");
		++it;
		if(*it!="-") {
			continue;
		}
	    ++it;
		tmp=it+1;
		while(((*it!="FAU")&&(*it!="LA")&&(*it!="AU")&&(*it!="CN")) || (*tmp!="-")){
			row.push_back(*it);
			Author4.push_back(*it);
			if(strlen((*it).c_str())>=2){
				if(visit==0){
					wordau=(*it)+",";
					Author3.push_back(wordau);
					visit=1;
				}
				else{
					Author3.push_back(*it);
				}				
				Author.push_back(*it);
			}
			else{
				Author3.push_back(*it);
			}
			++it;
			tmp=it+1;
			if(*it=="AU" && *tmp!="-") {
				num_Author--;
			}
		}
		Author2.push_back(row);
		visit=0;
		Author3.push_back(",");
		flag = it;
	}
	
//get pubtype
	flag = it;
	num_PubType=(int) count(flag, words.end(), "PT");
	for(i=0;i<num_PubType;i++){
		vector<string> row;
	    it = find(flag,words.end(),"PT");
		++it;
		if(*it!="-") {
			continue;
		}
		++it;
		tmp=it+1;
		while(((*it!="PL")&&(*it!="TT")&&(*it!="DEP")&&(*it!="PT")) || (*tmp!="-")){
		    PubType.push_back(*it);
			row.push_back(*it);
			++it;
			tmp=it+1;
			if(*it=="PT" && *tmp!="-") {
				num_PubType--;
			}
		}
		flag = it;
		PubType2.push_back(row);
	}
	
//get mesh term
	flag = it;
	num_MeshTerm=(int) count(flag, words.end(), "MH");
	if(num_MeshTerm==0) MeshTerm.push_back("empty");
	for(i=0;i<num_MeshTerm;i++){
	    it = find(flag,words.end(),"MH");
		++it;
		if(*it!="-") {
			continue;
		}
	    ++it;
		while((*it!="EDAT-")&&(*it!="PMC")&&(*it!="MHDA-")&&(*it!="OID")&&(*it!="MH")&&(*it!="RF")){
			while((pos = (*it).find_first_of(punctuation2,pos))!= string::npos){
				(*it).erase(pos,1);
			}
			pos=0;
		    MeshTerm.push_back(*it);
			++it;
		}
		flag = it;
	}
}
	
//output one article with refined structure (full info)
void Parser::Output(){
	cout<< "display the parsed content of the aiticle..." <<endl;
    vector<string>::iterator it;
    cout<< "PMID: ";
	cout<< PMID<< endl;
	cout<< endl;
	cout<< "Title: ";
	for (it=Title.begin();it<Title.end();it++)
	    cout<< *it<< " ";
	cout<< endl;
	cout<< endl;
	cout<< "Abstract: ";
	for (it=Abstract.begin();it<Abstract.end();it++)
	    cout<< *it<< " ";
	cout<< endl;
	cout<< endl;
	cout<< "Authors: ";
	for (it=Author.begin();it<Author.end();it++)
	    cout<< *it<< " ";
	cout<< endl;
	cout<< endl;
	cout<< "PubType: ";
	for (it=PubType.begin();it<PubType.end();it++)
	    cout<< *it<< " ";
	cout<< endl;
	cout<< endl;
	cout<< "MeshTerm: ";
	for (it=MeshTerm.begin();it<MeshTerm.end();it++)
	    cout<< *it<< " ";
	cout<< endl;
} 

//output the class-object with re-fined structure (part info)
void Parser::Output1(){
	//cout<< "display the parsed content of the aiticle..." <<endl;
    vector<string>::iterator it;
	cout<<"<h9>"<<endl;
    cout<< "PMID: ";
	cout<< PMID<< endl;
	cout<< endl;
	cout << "<br />";
	cout<< "Title: ";
	for (it=Title.begin();it<Title.end();it++)
	    cout<< *it<< " ";
	cout<< endl;
	cout<< endl;
	cout << "<br />";
	cout<< "Authors: ";
	for (it=Author.begin();it<Author.end();it++)
	    cout<<*it<<" ";
	cout<< endl;
	cout<< endl;
	cout << "<br />";
	cout<<"</h9>"<<endl;
} 

//revised output, which has adjusted the format of authors("lastname, firstname"), applied
void Parser::Output2(){
	//cout<< "display the parsed content of the aiticle..." <<endl;
    vector<string>::iterator it;
	cout<<"<h9>"<<endl;
    cout<< "PMID: ";
	cout<< PMID<< endl;
	cout<< endl;
	cout << "<br />";
	cout<< "Title: ";
	for (it=Title.begin();it<Title.end();it++)
	    cout<< *it<< " ";
	cout<< endl;
	cout<< endl;
	cout << "<br />";
	cout<< "Authors: ";
	for (it=Author3.begin();it<Author3.end();it++){
		if(*it!=","){
			cout<<"<u>"<<*it<<" "<<"</u>";
		}
		else{cout<<"&nbsp&nbsp";}
	}
	cout<< endl;
	cout<< endl;
	cout << "<br />";
	cout<<"</h9>"<<endl;
} 


// get the frequency map of abstract
void Parser:: Cal_Frequency (){
	// all transformed to lower case
	//cout<< "caculate the word frequency list..."<< endl;
	vector<string>::iterator it1;
	string word1;
	for (it1=Abstract.begin();it1<Abstract.end();it1++){
		word1=(*it1);
		transform(word1.begin(), word1.end(), word1.begin(), ::tolower);
		(*it1)=word1;
	}
// generate the frequency map
	string word;
	vector<string>::iterator it;
	for (it=Abstract.begin(); it!=Abstract.end();it++){
		word=*it;
		++cnt[word];
	}
}

// display the frequency map of abstract
void Parser:: Output_Frequency (){
	//cout<< "display the word frequency list..."<< endl;
	map<string,int>::iterator it;
	for( it=cnt.begin(); it!=cnt.end(); it++){
		//cout<< (*it).first << " " << (*it).second << endl;
	}
	//cout << endl;
}

// filter common words/stop-words from the original frequency map
void Parser::Filter (vector<string> common){
	map<string,int>::iterator it;
	vector<string>::iterator it1;
	string word;
	int flag=0;

	for (it=cnt.begin();it!=cnt.end();it++){
		if(flag==1 && it!=cnt.begin()){
			--it;
			flag=0;
		}
		word=(*it).first;
		it1 = find(common.begin(), common.end(), word);
		if(it1>=common.begin() && it1<common.end()){
			cnt.erase(it);
			flag=1;
		}
	}
	it=cnt.begin();
	if((*it).first=="-") cnt.erase(it);
}


//filter all numbers
void Parser::Filter_Number (){
	map<string,int>::iterator it;
	string word;
	int i;
	for (it=cnt.begin();it!=cnt.end();it++){
		word=(*it).first;
		i=atoi(word.c_str());
		if(i!=0){
			cnt.erase(it);
			it--;
		}
	}
	for (it=cnt.begin();it!=cnt.end();it++){
		word=(*it).first;
		i=atoi(word.c_str());
		if(i!=0){
			cnt.erase(it);
			it--;
		}
	}
	it=cnt.begin();
	if((*it).first=="0") cnt.erase(it);
	
	word=(*it).first;
	i=atoi(word.c_str());
	if(i!=0){
		cnt.erase(it);
	}
}

// get the frequency map of MeSH
void Parser:: Cal_Frequency_MH(){
    // all change to lower case
	//cout<< "caculate the word frequency list of MeshTerm..."<< endl;
	vector<string>::iterator it1;
	string word1;
	for (it1=MeshTerm.begin();it1<MeshTerm.end();it1++){
		word1=(*it1);
		transform(word1.begin(), word1.end(), word1.begin(), ::tolower);
		(*it1)=word1;
	}
// generate the frequency map
	string word;
	vector<string>::iterator it;
	for (it=MeshTerm.begin(); it!=MeshTerm.end();it++){
		word=*it;
		++cntMH[word];
	}
}
void Parser::Filter_Number_MH (){
	map<string,int>::iterator it;
	string word;
	int i;
	for (it=cntMH.begin();it!=cntMH.end();it++){
		word=(*it).first;
		i=atoi(word.c_str());
		if(i!=0){
			cntMH.erase(it);
			it--;
		}
	}
	for (it=cntMH.begin();it!=cntMH.end();it++){
		word=(*it).first;
		i=atoi(word.c_str());
		if(i!=0){
			cntMH.erase(it);
			it--;
		}
	}
	it=cntMH.begin();
	if((*it).first=="0") cntMH.erase(it);
	
	word=(*it).first;
	i=atoi(word.c_str());
	if(i!=0){
		cntMH.erase(it);
	}
}

// display the frequency map OF MeSH
void Parser:: Output_Frequency_MH (){
	//cout<< "display the word frequency list of MeshTerm..."<< endl;
	map<string,int>::iterator it;
	for( it=cntMH.begin(); it!=cntMH.end(); it++){
		//cout<< (*it).first << " " << (*it).second << endl;
	}
	//cout << endl;
}

// get the frequency of author
void Parser:: Cal_Frequency_AU(){
    // all change to lower case
	//cout<< "caculate the word frequency list of Author..."<< endl;
	vector<string>::iterator it1;
	string word1;
	for (it1=Author.begin();it1<Author.end();it1++){
		word1=(*it1);
		transform(word1.begin(), word1.end(), word1.begin(), ::tolower);
		(*it1)=word1;
	}
// generate the frequency map
	string word;
	vector<string>::iterator it;
	for (it=Author.begin(); it!=Author.end();it++){
		word=*it;
		++cntAU[word];
	}
}

// display the frequency map of author
void Parser:: Output_Frequency_AU (){
	cout<< "display the word frequency list of authors..."<< endl;
	map<string,int>::iterator it;
	for( it=cntAU.begin(); it!=cntAU.end(); it++){
		cout<< (*it).first << " " << (*it).second << endl;
	}
	cout << endl;
}

// caculate the frequency of title
void Parser:: Cal_Frequency_TI(){
	// all change to lower case
	//cout<< "caculate the word frequency list of Title..."<< endl;
	vector<string>::iterator it1;
	string word1;
	for (it1=Title.begin();it1<Title.end();it1++){
		word1=(*it1);
		transform(word1.begin(), word1.end(), word1.begin(), ::tolower);
		(*it1)=word1;
	}
// generate the frequency map
	string word;
	vector<string>::iterator it;
	for (it=Title.begin(); it!=Title.end();it++){
		word=*it;
		++cntTI[word];
	}
}
void Parser::Filter_Number_TI (){
	map<string,int>::iterator it;
	string word;
	int i;
	for (it=cntTI.begin();it!=cntTI.end();it++){
		word=(*it).first;
		i=atoi(word.c_str());
		if(i!=0){
			cntTI.erase(it);
			it--;
		}
	}
	for (it=cntTI.begin();it!=cntTI.end();it++){
		word=(*it).first;
		i=atoi(word.c_str());
		if(i!=0){
			cntTI.erase(it);
			it--;
		}
	}
	it=cntTI.begin();
	if((*it).first=="0") cntTI.erase(it);
	
	word=(*it).first;
	i=atoi(word.c_str());
	if(i!=0){
		cntTI.erase(it);
	}
}

// display the frequency map of title
void Parser:: Output_Frequency_TI (){
	//cout<< "display the word frequency list of MeshTerm..."<< endl;
	map<string,int>::iterator it;
	for( it=cntTI.begin(); it!=cntTI.end(); it++){
		//cout<< (*it).first << " " << (*it).second << endl;
	}
	//cout << endl;
}


// filter common words/stop-words for title
void Parser::FilterTI (vector<string> common){

	map<string,int>::iterator it;
	vector<string>::iterator it1;
	string word;
	int flag=0;

	for (it=cntTI.begin();it!=cntTI.end();it++){
		if(flag==1 && it!=cntTI.begin()){
			--it;
			flag=0;
		}
		word=(*it).first;
		it1 = find(common.begin(), common.end(), word);
		if(it1>=common.begin() && it1<common.end()){
			cntTI.erase(it);
			flag=1;
		}
	}
	it=cntTI.begin();
	if((*it).first=="-") cntTI.erase(it);
}



void Parser:: getid (char * id){
	strcpy(id, PMID.c_str());
}

void Parser:: JustTI (string & title){
	vector<string>::iterator it;
	for(it=Title.begin(); it<Title.end(); it++){
		title.append(*it);
		title.append(" ");
	}
}

map<string,int> Parser::getlist (){
	return cnt;
}

vector<string> Parser::getPubType (){
	return PubType;
}

// get full pubtype names
vector<vector<string> > Parser::getPubType2 (){
	return PubType2;
}

map<string,int> Parser::getMeshTerm (){
	return cntMH;
}

map<string,int> Parser::getAU (){
	return cntAU;
}

// get seperated full names
vector<vector<string> > Parser::getAU2 (){
	return Author2;
}

map<string,int> Parser::getTI (){
	return cntTI;
}	

// get relevance vector
// old version of type score, single word match, not applied
double Parser::getTypeScore (vector<string> type){
	vector<string>::iterator it;
	vector<string>::iterator it1;
	string word;
	int time = 0;
	double score = 0;
	for (it=PubType.begin();it!=PubType.end();it++){
		word=(*it);
		it1 = find(type.begin(), type.end(), word);
		if(it1>=type.begin() && it1<type.end()){
			time+=1;
			it1=type.end();
		}
	}
	
	if(1<=time && time<=3) score=0.1;
	else if(4<=time && time<=6) score=0.2;
	else if(time>6) score=0.3;
	else if(time==0) score=0;

	return score;
}

// new version of type score, full publishtype name (multiple words) match, applied
double Parser::getTypeScore2 (vector<vector<string> > types, int size){
	vector<string>::iterator it;
	vector<string>::iterator it1;
	vector<vector<string> >::iterator it2;
	string word;
	vector<vector<string> > PT;
	for(it2=PubType2.begin();it2<PubType2.end();it2++){
		vector<string> row;
		for(it1=(*it2).begin();it1<(*it2).end();it1++){
			word=*it1;
			transform(word.begin(), word.end(), word.begin(), ::tolower);
			row.push_back(word);
		}
		PT.push_back(row);
	}

	int time = 0;
	double score = 0;
	int i=0;
	int count=0;
	for (i=0; i<size; i++){
		for(it2=PT.begin(); it2!=PT.end(); it2++){
			it=types[i].begin();
			it1=(*it2).begin();
			while(it!=types[i].end() && it1!=(*it2).end()){
				if((*it)==(*it1)){
					it++;
					it1++;
				}
				else {break;}
			}
			if(it==types[i].end()){
				time++;
			}
		}
	}
	if(1==time) score=0.1;
	else if(time==2) score=0.2;
	else if(time>=3) score=0.3;
	else if(time==0) score=0;
	return score;
}

// old version for keywords score, single word match
double Parser::getKeyScore (vector<string> keys){
	vector<string>::iterator it;
	vector<string>::iterator it1;
	string word;
	int time = 0;
	double score = 0;
	for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		it1 = find(keys.begin(), keys.end(), word);
		if(it1>=keys.begin() && it1<keys.end()){
			time+=1;
			it1=keys.end();
		}
	}
	
	score = time;
	return score;
}

// old verison key-score for research models, need to provide topic name (hard coded phrases), not applied
double Parser::getKeyScore2 (vector<string> keys, string topic){
	vector<string>::iterator it;
	vector<string>::iterator it1;
	string word;
	int time = 0;
	double score = 0;
	for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		it1 = find(keys.begin(), keys.end(), word);
		if(it1>=keys.begin() && it1<keys.end()){
			time+=1;
			it1=keys.end();
		}
	}
	
	for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if(word=="quality"&&(it!=Abstract.end()-2)&&(it!=Abstract.end()-1)){
			it++;
			it1=it+1;
			if((*it)=="of" && (*it1)=="life"){
				time = time+1;
				it++;
				cout<<"find quality of life"<<endl;
			}
		}
		if(word=="life"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="quality"){
				time = time+1;
				cout<<"find life quality"<<endl;
			}
		}
		if(word=="dry"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="mouth"){
				time = time+1;
				cout<<"find dry mouth"<<endl;
			}
		}
		if(word=="mental"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="state"){
				time = time+1;
				cout<<"find mental state"<<endl;
			}
		}
	}
	
	if(topic=="UrinaryIncontinence"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if(word=="adverse"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="effect"||(*it)=="effects"){
				time = time+1;
				cout<<"find adverse effect"<<endl;
			}
		}
		}
	}
	
	if(topic=="NSAIDS"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if(word=="soft"&&(it!=Abstract.end()-2)&&(it!=Abstract.end()-1)){
			it++;
			it1=it+1;
			if((*it)=="tissue" && (*it1)=="pain"){
				time = time+1;
				it++;
				cout<<"find soft tissue pain"<<endl;
			}
		}
		if(word=="soft-tissue"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="pain"){
				time = time+1;
				cout<<"find soft-tissue pain"<<endl;
			}
		}
		if(word=="low"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="backpain"){
				time = time+1;
				cout<<"find low backpain"<<endl;
			}
		}
		if(word=="functional"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="status"){
				time = time+1;
				cout<<"find functional status"<<endl;
			}
		}
		if(word=="adverse"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="effect"||(*it)=="effects"){
				time = time+1;
				cout<<"find adverse effect"<<endl;
			}
		}
		}
	}
	
		if(topic=="Estrogens"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);	
		if(word=="adverse"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="effect"||(*it)=="effects"){
				time = time+1;
				cout<<"find adverse effect"<<endl;
			}
		}
		if(word=="sleep"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="disturbance"){
				time = time+1;
				cout<<"find sleep disturbance"<<endl;
			}
		}
		if(word=="night"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="sweat"||(*it)=="sweats"){
				time = time+1;
				cout<<"find night sweats"<<endl;
			}
		}
		if(word=="sexual"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="function"||(*it)=="functions"){
				time = time+1;
				cout<<"find sexual function"<<endl;
			}
		}
		if(word=="mood"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="change"||(*it)=="changes"){
				time = time+1;
				cout<<"find mood changes"<<endl;
			}
		}
		if(word=="urogenital"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="atrophy"){
				time = time+1;
				cout<<"find urogenitalatrophy"<<endl;
			}
		}
		if(word=="bone"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="density"){
				time = time+1;
				cout<<"find bone density"<<endl;
			}
		}
		
		if(word=="endometrial"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="hypertrophy"){
				time = time+1;
				cout<<"find endometrial hypertrophy"<<endl;
			}
		}
		if(word=="breast"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="tenderness"){
				time = time+1;
				cout<<"find Breast tenderness"<<endl;
			}
		}
		}
	}
	
	if(topic=="OralHypoglycemics"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if(word=="oral"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="hypoglycemic"){
				time = time+1;
				cout<<"find oral hypoglycemic"<<endl;
			}
		}
		if(word=="sdzdjn"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="608"){
				time = time+1;
				cout<<"find sdzdjn 608"<<endl;
			}
		}
		if(word=="adverse"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="effect"||(*it)=="effects"){
				time = time+1;
				cout<<"find adverse effect"<<endl;
			}
		}
		}
	}
	
		if(topic=="Antihistamines"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if(word=="nasal"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="congestion"){
				time = time+1;
				cout<<"find nasal congestion"<<endl;
			}
		}
		if(word=="skin"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="irritations"||(*it)=="irritation"){
				time = time+1;
				cout<<"find skin irritations"<<endl;
			}
		}
		
		if(word=="urinary"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="retention"){
				time = time+1;
				cout<<"find urinary retention"<<endl;
			}
		}
		}
	}
	
	if(topic=="AtypicalAntipsychotics"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if((word=="attention"||word=="attentions")&&(it!=Abstract.end()-2)&&(it!=Abstract.end()-1)){
			it++;
			it1=it+1;
			if((*it)=="deficit" && (*it1)=="hyperactivity"){
				time = time+1;
				it++;
				cout<<"find attention deficit hyperactivity"<<endl;
			}
		}
		if(word=="oppositional"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="defiant"){
				time = time+1;
				cout<<"find oppositional defiant"<<endl;
			}
		}
		if(word=="conduct"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="disorder"){
				time = time+1;
				cout<<"find conduct disorder"<<endl;
			}
		}
		if(word=="disruptive"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="behavior"||(*it)=="behaviors"){
				time = time+1;
				cout<<"find disruptive behavior"<<endl;
			}
		}
		if(word=="weight"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="gain"){
				time = time+1;
				cout<<"find weight gain"<<endl;
			}
		}
		if(word=="adverse"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="effect"||(*it)=="effects"){
				time = time+1;
				cout<<"find adverse effect"<<endl;
			}
		}
		}
	}

	if(topic=="Opioids"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if(word=="low"&&(it!=Abstract.end()-2)&&(it!=Abstract.end()-1)){
			it++;
			it1=it+1;
			if((*it)=="back" && (*it1)=="pain"){
				time = time+1;
				it++;
				cout<<"find low back pain"<<endl;
			}
		}
		if(word=="transdermal"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="fentanyl"){
				time = time+1;
				cout<<"find Transdermal fentanyl"<<endl;
			}
		}
		if(word=="adverse"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="effect"||(*it)=="effects"){
				time = time+1;
				cout<<"find adverse effect"<<endl;
			}
		}
		}
	}	
	
	if(topic=="ACEInhibitors"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if(word=="low"&&(it!=Abstract.end()-2)&&(it!=Abstract.end()-1)){
			it++;
			it1=it+1;
			if((*it)=="back" && (*it1)=="pain"){
				time = time+1;
				it++;
				cout<<"find low back pain"<<endl;
			}
		}
		if(word=="heart"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="failure"||(*it)=="disease"){
				time = time+1;
				cout<<"find heart failure/disease"<<endl;
			}
		}
		if(word=="blood"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="pressure"){
				time = time+1;
				cout<<"find blood pressure"<<endl;
			}
		}
		if(word=="myocardial"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="infarction"){
				time = time+1;
				cout<<"find myocardial infarction"<<endl;
			}
		}
		if(word=="adverse"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="effect"||(*it)=="effects"){
				time = time+1;
				cout<<"find adverse effect"<<endl;
			}
		}
		}
	}	
	
	if(topic=="CalciumChannelBlockers"){
		for (it=Abstract.begin();it!=Abstract.end();it++){
		word=(*it);
		if(word=="calcium"&&(it!=Abstract.end()-2)&&(it!=Abstract.end()-1)){
			it++;
			it1=it+1;
			if((*it)=="channel" && (*it1)=="blocker"){
				time = time+1;
				it++;
				cout<<"find calcium channel blocker"<<endl;
			}
		}
		
		if(word=="heart"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="failure"||(*it)=="disease"){
				time = time+1;
				cout<<"find heart failure/disease"<<endl;
			}
		}
		
		if(word=="myocardial"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="infarction"){
				time = time+1;
				cout<<"find myocardial infarction"<<endl;
			}
		}
		if(word=="adverse"&&it!=Abstract.end()-1){
			it++;
			if((*it)=="effect"||(*it)=="effects"){
				time = time+1;
				cout<<"find adverse effect"<<endl;
			}
		}
		}
	}	

	score = time;
	return score;
}

// new version keywords score for multiple words phrase match, applied
double Parser::getKeyScore3 (vector<vector<string> > keys, int size){
	vector<string>::iterator it;
	vector<string>::iterator it1;
	vector<string>::iterator tag;
	string word;
	vector<string> AB;
	for(it=Abstract.begin();it<Abstract.end();it++){
		word=*it;
		transform(word.begin(), word.end(), word.begin(), ::tolower);
		AB.push_back(word);	
	}
	int time = 0;
	double score = 0;
	int i=0;
	for (i=0; i<size; i++){
		it1=keys[i].begin();
		word=*it1;
		tag=AB.begin();
		it=AB.begin();
		while(it>=AB.begin() && it<AB.end()){
			it1=keys[i].begin();
			it=find(tag, AB.end(), (*it1));
			if(it>=AB.begin() && it<AB.end()){
				while(it1!=keys[i].end() && it!=AB.end()){
					if((*it)==(*it1)){
						it++;
						it1++;
					}
					else{ break;}
				}
				if(it1==keys[i].end()){
					time=time+1;
				}
			tag=it;
			}
		}
	}

	score = time;
	return score;

}

// old version for author acore, single word match (lastname match), not applied
double Parser::getAuthorScore (vector<string> authors){
	vector<string>::iterator it;
	vector<string>::iterator it1;
	string word;
	int time = 0;
	double score = 0;
	for (it=Author.begin();it!=Author.end();it++){
		word=(*it);
		transform(word.begin(), word.end(), word.begin(), ::tolower);
		(*it)=word;
		it1 = find(authors.begin(), authors.end(), word);
		if(it1>=authors.begin() && it1<authors.end()){
			time+=1;
			it1=authors.end();
		}
	}

	score = time;
	return score;
}

// new version of author score, full name match, applied
double Parser::getAuthorScore2 (vector<vector<string> > authors, int size){
	vector<string>::iterator it;
	vector<string>::iterator it1;
	vector<vector<string> >::iterator it2;
	string word;
	vector<vector<string> > AU;
	for(it2=Author2.begin();it2<Author2.end();it2++){
		vector<string> row;
		for(it1=(*it2).begin();it1<(*it2).end();it1++){
			word=*it1;
			transform(word.begin(), word.end(), word.begin(), ::tolower);
			row.push_back(word);
		}
		AU.push_back(row);
	}
	
	int time = 0;
	double score = 0;
	int i=0;
	for (i=0; i<size; i++){
		for(it2=AU.begin(); it2<AU.end(); it2++){
			it=authors[i].begin();
			it1=(*it2).begin();
			while(it<authors[i].end() && it1<(*it2).end() ){
				if(*it==*it1){
					it++;
					it1++;
				}
				else {break;}
			}
			if(it==authors[i].end()){
				time++;
			}
		}
	}

	score = time;
	return score;
}
	
#endif
