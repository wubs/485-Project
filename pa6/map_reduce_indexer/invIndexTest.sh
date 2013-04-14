#!/bin/bash

export JAVA_HOME=/usr

rm -rf tf_output
../hadoop/bin/hadoop jar InvertedIndex.jar edu.umich.cse.eecs485.InvertedIndex ../hadoop/dataset/test tf_output
