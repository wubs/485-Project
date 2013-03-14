package edu.umich.eecs485.pa4;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.io.Reader;
import java.util.ArrayList;
import java.util.HashMap;
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

import edu.umich.eecs485.pa4.utils.QueryHit;
import edu.umich.eecs485.pa4.utils.GenericIndexServer;

/*******************************************************
 * The <code>IndexServer</code> loads an inverted index and processes
 * user queries.  It returns Hit objects that are then returned to the
 * PHP server over the network.
 *
 * Its superclass is GenericIndexServer, which provides basic network
 * and serialization functionality.
 *******************************************************/
public class IndexServer extends GenericIndexServer {
    
    
   static HashMap<String, List<DocItem>> map;
    
  /**
   * Creates a new <code>IndexServer</code> instance.
   *
   * The superclass needs a port to listen on.
   * We store fname in a member variable for later use.
   */
  public IndexServer(int port, File fname) throws IOException {
    super(port, fname);
  }

  /**
   * This method is called once when the server is first started.
   * Inside this method you should load the inverted index from disk.
   *
   * Fill in this method to do something useful!
   */
  public void initServer(File fname) {
    // Do something!
    System.err.println("Init server with fname " + fname);
    
    // here we will load the serialized map object back into mem.
  
    map = new HashMap<String, List<DocItem>>();
    
    try {
        BufferedReader read = new BufferedReader(new FileReader(fname));
        
        String s = read.readLine();
        JSONParser parser = new JSONParser();
        
        ContainerFactory containerFactory = new ContainerFactory(){
            public List creatArrayContainer() {
                return new ArrayList();
            }

            public Map createObjectContainer() {
                return new HashMap();
            }
        };

        Map json = (Map)parser.parse(s, containerFactory);
        Iterator iter = json.entrySet().iterator();
        DocItem item;
        Map temp;
        List<DocItem> listDocItem;
        Map.Entry entry;
        String word;
        List docList;
        Map<String, Integer> tf;
        
        while(iter.hasNext()){
            entry = (Map.Entry) iter.next();
            
            word = (String) entry.getKey();
            docList = (List) entry.getValue();
            
            listDocItem = new ArrayList<DocItem>();
            
            for (int j=0; j<docList.size(); j++) {
               temp = (Map) docList.get(j);
               tf = (Map<String, Integer>) parser.parse( (String) temp.get("tf"), containerFactory);
               item = new DocItem( 
                       (String) temp.get("id"), 
                       ((Double) temp.get("score")).doubleValue(), 
                       (String) temp.get("caption"), 
                       (String) temp.get("url"), 
                       (HashMap<String, Integer>) tf
                    );
               listDocItem.add(item);
            }
            
            map.put(word, listDocItem);
        }
        
    } catch (Exception e) {
        // TODO Auto-generated catch block
        e.printStackTrace();
    }
  }
  

  /**
   * The <code>processQuery</code> method takes a user query and
   * returns a relevance-ranked and scored list of document hits.
   * If the list is empty, then there are zero hits for the query. 
   *
   * This method should never return null.
   *
   * Fill in this method to do something useful!
   */
  private boolean endSearching(int []index, int [] size)
  {
      int i = 0;
      for(int temp: index)
          if(temp > size[i++])
              return true;

      return false;
  }

  public List<QueryHit> processQuery(String query) {

      System.out.println("Processing query '" + query + "'");
      // Split query String into words
      String [] words = query.split(" "); 
      int totalWords = words.length;

      int[] index = new int[totalWords];        
      int []size = new int[totalWords];
      int maxId = Integer.MIN_VALUE;
      for(int i = 0 ; i < totalWords;i ++){
          size[i] = map.get(words[i]).size();
          maxId = Math.max(maxId,  map.get(words[i]).get(0).getIntId() );
      }

      // Start searching
      ArrayList<QueryHit> result = new ArrayList<QueryHit>();
      
      while(true){
          int i = 0;
          while(i < totalWords){

              while( map.get(words[i]).get(index[i]).getIntId() < maxId) {
                  index[i] ++;
              }                

              if(map.get(words[i]).get(index[i]).getIntId() > maxId)
              {
                  maxId = map.get(words[i]).get(index[i]).getIntId();
                  break;
              }

              if(i == (words.length-1)){
                  DocItem tempDocItem = map.get(words[i]).get(index[i]);
                  result.add(QueryHit(tempDocItem.getIdentifier(), calScore(words, tempDocItem) ));
                  for(int j = 0; j < totalWords; j++)
                      index[j] ++;
              }
              i++;
          }// while(i < )
          if(endSearching(index, size)) {
              return result;
          }
      }// while(1)
  }// processQuery
 
  private double calScore(String [] words, DocItem docItem){
      HashMap<String, Double> queryTf = new HashMap<String, Double>();
      HashMap<String, Double> idf = new HashMap<String, Double>();
      
      double de1 = 0;
      double de2 = 0;
      double nu = 0;

      for(String word: words){
        if(!queryTf.containsKey(word)) {
          queryTf.put(word, new Double(1)); 
        } else {
          queryTf.put(word, Double.valueOf( queryTf.get(word) + 1)); 
        }
        // TODO make "totalDocument" a global double variable, for PA4, it should be 200
        //idf.put(word, Math.log10((totalDocument/((double)map.get(word).size()) )) );
      }

      double result = 0;
      for(String word: words){
        double temp1 = queryTf.get(word) * idf.get(word);
        double temp2 = docItem.tf.get(word) * idf.get(word);
        nu += temp1 * temp2;
        de1 += temp1 * temp1;
        de2 += temp2 * temp2;
      }

      if(de2 == 0)
        return 0;

      result = nu / (Math.sqrt(de1) * Math.sqrt(de2));
      
      return result;
    }
  /**
   * Parse the command-line args.  Then start up the server.
   */
  public static void main(String argv[]) throws IOException {
    if (argv.length < 2) {
      System.err.println("Usage: IndexServer <portnum> <inverted-index-filename>");
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

    // Run server.  Note that because server.serve() creates a new
    // thread, the process will not terminate even though serve() returns.
    IndexServer server = new IndexServer(portnum, fname);
    server.serve();
  }
}