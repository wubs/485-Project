PA6 
===

Step 1. MapReduce Indexer 
----------------------

#### -- Input `../hadoop/dataset/prod`, which contains `mining.articles.xml` 
(equivalent path `pa6/hadoop/dataset/prod`)

####  -- Output `pa6/map_reduce_indexer/inv4_out/part-r-00000`

**Base Directory** `pa6/map_reduce_indexer` **This is an ant project**

* `ant` to compile

* `inv_all.sh` to run

(Change `FOLDER="../hadoop/dataset/prod"` if data is in other directory) 



Step 2. Page Rank 
----------------------

#### -- Input `../hadoop/dataset/mining.edges.xml`
(equivalent path `pa6/hadoop/dataset/mining.edges.xml`)


#### -- Output `pa6/pagerank/output.txt`

**Base Directory** `pa6/pagerank`

* `javac Pagerank.java` to compile

* `java  Pagerank  [d value]  [-k  num iterations  or - converge maxchange] [input file name] [output file name] ` to run

Example:
```
java Pagerank 0.85 –converge 0.01 ../hadoop/dataset/mining.edges.xml output.txt
```

A detailed help at `pa6/pagerank/readme.txt`



Step 3. InvertedIndex 
----------------------

#### -- Input `pa6/pagerank/output.txt` & `pa6/map_reduce_indexer/inv4_out/part-r-00000`


**Base Directory** `pa6/indexer_server` **This is an ant project**

`ant compile` will compile the code

`ant run_server` will run the indexer server

** If you followed the previous steps, you don't need to change parameters (input file path) **

```
<target name="run_server" depends="dist">
  <java maxmemory="3248m" fork="true" classname="${server-class}">
    <classpath>
      <path refid="classpath"/>
      <path location="${dist}/pa6.jar"/>
    </classpath>

    <!-- port -->
    <arg value="9010" /> 

    <!-- inverted index file -->
    <arg value="../map_reduce_indexer/inv4_out/part-r-00000" />

    <!-- page_rank file -->
    <arg value="../pagerank/output.txt" />
  </java>
</target>
```

Step 4. Front end.
------------------

`search.html` is the entry point.


When clicking search, query and w will be sent via a ajax POST to `/search_action.php`.

`/search_action.php` will use `server.php` to connect to `indexer server` (Java)






