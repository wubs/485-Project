import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.HashMap;
import java.util.HashSet;
import java.util.LinkedList;
import java.util.List;
import java.util.Map;

class Hits {
  public static class Page {
    private Integer pageID;
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
        inEdge = new LinkedList<Page>();
        outEdge = new LinkedList<Page>();
    }
    
    public Page(Integer pageID, String pageTitle) {
        // normal case
        setPageID(pageID);
        setPageTitle(pageTitle);
        setAuthScore(1);
        setHubScore(1);
        inEdge = new LinkedList<Page>();
        outEdge = new LinkedList<Page>();
    }
    
    public void addOutEdge(Page to) {
        this.outEdge.add(to);
    }
    
    public void addInEdge(Page from) {
        this.inEdge.add(from);
    }
    
    public List<Page> getOutEdgeList() {
        return outEdge;
    }

    public void setOutEdgeList(List<Page> outEdge) {
        this.outEdge = outEdge;
    }

    public List<Page> getInEdgeList() {
        return inEdge;
    }

    public void setInEdgeList(List<Page> inEdge) {
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

    public Integer getPageID() {
        return pageID;
    }

    public void setPageID(Integer pageID) {
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
  static Map<Integer, Page> page_map;

  public static void commandWrong() {
      System.out.println("h value> (-k <numiterations> | -converge <maxchange>) " +
              "\"queries\" <input-net-file> <input-inverted-index-file> <output-file>");
      System.exit(0);
  }
  public static void main(String [] args) {
      // h
      // -k | -converge
      // "query"
      // in net file
      // inverted index
      // out file
      
      
      
      if (args.length != 7) {
          System.out.println("Missing argument(s), see help below");
          commandWrong();
      }
      
      Integer h = new Integer(args[0]);
      
      String opt = (String) args[1];
      
      Integer k = null;
      Double converge = null;
      if (opt.equals("-k")) {
          k = new Integer(args[2]);
      } else if(opt.equals("-converge")) {
          converge = new Double(args[2]);
      } else {
          System.out.println("-k or -converge wrong");
          commandWrong();
      }
      
      String query = (String) args[3];
      String netFName = (String) args[4];
      String indexFName = (String) args[5];
      String outputFName = (String) args[6];
      
      System.out.println(h);
      System.out.println(k);
      System.out.println(converge);
      System.out.println(query);
      System.out.println(netFName);
      System.out.println(indexFName);
      System.out.println(outputFName);
      
      // Step 2. load inverted index, and net file
      loadMap(indexFName);
      System.out.println("map loaded from inverted index"); 

      loadPageMap(netFName);
      System.out.println("page map loaded from net file"); 
     
      // Step 3. get seed set
      // by searching the inverted index with query words.
      String [] queryWords  = query.toLowerCase().split(" "); 
      List<Integer> seedSet = getSeedSet(queryWords, map);
      System.out.println("seed got, len: " + seedSet.size());
      
      // Step 4. get base set
      
      // This set contains pageID: Integer.
      // We could get the corresponding Page for O(1) 
      HashSet<Integer> basePageSet = new HashSet<Integer>();
      
      Integer maxID = new Integer(0);
      Page curPage;
      for (Integer pageID : seedSet) {
          curPage = page_map.get(pageID);
          // 4.1 add cur
          basePageSet.add(curPage.getPageID());
          if (pageID > maxID) {
              maxID = pageID;
          }
          // 4.2 get inEdges
          for (Page pointingToCur : curPage.getInEdgeList()) {
              basePageSet.add(pointingToCur.getPageID());
              if (pointingToCur.getPageID() > maxID) {
                  maxID = pointingToCur.getPageID();
              }
          }
          // 4.3 get outEdges
          for (Page pointedFromCur : curPage.getOutEdgeList()) {
              basePageSet.add(pointedFromCur.getPageID());
              if (pointedFromCur.getPageID() > maxID) {
                  maxID = pointedFromCur.getPageID();
              }
          }
      }
      
      System.out.println("Got the base page set, len: " + basePageSet.size());
      
      //Collections.sort(basePageSet, new PageIDComparator());
      
      System.out.println("maxID: " + maxID.toString());
      try{
          FileWriter fstream = new FileWriter(outputFName);
          BufferedWriter out = new BufferedWriter(fstream);
          
          // Here is a Pigeonhole sort
          Integer tempID;
          Page tempP;
          for (int i=0; i<maxID.intValue(); i++) {
              tempID = new Integer(i); 
              if (basePageSet.contains(tempID)) {
                  tempP = page_map.get(tempID);
                  out.write(tempP.getPageID().toString());
                  out.write("\n");
              }
          }
          out.close();
      } catch (Exception e){//Catch exception if any
          System.err.println("Error: " + e.getMessage());
      }
      // Step 5. start looping
      
      if (k != null) {
          // 5.A k-loop
      
      } else {
          // 5.B until converge 
      }
  }
  
  // This is not used anymore, we use Pigeonhole sort instead
  public static class PageIDComparator implements Comparator<Page>{
      @Override
      public int compare(Page o1, Page o2) {
          if (o1.getPageID().intValue() > o2.getPageID().intValue() ) {
              return 1;
          } else if (o1.getPageID().intValue() == o2.getPageID().intValue() ) {
              return 0;
          } else {
              return -1;
          }
      }
  } 

  public static void loadMap(String indexFName) {
      map = new HashMap<String, List<Integer>>();
      try {
          String line;
          String[] lineSplited;
          String word;
          Integer pageID;
          List<Integer> tempList; 

          BufferedReader invertedIndexReader = new BufferedReader(new FileReader(indexFName));
          long count = 1;
          while (invertedIndexReader.ready()) {
              line = invertedIndexReader.readLine().toLowerCase();
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
              System.out.println("reading line " + count++);
          }
          // map is constructed
          // map should be a static value
      } catch (FileNotFoundException e) {
          // invertedIndexReader 
          e.printStackTrace();
      } catch (IOException e) {
          e.printStackTrace();
      }
  }
  
  public static void loadPageMap(String netFName) {
      page_map = new HashMap<Integer, Page>();
      try {
          String line;
          String[] lineSplited;
          
          Integer pageID;
          String pageTitle;
          Page tempPage; 
          
          Page fromPage;
          Page toPage;
          
          boolean firstStage = true;

          BufferedReader invertedIndexReader = new BufferedReader(new FileReader(netFName));
          long count = 1;
          while (invertedIndexReader.ready()) {
              line = invertedIndexReader.readLine();
              
              if (line.charAt(0) == '*') {
                  if ( line.substring(1).split(" ")[0].equals("Vertices") ) {
                      firstStage = true;
                  } else if ( line.substring(1).split(" ")[0].equals("Arcs") ) {
                      firstStage = false;
                  }
                  continue;
              }
              
              lineSplited = line.split(" ");

              if (firstStage) {
                  // 1. First stage, read Nodes
                  pageID = new Integer(lineSplited[0]);
                  pageTitle = lineSplited[1];
                  if (page_map.containsKey(pageID)) {
                      System.out.println("wait duplicated pageID?");
                      System.exit(0);
                  } else {
                      tempPage = new Page(pageID, pageTitle);
                      page_map.put(pageID, tempPage);
                  }
              } else {
                  // 2. Second stage, read Edges
                  fromPage = page_map.get(new Integer(lineSplited[0]));
                  toPage = page_map.get(new Integer(lineSplited[1]));
                  
                  fromPage.addOutEdge(toPage);
                  toPage.addInEdge(fromPage);
              }
              
              System.out.println("reading line " + count++);
          }
          // page_map is constructed
          // page_map should be a static value
      } catch (FileNotFoundException e) {
          // invertedIndexReader 
          e.printStackTrace();
      } catch (IOException e) {
          e.printStackTrace();
      }
  }
}