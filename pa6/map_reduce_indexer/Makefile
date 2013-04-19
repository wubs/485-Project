all: inv1 inv2 

inv1:
	javac -cp .:../hadoop/hadoop-core-1.0.1.jar:../hadoop/lib/* -d bin InvertedIndex1.java XmlInputFormat.java
	jar -cvf ./InvertedIndex1.jar -C bin/ .

inv2:
	javac -cp .:../hadoop/hadoop-core-1.0.1.jar:../hadoop/lib/* -d bin InvertedIndex2.java XmlInputFormat.java
	jar -cvf ./InvertedIndex2.jar -C bin/ .

xom:
	javac -cp .:../hadoop/hadoop-core-1.0.1.jar:../hadoop/lib/* -d bin XOMtest.java
	java -Xms1024m -Xmx1024m -cp .:../lib/*:bin/ edu.umich.cse.eecs485.XOMtest

clean:
	rm -rf *.jar bin/* inv1_out inv2_out 
