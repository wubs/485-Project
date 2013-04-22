PA6 
===

**This project is completed under Chrome browser**

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


Step 4. XML Loader
--------------------

#### -- Input `../hadoop/dataset/mining.imageUrls.xml,`../hadoop/dataset/mining.infobox.xml,../hadoop/dataset/mining.category.xml,../hadoop/dataset/mining.edges.xml, ../hadoop/dataset/prod/mining.article.xml`

#### -- Output: Table Article(id, title, body), Category(id, category), Edge(id_from, id_to), imageUrl(id, url), infoBox(id, summary) with entries`

**Base Directory**  `pa6/xml_loader` **This is an ant project**
`ant` will compile and run the code

`db.cfg` contains the parameters of database connection.
Please change `dbName`, `dbUser`, `dbPass`, `dbHost` accordingly.



Step 5. Front end
------------------

`search.html` is the entry point.


When clicking search, query and w will be sent via a ajax POST to `/search_action.php`.

Value w can be changed via the slider or the input in the textbox. Click "set_w" to submit the change.

`/search_action.php` will use `server.php` to connect to `indexer server` (Java)


When clicking "Detail", id will be sent via POST to `/detail.php`.

`/detail.php` will handle sql queries and show detail information on `search.html`.

Detail information includes: 1. The first picture; 2. An infoBox; 3. Some paragraphs from the article; 4. Selected categories.

Some meaningless categories like 'Article_uncensored_since_xx_xx' are eliminated.


When clicking "Close" in the summary block, the summary will be closed. 


Step 6. Extra Credits
------------------------------

The feature we implemented is category visualization. 

The visual graph shows the relationship among pages by the number of shared categories.

Click 'Visualize Similar' to trigger the graph in the detail information block.

The graph contains the following information:
   1. The central node: the page whose detail information is shown;
   2. The surrounding node: the pages who shared categories with the central node;
   3. Weight of the edges: how many categories are shared with the central node;
   4. Name tag of the nodes: title of the article.

**Implementation details:**
Back end:

Front end: 
  Reference library: RGraph from JIT - http://philogb.github.io/jit/


