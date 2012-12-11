// evalue1.cpp
// Author: Xiaonan Ji, Date: 12/8/2012
// Read in article network, recommend articles and generate traverse-recall graph.
// Applied for model evaluation.
 
#include <iostream>
#include <vector>
#include <map>
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

int main(int argc, char* argv[]){

	if (argc < 14) {
        printf("please enter enough parameters.");
        exit(-1);
    }
	int num = atoi(argv[1]);
	int step = atoi(argv[2]);
	int size = atoi(argv[3]);
	int IIsize = atoi(argv[4]);
	double keyweight = atof(argv[6]);
	double typeweight = atof(argv[7]);
	double abweight = atof(argv[8]);
	double mhweight = atof(argv[9]);
	double tiweight = atof(argv[10]);
	double auweight1 = atof(argv[11]);
	double auweight2 = atof(argv[12]);
	int perround = atoi(argv[13]);
	char path[80];
	strcpy(path, argv[5]);
	int perform = 0;	

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
	int estimatestep = (step/perround)+2; 
	
	double recall[estimatestep];
	double precision[estimatestep];
	double F_measure[estimatestep];
	vector<vector<double> > matrix(size, vector<double>(size)); 
	vector<vector<double> > matrixMH(size, vector<double>(size)); 
	vector<vector<double> > matrixTI(size, vector<double>(size)); 
	vector<vector<double> > matrixAU(size, vector<double>(size)); 
	
	vector<vector<double> >::iterator itm;
	vector<double>::iterator itr;
	int i,j=1;
	
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
	
	vector<int> IIlist;
	vector<int> EElist;
	map<int,double> rest;
	for(i=1;i<size;i++){
		rest[i]=0;
	}
	
	int m,n=1;
	int ID1,ID2;
	double highscore=0;
	int recommandID = 0;
	
	double weightAB = 1;
	double weightType = 1;
	double weightKey = 0.015;
	double weightMH = 0.5;
	double weightTI = 0.6;
	double weightAU1 = 0.5;
	double weightAU2 = 0.05;

	weightType = weightType*typeweight;
	weightKey = weightKey*keyweight;
	weightMH = weightMH*mhweight;
	weightTI = weightTI*tiweight;
	weightAU1 = weightAU1*auweight1;
	weightAU2 = weightAU2*auweight2;
	weightAB = weightAB*abweight;
	
	vector<int>::iterator it1;
	vector<int>::iterator it2;
	map<int,double>::iterator itrest;
	int numII=0;
	int in=0;
	
// select initial article
	int firstID=size+1;
	itrest=rest.begin();
	for(itrest=rest.begin();itrest!=rest.end();itrest++){
			ID2 = (*itrest).first;
			(*itrest).second+=(TypeScore[ID2]*weightType);
			(*itrest).second+=(KeyScore[ID2]*0.02);
			(*itrest).second+=(AuthorScore[ID2]*weightAU2);
			if((*itrest).second<0) (*itrest).second=0;
	}
// find the first article
	int count=0;
	int prestep=0;
	i=0;
	j=0;
	while(IIlist.size()==0){
		cout<<"<h17>Round "<<j+1<<" (Traverse Rate: "<<(double)(j+1)/step<<"): "<<"</h17><br>"<<endl;
		prestep++;
		itrest=rest.begin();
		for(i=1; i<=perround; i++){
			for(itrest=rest.begin();itrest!=rest.end();itrest++){
				if((*itrest).second>highscore){
					highscore=(*itrest).second;
					firstID=(*itrest).first;
				}
			}
			if(firstID<=IIsize && firstID>0){
				IIlist.push_back(firstID);
				rest.erase(firstID);
				cout<<"The initial article for this iteration is: "<<firstID<<endl;
				cout<<"<br>";
				highscore=0;
				firstID=0;
				count++;
				perform++;
				numII++;
			}
			else if(firstID>IIsize){
				EElist.push_back(firstID);
				rest.erase(firstID);
				cout<<"Tried initial article for this iteration is: "<<firstID<<endl;
				cout<<"<br>";
				highscore=0;
				firstID=0;
				count++;
			}
		}
		recall[j]=(double)perform/IIsize;
		precision[j]=(double)perform/(j+1);
		F_measure[j]=(double)(2*recall[j]*precision[j])/(recall[j]+precision[j]);
		cout<<"<h21>Recall is: "<<recall[j]<<"</h21><br>"<<endl;
		cout<<"<h21>Precision is: "<<precision[j]<<"</h21><br>"<<endl;
		cout<<"<h21>F_measure is: "<<F_measure[j]<<"</h21>"<<endl;
		cout<<"<br>";
		i++;
		j++;
	}

	
	for(itrest=rest.begin();itrest!=rest.end();itrest++){
			(*itrest).second=0;
		}
	
	m=1;
	n=1;
	highscore=0;
	recommandID = 0;
	int reststep = (double)((step-count)/perround);	
	cout<<"<br>"<<endl;
	for(i=prestep;i<reststep+prestep;i++){
		cout<<"<h17>Round "<<i+1<<" (Traverse Rate: "<<(double)(i+1)/step<<"): "<<"</h17><br>"<<endl;;		
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
// edit here is penalty should be applied				
				//(*itrest).second-=(matrix[ID1][ID2]/2);
			}
			(*itrest).second+=(TypeScore[ID2]*weightType);
			(*itrest).second+=(KeyScore[ID2]*weightKey);
			(*itrest).second+=(AuthorScore[ID2]*weightAU2);
			if((*itrest).second<0) (*itrest).second=0;
		}
