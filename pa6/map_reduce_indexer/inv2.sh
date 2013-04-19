#!/bin/bash

export JAVA_HOME=/usr

rm -rf inv2_out 
../hadoop/bin/hadoop jar dist/map_reduce_indexer.jar edu.umich.cse.eecs485.InvertedIndex2 inv1_out inv2_out 
