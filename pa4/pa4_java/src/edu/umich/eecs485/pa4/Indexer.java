package edu.umich.eecs485.pa4;

import java.io.File;
import java.io.IOException;
import java.util.*;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;

/*********************************************************
 * <code>Indexer</code> reads in some raw content and writes to
 * an inverted index file
 *********************************************************/
public class Indexer {
  public Indexer() {
  }

  /**
   * The <code>index</code> code transforms the content into the
   * actual on-disk inverted index file.
   *
   * Fill in this method to do something useful!
   */
  public void index(File contentFile, File outputFile) {
    // Our main function that reads raw file and output the invertedIndex
      HashMap<String, List<DocItem>> map = new HashMap<String, List<DocItem>>();
      
      // scan contentFile search.xml
      
      List<DocItem> data = readXML(contentFile); 
      
      if (data == null){
         System.exit(1);
      }
      
      List<DocItem> docList;
      
      for (DocItem item : data) {
          String[] words = item.caption.split("\\s+");
          
          for (String word : words) {
              if (!map.containsKey(word)) {
                  docList =  new LinkedList<DocItem>();
                  docList.add(item);
                  map.put(word, docList);
              } else {
                  docList = map.get(word);
                  if (!docList.contains(item)) {
                      docList.add(item);
                  }
                  map.put(word, docList);
              }
          }
      }
      
      // serialize map into JSON 
  }
  
  public static List<DocItem> readXML(File fXmlFile) {
      try {
          DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
          DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
          Document doc = dBuilder.parse(fXmlFile);

          doc.getDocumentElement().normalize();

          DocItem temp;

          List<DocItem> list = new ArrayList<DocItem>();

          NodeList nList = doc.getElementsByTagName("photo");
          for (int i=0; i<nList.getLength(); i++) {
              Node nNode = nList.item(i);
              if (nNode.getNodeType() == Node.ELEMENT_NODE) {
                  Element el = (Element) nNode;

                  String url = "static/images/" + el.getAttribute("filename");
                  String code = "empty"; // not used
                  String format = "jpg"; // not used
                  String date = "NOW()"; // not used here
                  String caption = el.getAttribute("caption");
                  String seq = el.getAttribute("sequencenum");

                  // -1.0 means no score calculated yet
                  temp = new DocItem(seq, url, caption);

                  list.add(temp);

              }
          }
          return list;
      } catch (Exception e) {
          e.printStackTrace();
          return null;
      }
  }

  /**
   * Parse the command-line args.
   */
  public static void main(String argv[]) throws IOException {
    if (argv.length < 2) {
      System.err.println("Usage: Indexer <content-filename> <inverted-index-filename>");
      return;
    }
    int i = 0;
    File contentFname = new File(argv[i++]).getCanonicalFile();
    File invertedIndexFname = new File(argv[i++]).getCanonicalFile();

    Indexer indexer = new Indexer();
    indexer.index(contentFname, invertedIndexFname);
  }
}
