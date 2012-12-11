CFLAGS = 
RCSDIFFFLAGS = -c
CC = g++
#LIB = -lm -lc

ALL = preprocess process evalue1 evalue2 result pubmed
SRC = Makefile preprocess.cpp process.cpp evalu1e.cpp evalue2.cpp result.cpp pubmed.cpp

all: $(ALL)

clean: true
	rm preprocess process evalue1 evalue2 result pubmed

preprocess: preprocess.cpp
	$(CC) $(CFLAGS) -o preprocess preprocess.cpp $(LIB)

process: process.cpp
	$(CC) $(CFLAGS) -o process process.cpp $(LIB)

evalue1: evalue1.cpp
	$(CC) $(CFLAGS) -o evalue1 evalue1.cpp $(LIB)

evalue2: evalue2.cpp
	$(CC) $(CFLAGS) -o evalue2 evalue2.cpp $(LIB)
	
result: result.cpp
	$(CC) $(CFLAGS) -o result result.cpp $(LIB)	

pubmed: pubmed.cpp
	$(CC) $(CFLAGS) -o pubmed pubmed.cpp $(LIB)	

ci: true
	ci $(CIFLAGS) $(SRC)

true:
