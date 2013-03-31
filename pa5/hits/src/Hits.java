import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import org.apache.commons.cli.BasicParser;
import org.apache.commons.cli.CommandLine;
import org.apache.commons.cli.CommandLineParser;
import org.apache.commons.cli.Option;
import org.apache.commons.cli.OptionGroup;
import org.apache.commons.cli.Options;
import org.apache.commons.cli.ParseException;
import org.apache.commons.cli.OptionBuilder;

class Hits {
  public static class Page {
    private int pageID;
    private String pageTitle;
    private double authScore;  
    private double hubScore;
    private List<Page> inEdge = null;
    private List<Page> outEdge = null;
    
    public Page() {
        // null case
        setPageID(-1);
        setAuthScore(0);
        setHubScore(0);
        setOutEdge(null);
        setInEdge(null);
    }
    
    public Page(int pageID, String pageTitle) {
        // normal case
        setPageID(pageID);
        setPageTitle(pageTitle);
        setAuthScore(1);
        setHubScore(1);
        setOutEdge(null);
        setInEdge(null);
    }
    
    public List<Page> getOutEdge() {
        return outEdge;
    }

    public void setOutEdge(List<Page> outEdge) {
        this.outEdge = outEdge;
    }

    public List<Page> getInEdge() {
        return inEdge;
    }

    public void setInEdge(List<Page> inEdge) {
        this.inEdge = inEdge;
    }

    public double getAuthScore() {
        return authScore;
    }

    public void setAuthScore(double authScore) {
        this.authScore = authScore;
    }

    public double getHubScore() {
        return hubScore;
    }

    public void setHubScore(double hubScore) {
        this.hubScore = hubScore;
    }

    public int getPageID() {
        return pageID;
    }

    public void setPageID(int pageID) {
        this.pageID = pageID;
    }

    public String getPageTitle() {
        return pageTitle;
    }

    public void setPageTitle(String pageTitle) {
        this.pageTitle = pageTitle;
    }

  }
  
  public static boolean searchEnd(HashMap<String, Integer> searchMap) {
      // it is the end, when a DocID list (of a search word) is empty 
      for (Map.Entry<String, Integer> entry : searchMap.entrySet() ) {
          // entry.getValue(): index of DocItemList,  length - 1
          System.out.println("cur pos " + entry.getValue());
          System.out.println("max siz " + map.get(entry.getKey()).size());
          if (entry.getValue() == map.get(entry.getKey()).size() - 1) {
              System.out.println("false");
              return false;
          }
      }
      System.out.println("true");
      return true;
  } 
  
  public static List<Integer> getSeedSet(String [] words, Map<String, List<Integer>> map) {

      List<Integer> result = new ArrayList<Integer>();  // contains pageID
      
      int totalWords = words.length;
      
      // below two data structure are for moving pointer to DocID list
      ArrayList<String> queryWords = new ArrayList<String>();
      HashMap<String, Integer> searchMap = new HashMap<String, Integer>();

      for (int i=0; i < totalWords; i++) {
          if (map.get(words[i]) == null) {
              return result;
          }
          queryWords.add(words[i]);
          searchMap.put(words[i], new Integer(0));
      }
      
      // start from first word 
      String nextWord = queryWords.get(0);
      int min = map.get(nextWord).get(0).intValue();

      if (totalWords == 1 && map.get(nextWord) != null) {
          // single word query
          System.out.println("single word");
          for (Integer item: map.get(nextWord)) {
              result.add(item);
          }
      } else { 
          // multiple word query
          
          // check the lists belongs to query words. 
          // check if the cur pointers points to the same docID
          System.out.println("multi word");
          
          boolean flag;
          
          while ( searchEnd(searchMap) ) {
              flag = true;

              String word, preWord;
              int pos, prePos;
              Integer foundDocItem;
              Integer temp;

              // 1. check if we found a result
              for (int i=1; i<totalWords; i++) {
                  word = queryWords.get(i);
                  preWord = queryWords.get(i-1); 
                  pos = searchMap.get(word).intValue();
                  prePos = searchMap.get(preWord).intValue();
                  if (map.get(word).get(pos).intValue() != map.get(preWord).get(prePos).intValue()) {
                      // if we didn't find a doc that contains all those words
                      // find the word where the pointer have smallest doc id
                      // get smallest doc id among all words
                      for (String curWord : queryWords) {
                          pos = searchMap.get(curWord).intValue();
                          if (map.get(curWord).get(pos).intValue() < min) {
                              min = map.get(curWord).get(pos).intValue();
                              nextWord = curWord;
                          }
                      }
                      temp = searchMap.get(nextWord);
                      searchMap.put(nextWord, new Integer(temp.intValue() + 1));
                      flag = false;
                  }
              }

              if (flag) {
                  // 2. found one word
                  word = queryWords.get(0);
                  pos = searchMap.get(word).intValue();
                  foundDocItem = map.get(word).get(pos);

                  result.add(foundDocItem);

                  // increament pointer for each word in query words
                  for (String eachWord : queryWords) {
                      searchMap.put(eachWord, new Integer(searchMap.get(eachWord).intValue() + 1));
                  }

                  System.out.println("found one word");

                  for (String testWord : queryWords) {
                      System.out.println(testWord + " " + searchMap.get(testWord));
                  }
              }
          }
      }
      System.out.println(result.size());

      return result;
  }
  
  static Map<String, List<Integer>> map;

  public static void main(String [] args) {
      // h
      // -k | -converge
      // "query"
      // in net file
      // inverted index
      // out file
      
      CommandLineParser parser = new BasicParser();
      
      Options opts = new Options();
      
      opts.addOption("h", false, "h value");
      opts.addOption("k", true, "stop on iteration");
      opts.addOption("converge", true, "stop on convergence");
      opts.addOption("query", false, "the query");
      opts.addOption("net", false, "net file");
      opts.addOption("index", false, "index file");
      opts.addOption("output", false, "output file");
      
      try {
          CommandLine line = parser.parse(opts, args);
          System.out.println(line.getArgList().toString());
          
          // 1. check required options
          if (line.hasOption("h") 
                  && (line.hasOption("k") || line.hasOption("converge")) 
                  && line.hasOption("query")
                  && line.hasOption("net")
                  && line.hasOption("index")
                  && line.hasOption("output") ) {
              
              String h = (String) line.getOptionValue("h");
              System.out.println("h: " + h);
              
              String k = (String) line.getOptionValue("k");
              System.out.println("k: " + k);
              
              String converge = (String) line.getOptionValue("converge");
              System.out.println("converge: " + converge);
              
              String query = (String) line.getOptionValue("query");
              System.out.println("query: " + query);
              
              String net = (String) line.getOptionValue("net");
              System.out.println("netfile: " + net);
              
              String index = (String) line.getOptionValue("index");
              System.out.println("index: " + index);
              
              String output = (String) line.getOptionValue("output");
              System.out.println("output: " + output);
          } else {
              System.out.println("<h value> (-k <numiterations> | -converge <maxchange>)" +
              		" \"queries\" <input-net-file> <input-inverted-index-file> <output-file>");
              System.exit(0);
          }
          
      } catch( ParseException exp ) {
          System.out.println("parse error!!!");
          System.out.println(exp.getMessage());
      }
      
      System.exit(0);
      
      
      
      
      String invertedIndex = "";
      // Step 2. load inverted index 
      
      map = new HashMap<String, List<Integer>>();

      try {
          String line;
          String[] lineSplited;
          String word;
          Integer pageID;
          List<Integer> tempList; 

          BufferedReader invertedIndexReader = new BufferedReader(new FileReader(invertedIndex));
          while (invertedIndexReader.ready()) {
              line = invertedIndexReader.readLine();
              lineSplited = line.split(" ");
              word = lineSplited[0];
              pageID = new Integer(lineSplited[1]);

              if (map.containsKey(word)) {
                  tempList = map.get(word);
                  tempList.add(pageID);
                  map.put(word, tempList);
              } else {
                  tempList = new ArrayList<Integer>();
                  tempList.add(pageID);
                  map.put(word, tempList);
              }
          }

          // map is constructed
          // map should be a static value

      } catch (FileNotFoundException e) {
          // invertedIndexReader 
          e.printStackTrace();
      } catch (IOException e) {
          e.printStackTrace();
      }
      
      // search the inverted index with query words.

      String [] queryWords  = {"a", "b", "c"};
     
      // Step 3. get seed set
      
      List<Integer> seedSet = getSeedSet(queryWords, map);
      
      // Step 4. get base set
      
      // Step 5. start looping
      
      // 5.a k-loop
      
      // 5.b until converge 
  }
}
