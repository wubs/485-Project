package edu.umich.eecs485.pa6;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;

import javax.xml.parsers.SAXParser;
import javax.xml.parsers.SAXParserFactory;
import org.xml.sax.Attributes;
import org.xml.sax.SAXException;
import org.xml.sax.helpers.DefaultHandler;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;
import org.w3c.dom.Element;
import java.io.File;
import java.io.IOException;
import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.Reader;
import java.util.ArrayList;
import java.util.Collection;
import java.util.Collections;
import java.util.Comparator;
import java.util.HashMap;
import java.util.HashSet;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;
import java.util.Iterator;

import org.json.simple.JSONArray;
import org.json.simple.JSONObject;
import org.json.simple.JSONValue;
import org.json.simple.parser.ContainerFactory;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

import edu.umich.eecs485.pa6.utils.QueryHit;
import edu.umich.eecs485.pa6.utils.GenericIndexServer;

public class IndexServer extends GenericIndexServer {
    
   static HashMap<String, HashMap<String, DocItem> > map;
   
   static HashMap<String, Double> pr_map;
   
   static HashMap<String, Double> df_map;
   
   static HashMap<String, ArrayList<String> > cat_art_map;

   static HashMap<String, ArrayList<String> > art_cat_map; 
   
   static HashSet<String> stop_words;

   static long doc_length=0;
   
   static File pr_file;
   
    
  public IndexServer(int port, File fname, File pr_filename) throws IOException {
    super(port, fname, pr_filename);
  }

  public void initServer(File fname, File pr_filename) {
    // Do something!
    System.err.println("Init server with fname " + fname);
    
    // here we will load the serialized map object back into mem.
  
    map = new HashMap<String, HashMap<String, DocItem>>();
    pr_map  = new HashMap<String, Double>();
    df_map = new HashMap<String, Double>();
    cat_art_map = new HashMap<String, ArrayList<String> >();
    art_cat_map = new HashMap<String, ArrayList<String> >();

    
    try {
        stop_words = new HashSet<String>();
        BufferedReader read = new BufferedReader(new FileReader(new File("english.stop")));
        String line;
       
        while( (line = read.readLine()) != null ) {
            stop_words.add(line.trim().toLowerCase());
        }
    } catch (Exception e) {
        e.printStackTrace();
        System.out.println("Reading inverted index error!");
        System.exit(1);
    }

    try {
        BufferedReader read = new BufferedReader(new FileReader(fname));
        
        String line;
        HashMap<String, DocItem> mapDocItem;
        String[] key_value;
        String word;
        String value;
        
        String[] df_list;
        String df;
        String[] list;
        String docid;
        String tfidf;
        DocItem item;
        
        long i = 0;
        
        while( (line = read.readLine()) != null ){
            key_value = line.split("\\s+", 2);
            word = key_value[0].toLowerCase();
            
            if (stop_words.contains(word)) {
                continue;
            }
            
            value = key_value[1];
            
            df_list = value.split("\\s+", 2);
            df = df_list[0];
            list = df_list[1].split("\\s+");
            
            if ( !map.containsKey(word) ) {
                mapDocItem = new HashMap<String, DocItem>();
            } else {
                mapDocItem = map.get(word);
            }
            
            for (int j=0; j<list.length; j++) {
                docid = list[j].split(":")[0];
                tfidf = list[j].split(":")[1];
                item = new DocItem(docid, tfidf);
                mapDocItem.put(docid, item);
            }
            
            df_map.put(word, new Double(df));
            map.put(word, mapDocItem);
            System.out.println(i++);
        }
        
    } catch (Exception e) {
        e.printStackTrace();
        System.out.println("Reading inverted index error!");
        System.exit(1);
    }
    
    try {
        BufferedReader read = new BufferedReader(new FileReader(pr_filename));
        
        String line;
        String docid;
        Double pr;
        
        while( (line = read.readLine()) != null ){
            doc_length++;
            docid = line.split("\\s+")[0];
            pr = new Double(line.split("\\s+")[1]);
            pr_map.put(docid, pr);
        }
    } catch (Exception e) {
        e.printStackTrace();
        System.out.println("Reading page rank error");
    }
    
    try {
            SAXParserFactory factory = SAXParserFactory.newInstance();
            SAXParser saxParser = factory.newSAXParser();
            DefaultHandler handler = new DefaultHandler() {

               boolean bcategory = false;
               boolean bid = false;

               String category, id;

                public void startElement(String uri, String localName,String qName, 
                        Attributes attributes) throws SAXException {
                    if (qName.equalsIgnoreCase("eecs485_article_category")) {
                        bcategory = true;
                    }
                    if (qName.equalsIgnoreCase("eecs485_article_id")) {
                        bid = true;
                    }
               }

                public void endElement(String uri, String localName, String qName) throws SAXException {
                    try{

                        if (qName.equalsIgnoreCase("eecs485_category")) {
                            if ( !category.matches("^All_articles_.*") 
                                    && !category.matches("^Wikipedia_.*") 
                                    && !category.matches("^Articles.*") 
                                    && !category.matches("^Use_.*_dates")) {
                                
                              if(cat_art_map.containsKey(category))
                              {
                                cat_art_map.get(category).add(id);
                              }else
                              {
                                ArrayList<String> ToBeAdd = new ArrayList<String>();
                                ToBeAdd.add(id);
                                cat_art_map.put(category, ToBeAdd);
                              }

                              if(art_cat_map.containsKey(id))
                              {
                                art_cat_map.get(id).add(category);
                              }else
                              {
                                ArrayList<String> ToBeAdd = new ArrayList<String>();
                                ToBeAdd.add(category);
                                art_cat_map.put(id, ToBeAdd);
                              }

                            }
                        }
                    }catch (Exception e) {
                        e.printStackTrace();
                        System.exit(1); 
                    }

                }

                public void characters(char ch[], int start, int length) throws SAXException {

                  if (bcategory) {
                    category = new String(ch, start, length);
                    bcategory = false;
                  }

                   if (bid) {
                    id = new String(ch, start, length);
                    bid = false;
                  }
                }
            }; 
            saxParser.parse("../hadoop/dataset/mining.category.xml", handler);
        } catch (Exception e) {
            e.printStackTrace();
            System.exit(1);
        }
  }
  
