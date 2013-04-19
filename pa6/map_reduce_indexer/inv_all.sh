#!/bin/bash

FOLDER="../hadoop/dataset/prod"

export JAVA_HOME=/usr

rm -rf inv1_out 
../hadoop/bin/hadoop jar dist/map_reduce_indexer.jar edu.umich.cse.eecs485.InvertedIndex1 $FOLDER inv1_out 
#!/bin/bash

export JAVA_HOME=/usr

rm -rf inv2_out 
../hadoop/bin/hadoop jar dist/map_reduce_indexer.jar edu.umich.cse.eecs485.InvertedIndex2 inv1_out inv2_out 
#!/bin/bash

export JAVA_HOME=/usr

rm -rf inv3_out 
../hadoop/bin/hadoop jar dist/map_reduce_indexer.jar edu.umich.cse.eecs485.InvertedIndex3 inv2_out inv3_out 
#!/bin/bash

export JAVA_HOME=/usr

rm -rf inv4_out 
../hadoop/bin/hadoop jar dist/map_reduce_indexer.jar edu.umich.cse.eecs485.InvertedIndex4 inv3_out inv4_out 
