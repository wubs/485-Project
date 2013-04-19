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

public class InvertedIndex4 {

  public static class Map extends Mapper<LongWritable, Text, Text, Text> {
    public void map(LongWritable key, Text value, Context context) throws IOException, InterruptedException { 
      // value is a line 
      String line = value.toString();

      String[] splited_key_value = line.split("\\s+");
      String docid = splited_key_value[0];
      
      String[] triples = splited_key_value[1].split(",");
      
      
      String[] splited_triples;
      String term, df, tfidf;
      for (int i=0; i<triples.length; i++) {
          splited_triples = triples[i].split(":"); 
          term = splited_triples[0];
          df = splited_triples[1];
          tfidf = splited_triples[2];
          
          context.write(new Text(String.format("%s:%s", term, df)),
                        new Text(String.format("%s:%s", docid, tfidf)) );
      }
    }
  }

  public static class Reduce extends Reducer<Text, Text, Text, Text> {
    public void reduce(Text key, Iterable<Text> values, Context context) throws IOException, InterruptedException {
        
      StringBuilder list = new StringBuilder();
      String[] term_df = key.toString().split(":");
      
      String docid, tfidf;
      String[] docid_tfidf;
      
      for (Text value : values) {
          docid_tfidf = value.toString().split(":");
          docid = docid_tfidf[0]; 
          tfidf = docid_tfidf[1]; 

          list.append(String.format("%s:%s ", docid, tfidf));
      }
      // remove trailing space
      list.deleteCharAt(list.length()-1);
      // add df 
      list.insert(0, String.format("%s ", term_df[1]));
      context.write(new Text(term_df[0]), new Text(list.toString()));
    }
  }

  public static void main(String[] args) throws Exception
  {
      Configuration conf = new Configuration();

      Job job = new Job(conf, "InvertedIndex3");

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
