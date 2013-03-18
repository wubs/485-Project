PA4 Java
========

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
