all: invindex

invindex:
	javac -cp .:../hadoop/hadoop-core-1.0.1.jar:../hadoop/lib/* -d bin InvertedIndex.java XmlInputFormat.java
	jar -cvf ./InvertedIndex.jar -C bin/ .

xom:
	javac -cp .:../hadoop/hadoop-core-1.0.1.jar:../hadoop/lib/* -d bin XOMtest.java
	java -Xms1024m -Xmx1024m -cp .:../lib/*:bin/ edu.umich.cse.eecs485.XOMtest

clean:
	rm -rf *.jar bin/* tf_output
