#!/bin/bash

export JAVA_HOME=/usr

rm -rf inv4_out 
../hadoop/bin/hadoop jar dist/map_reduce_indexer.jar edu.umich.cse.eecs485.InvertedIndex4 inv3_out inv4_out 
