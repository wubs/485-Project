To Compile:
===========

``` 
javac Pagerank.java
```

 To  Run:
=======
 
under directory  `pa6/pagerank`

```
java  Pagerank  [d value]  [-k  num iterations  or - converge maxchange] [input file name] [output file name] 
```

example:  
========

```
java -Xms 1000M -Xmx 4000M Pagerank 0.85 –k 1 Small.txt output.txt

java -Xms 1000M -Xmx 4000M Pagerank 0.85 –converge .001 Small.txt output.txt
```