// find the highest score
		for(j=1; j<=perround; j++){
			for(itrest=rest.begin();itrest!=rest.end();itrest++){
				if((*itrest).second>highscore){
					highscore=(*itrest).second;
					recommandID=(*itrest).first;
				}
			}		
			rest.erase(recommandID);
		
			if(recommandID<=IIsize&&recommandID>0){
				IIlist.push_back(recommandID);
				cout<<"Recommand article: "<<recommandID<<" which is I."<<endl;
				perform = perform+1;
				cout<<"<br>";
				numII=numII+1;
			}
			if(recommandID>IIsize){
				EElist.push_back(recommandID);
				cout<<"Recommand article: "<<recommandID<<" which is E."<<endl;
				cout<<"<br>";
			}			
			highscore = 0;
			recommandID = 0;
		}
		
		recall[i]=(double)perform/IIsize;
		precision[i]=(double)perform/(i+1);
		F_measure[i]=(double)(2*recall[i]*precision[i])/(recall[i]+precision[i]);
		cout<<"<h21>Recall is: "<<recall[i]<<"</h21><br>"<<endl;
		cout<<"<h21>Precision is: "<<precision[i]<<"</h21><br>"<<endl;
		cout<<"<h21>F_measure is: "<<F_measure[i]<<"</h21>"<<endl;
		cout<<"<br>";
		for(itrest=rest.begin();itrest!=rest.end();itrest++){
			(*itrest).second=0;
		}
	}
	
// take care of the rest articles
	int left = step-count-(reststep*perround);
	int laststep=0;
	if(left>0){
		laststep++;
		cout<<"<h17>Round "<<prestep+reststep+1<<" (Traverse Rate: "<<(double)(prestep+reststep+1)/step<<"): "<<"</h17><br>"<<endl;
	}
	for(itrest=rest.begin();itrest!=rest.end();itrest++){
		recommandID=(*itrest).first;
		if(recommandID<=IIsize&&recommandID>0){
			IIlist.push_back(recommandID);
			cout<<"Recommand article: "<<recommandID<<" which is I."<<endl;
			perform = perform+1;
			cout<<"<br>";
			numII=numII+1;
		}
		if(recommandID>IIsize){
			EElist.push_back(recommandID);
			cout<<"Recommand article: "<<recommandID<<" which is E."<<endl;
			cout<<"<br>";
		}
		recommandID = 0;
		rest.erase(recommandID);
	}
	if(left>0){
		recall[reststep+prestep]=(double)perform/IIsize;
		cout<<"<h21>Recall is: "<<recall[reststep+prestep]<<"</h21><br>"<<endl;
		precision[reststep+prestep]=(double)perform/(reststep+prestep+1);
		cout<<"<h21>Precision is: "<<precision[reststep+prestep]<<"</h21><br>"<<endl;
		F_measure[reststep+prestep]=(double)(2*recall[reststep+prestep]*precision[reststep+prestep])/(recall[reststep+prestep]+precision[reststep+prestep]);
		cout<<"<h21>F_measure is: "<<F_measure[reststep+prestep]<<"</h21>"<<endl;
		cout<<"<br>";
	}

	double percent1;
	percent1 = (double)(step*100)/(size-1);
	double percent2;
	percent2 = (double)(perform*100)/IIsize;
	cout<<"<br>";
	cout<<"<hr width=80% size=4 color=#357EC7 style='filter:alpha(opacity=100,finishopacity=0,style=2)'>";
	cout<<"<h5>Performance: </h5><br>"<<endl;
	cout<<"<h4> We have iterated "<<percent1<<"% of the entire articles. </h4><br>"<<endl;
	cout<<"<h4> We have got "<<percent2<<"% of the Included articles. </h4>"<<endl;
	cout<<"<br>"<<endl;
	
// draw the traverse-recall graph
	cout<<"<canvas id='canvas' width='830' height='530'></canvas>"<<endl; 
    cout<<"<script type='application/javascript'>\n"<<"var canvas = document.getElementById('canvas');\n"<<endl;
    cout<<"if (canvas.getContext) { var ctx = canvas.getContext('2d');}\n"<<endl;
	cout<<"var img=new Image();\n"<<"img.onload=function(){ \n"<<"ctx.drawImage(img,0,0);\n"<<endl;
	cout<<"ctx.strokeStyle = 'rgba(0, 0, 200, 0.5)';\n"<<"ctx.lineWidth=3; \n"<<"ctx.beginPath();\n"<< "ctx.moveTo(33,500); \n"<<endl; 
	//int size2=size-1;
	double x,y;
	int totalstep=prestep+reststep+laststep;
	for(i=1;i<=totalstep;i++){
		x=(double)(33+(i*800)/totalstep);
		y=(double)(500-500*recall[i-1]);
		cout<<"var x="<<x<<";\n"<<"var y="<<y<<"; \n"<<"ctx.lineTo(x,y); \n"<<endl;
	} 
	cout<<"ctx.stroke(); \n"<<endl;
	cout<<"};"<<endl;
	cout<<"img.src='./pages/img/form.png';\n"<<endl;
	cout<<"</script>"<<endl;	

}
