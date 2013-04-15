package edu.umich.cse.eecs485;

import java.io.IOException;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.Reducer;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.mapreduce.lib.output.TextOutputFormat;
import org.apache.mahout.classifier.bayes.XmlInputFormat;
import java.util.HashSet;
import nu.xom.*;
import java.util.Iterator;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

public class InvertedIndex1
{
    public static class Map extends Mapper<LongWritable, Text, Text, LongWritable> {

        // static final to reduce mem usage
        private final static LongWritable one = new LongWritable(1);

        public void map(LongWritable key, Text value, Context context)
                throws IOException, InterruptedException {

            String strId = "";
            String strBody = "";

            // Parse the xml and read data (page id and article body)
            // Using XOM library
            Builder builder = new Builder();

            try {
                Document doc = builder.build(value.toString(), null);

                Nodes nodeId = doc.query("//eecs485_article_id");
                strId = nodeId.get(0).getChild(0).getValue();

                Nodes nodeBody = doc.query("//eecs485_article_body");
                strBody = nodeBody.get(0).getChild(0).getValue();
            }
            catch (ParsingException ex) { 
                System.out.println("Not well-formed.");
                System.out.println(ex.getMessage());
            }  
            catch (IOException ex) {
                System.out.println("io exception");
            }

            // Tokenize document body
            Pattern pattern = Pattern.compile("\\w+");
            Matcher matcher = pattern.matcher(strBody);

            while (matcher.find()) {
                // Write the parsed token
                // key = term, docid   value = 1
                context.write(new Text(matcher.group() + "," + strId), one);
            }
        }
    }

    public static class Reduce extends Reducer<Text, LongWritable, Text, LongWritable> {

        public void reduce(Text key, Iterable<LongWritable> values, Context context)
                throws IOException, InterruptedException {

            // function starts here 

            int sum = 0;

            for (LongWritable value : values) {
                sum += value.get();
            }

            context.write(key, new LongWritable(sum));
        }
    }

    public static void main(String[] args) throws Exception
    {
        Configuration conf = new Configuration();

        conf.set("xmlinput.start", "<eecs485_article>");
        conf.set("xmlinput.end", "</eecs485_article>");

        Job job = new Job(conf, "XmlParser");

        job.setOutputKeyClass(Text.class);
        job.setOutputValueClass(LongWritable.class);

        job.setMapperClass(Map.class);
        job.setReducerClass(Reduce.class);

        job.setInputFormatClass(XmlInputFormat.class);
        job.setOutputFormatClass(TextOutputFormat.class);

        FileInputFormat.addInputPath(job, new Path(args[0]));
        FileOutputFormat.setOutputPath(job, new Path(args[1]));

        job.waitForCompletion(true);
    }
}