  public List<QueryHit> processQuery(String query, double w) {
      System.out.println("Processing query '" + query + "' w:" + w);
      ArrayList<QueryHit> result = new ArrayList<QueryHit>();
      
      // Split query String into words
      String [] words = query.toLowerCase().split("\\s*[^0-9a-zA-Z']+\\s*"); 
      String word;
      int totalWords = words.length;
      
      HashSet<DocItem> union = new HashSet<DocItem>(); 
      
      for (int i=0; i<totalWords; i++) {
          word = words[i];
      
          
          if (map.get(word) != null) {
              union.addAll(map.get(word).values());
          }
      }
      
      for (DocItem item: union) {
          result.add( new QueryHit(item.getIdentifier(), calScore(words, item, w)));
      }
      
      // this will sort doc item in descending order
      Collections.sort( result, new DocItemComparator());
      
      if (result.size() > 10) {
          return result.subList(0, 10);
      } else {
          return result;
      }
  }
  
  public List<QueryHit> processQuery2(String query) {
      System.out.println("Processing query sim cat '" + query );
      HashMap<String, Integer> result_map = new HashMap<String, Integer>();
      ArrayList<QueryHit> result = new ArrayList<QueryHit>();
      
      // Split query String into ids 
      String [] words = query.toLowerCase().split("\\s*[^0-9a-zA-Z']+\\s*"); 
      String ArticleID;
      int totalWords = words.length;
      
      QueryHit temp;
      
      for (int i=0; i<totalWords; i++) {
          ArticleID = words[i];
          if(art_cat_map.containsKey(ArticleID))
          {
              for(String categoryID : art_cat_map.get(ArticleID))
              {
                  for(String OtherArticleId : cat_art_map.get(categoryID))
                  {
                      if (!OtherArticleId.equals(ArticleID)) {
                          if(result_map.containsKey(OtherArticleId))
                          {
                              result_map.put(OtherArticleId, new Integer( result_map.get(OtherArticleId) + 1));
                          } else
                          {
                              result_map.put(OtherArticleId, new Integer(1));
                          }
                      }
                  }
              }
          } 
      }
      
      for (String key : result_map.keySet()) {
         result.add(new QueryHit(key, Double.parseDouble(result_map.get(key).toString()))); 
      }
      
      // this will sort doc item in descending order
      Collections.sort( result, new DocItemComparator());
      
      System.out.println(result.size());
      if (result.size() > 20) {
          return result.subList(0, 20);
      } else {
          return result;
      }
      
  }
  public static double calScore(String[] words, DocItem item, double w) {

      HashMap<String, Double> query_tfidf = new HashMap<String, Double>();

      double de1 = 0;
      double de2 = 0;
      double nu = 0;
      double word_df = 0;

      String word;
      for (int i=0; i< words.length; i++) {
          word = words[i].toLowerCase();
          if(df_map.containsKey(word)) {
              word_df = df_map.get(word) + 1;
          }
          else {
              word_df = 1;
          }
          word_df = Math.log10((doc_length+1)/(word_df));

          if ( !query_tfidf.containsKey(word) ) {
              query_tfidf.put(word, new Double(word_df)); 
          } else {
              query_tfidf.put(word, Double.valueOf( query_tfidf.get(word) + word_df)); 
          }    
      }

      double result = 0, temp1 = 0, temp2 = 0;
      for (int i=0; i< words.length; i++) {
          word = words[i];
          
          temp1 = query_tfidf.get(word);
          
          if(map.containsKey(word) && map.get(word).containsKey(item.getIdentifier())) {
              temp2 = map.get(word).get(item.getIdentifier()).tfidf;
          } else {
              temp2 = 0;
          }
          
          System.out.println("id: " + item.getIdentifier() + " temp 1 " + temp1 + " temp2 " + temp2);
          nu += temp1 * temp2;
          de1 += temp1 * temp1;
          de2 += temp2 * temp2;
//          System.out.println("nu: " + nu + " de1 " + de1 + " de2 " + de2);
          
      }

      if(de2 == 0) {
          return 0;
      }

      result = nu / (Math.sqrt(de1) * Math.sqrt(de2));
//      System.out.println("zero result: " + result);
      
      result = (1-w) * result;
      
//      System.out.println("first result: " + result);
      
      if(pr_map.containsKey(item.getIdentifier()))
      {
        result += w * pr_map.get(item.getIdentifier());
      }
      
//      System.out.println("second result: " + result);

      return result;
  }
  
  public static class DocItemComparator implements Comparator<QueryHit>{
      // this will sort doc item in descending order
      
      @Override
      public int compare(QueryHit o1, QueryHit o2) {
          if (o1.getScore() > o2.getScore() ) {
              return -1;
          } else if (o1.getScore() == o2.getScore() ) {
              return 0;
          } else {
              return 1;
          }
      }
  }
  
  public static void main(String argv[]) throws IOException {
    if (argv.length < 2) {
      System.err.println("Usage: IndexServer <portnum> <inverted-index-filename> <pr-filename>");
      return;
    }

    // Parse args
    int i = 0;
    int portnum = -1;
    try {
      portnum = Integer.parseInt(argv[i++]);
    } catch (NumberFormatException nfe) {
      System.err.println("Cannot parse port number: " + argv[i-1]);
      return;
    }
    File fname = new File(argv[i++]).getCanonicalFile();
    File pr_filename = new File(argv[i++]).getCanonicalFile();

    // Run server.  Note that because server.serve() creates a new
    // thread, the process will not terminate even though serve() returns.
    
    IndexServer server = new IndexServer(portnum, fname, pr_filename);
    server.serve();
  }
}
