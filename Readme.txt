Welcome to ArticleNet! This is a open-scource system for systematical reviews. 
ArticleNet is developed by the Network Laboratory of Biomedical Informatics, The Ohio State University.

The source codes consist of two parts:
(1) Server part-- The "SR-C" folder. 
(2) Web part-- The "ArticleNet" folder.

To set up the system in your worksapce, please follow the instructions below:
(1) Create a folder named "SR" under /var/www/cgi-bin/, so you will have a directory /var/www/cgi-bin/SR/; 
	then put our source codes, folder 'SR-C", under /var/www/cgi-bin/SR/, 
	the future server code directory is /var/www/cgi-bin/SR/SR-C/.
(2) On your commandline, enter directory /var/www/cgi-bin/SR/SR-C/, then type "make" to create the exectuable files.
(3) Put our provided folder "ArticleNet" under /var/www/html/.
(4) Open your broswer (google chrome or firefox is preferred), 
	and type URL "Your server address"/ArticleNet (i.e. netlab.bmi.osumc.edu/ArticleNet),
	You are all set to explore ArticleNet.

Wish you have fun with ArticleNet!