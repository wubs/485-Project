#!/bin/bash

export JAVA_HOME=/usr

rm -rf inv3_out 
../hadoop/bin/hadoop jar dist/map_reduce_indexer.jar edu.umich.cse.eecs485.InvertedIndex3 inv2_out inv3_out 
