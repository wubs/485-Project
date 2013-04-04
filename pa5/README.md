PA5
===

file is located at 
eecs485-08.eecs.umich.edu
/home/wubs/pa5/pg5largeoutput.txt 


Datasets are ignored by git.

Please download `hits.net`, `hits_inindex` and place them under `pa5/hits`.

Similarly download `small.net`, `large.net` and place them under `pa5/pagerank`.



1. Pagerank
-----------

### Original README is in /pagerank/ReadMe.pdf
### a. how to run
To Compile:
```
cd pagerank/src
javac Pagerank.java

```
To Run:
```
cd pagerank/src
java Pagerank [d value] [-k numiterations or -converge maxchange] [input filename] [output filename]

```
### b. notes


2. Hits
-------

### a. how to run

To Compile the default ant job should do the work.
```
ant 
```

To execute
```
cd hits
java -Xms500m -Xmx2000m -jar dist/Hits.jar 20 -converge 5 "ability" hits.net hits_invindex output.data
java -Xms500m -Xmx2000m -jar dist/Hits.jar 20 -k 10 "ability" hits.net hits_invindex output.data
```

### b. notes


3. Appendix
-----------

The project structure (initial look) 

```
../pa5
├── README.md
├── hits
│   ├── build
│   │   └── classes
│   │       └── Hits.class
│   ├── build.xml
│   ├── dist
│   │   └── Hits.jar
│   ├── hits.net
│   ├── hits_invindex
│   ├── lib
│   │   └── readme.md
│   └── src
│       └── Hits.java
└── pagerank
    ├── build
    │   └── classes
    │       └── Pagerank.class
    ├── build.xml
    ├── dist
    │   └── Pagerank.jar
    ├── large.net
    ├── lib
    │   └── readme.md
    ├── small.net
    └── src
        └── Pagerank.java
```
