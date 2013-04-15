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
import org.apache.hadoop.mapreduce.lib.input.TextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.mapreduce.lib.output.TextOutputFormat;

public class InvertedIndex2 {

    public static class Map extends Mapper<LongWritable, Text, Text, Text> {
        public void map(LongWritable key, Text value, Context context) throws IOException, InterruptedException { 
            // value is a line 
            String line = value.toString();

            String[] splited_key_value = line.split("\\s+");
            String[] splited_term_docid = splited_key_value[0].split(",");

            String term = splited_term_docid[0];
            String docid = splited_term_docid[1];
            String count = splited_key_value[1];

            context.write(new Text(term), new Text(docid + ":" + count));
        }
    }

    public static class Reduce extends Reducer<Text, Text, Text, Text> {
        public void reduce(Text key, Iterable<Text> values, Context context) throws IOException, InterruptedException {
            // function starts here 

            StringBuilder list_as_text = new StringBuilder(); 

            int df = 0;

            for (Text value : values) {
                list_as_text.append(value.toString());        
                list_as_text.append(",");        
                df++;
            }

            // remove tailing coma 
            list_as_text.deleteCharAt(list_as_text.length()-1);

            // first item of value is df 
            list_as_text.insert(0, String.format("%s;", df));

            context.write(key, new Text(list_as_text.toString()));
        }
    }

    public static void main(String[] args) throws Exception
    {
        Configuration conf = new Configuration();

        Job job = new Job(conf, "InvertedIndex2");

        job.setOutputKeyClass(Text.class);
        job.setOutputValueClass(Text.class);

        job.setMapperClass(Map.class);
        job.setReducerClass(Reduce.class);

        job.setInputFormatClass(TextInputFormat.class);
        job.setOutputFormatClass(TextOutputFormat.class);

        FileInputFormat.addInputPath(job, new Path(args[0]));
        FileOutputFormat.setOutputPath(job, new Path(args[1]));

        job.waitForCompletion(true);
    }
}
