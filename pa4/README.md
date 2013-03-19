PA 4
====

db_config.php
-------------
Check `db_config.php.example` 
Fill in the values and place a new file named `db_config.php` in to directory `pa5/html`.

CaptionLoader - subproject of PA4
=================================

Caption laoding is done by a java sub-project called CaptionLoader.
This is a simple Ant project.

`pa4/caption_loader` is the root dir of CaptionLoader.

db.cfg
------
Change db name, uesr, pass here.


Source code 
-----------

`src/CaptionLoader.java`


Ant - build.xml
---------------

Check the file for detail.

After `db.cfg` is configured. 

`ant run` will load the images into database.

1. We used old db schema. column code and type are for base64 images, which is not used in pa4.

2. We didn't load the date, because all dates are the same.


Images
------

This program will not move the files under `flickr-images/` to `/static/images/`

You need to manually move those:
`cp pa4/caption_loader/flickr-images/* pa4/html/static/images/`


PA4 Java - Indexer & IndexServer
===============================

1. run indexer
--------------

`ant run_indexer`, will compile the project and out put the inverted index.

The output filename is `inverted_index.json`, with path `pa4/index/inverted_index.json`.

This is defined in `build.xml`

You can also execute this program using the complex way, as stated in pa4 instruction.

2. run index server
-------------------

`and run_server`, will run indexer first and then load the fresh inverted index file.

The input filename is `inverted_index.json`, with path `pa4/index/inverted_index.json`.

The port is `9010`.

This is also defined in `build.xml`

You can also execute this program using the complex way, as stated in pa4 instruction.



3. Build.xml
-----------

```
This is customized part of pa4/pa4_java/build.xml 

<target name="run_indexer" depends="dist">
  <java fork="true" classname="${main-class}">
    <classpath>
      <path refid="classpath"/>
      <path location="${dist}/pa4.jar"/>
    </classpath>
    <arg value="../search.xml" />
    <arg value="../index/inverted_index.json" />
  </java>
</target>

<target name="run_server" depends="dist, run_indexer">
  <java fork="true" classname="${server-class}">
    <classpath>
      <path refid="classpath"/>
      <path location="${dist}/pa4.jar"/>
    </classpath>
    <arg value="9010" />
    <arg value="../index/inverted_index.json" />
  </java>
</target>
```
