#!/bin/bash

export JAVA_HOME=/usr

rm -rf inv1_out 
../hadoop/bin/hadoop jar dist/map_reduce_indexer.jar edu.umich.cse.eecs485.InvertedIndex1 ../hadoop/dataset/test inv1_out 
