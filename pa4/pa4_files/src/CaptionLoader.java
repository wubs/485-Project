import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.parsers.DocumentBuilder;

import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;
import org.w3c.dom.Element;
import java.io.File;
import java.io.IOException;

import java.sql.*;
import java.util.Properties;

public class CaptionLoader {
    static File fXmlFile = null;
    static String db_name;
    static String db_user;
    static String db_pass;
    static String db_host;
    
    static Properties cfg = new Properties();
    
    public static void loadCfg() {
        try {
            java.io.FileInputStream fis = new java.io.FileInputStream(new java.io.File("db.cfg"));
//            cfg.load(CaptionLoader.class.getClassLoader().getResourceAsStream("db.cfg"));
            cfg.load(fis);
        } catch (IOException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }
        db_name = cfg.getProperty("dbName", "pa1_db");
        db_user = cfg.getProperty("dbUser", "ruoran");
        db_pass = cfg.getProperty("dbPass", "1216");
        db_host = cfg.getProperty("dbHost", "localhost");
    }
    
    public static void main(String [] args) {
        
        loadCfg();
         
        // load raw_data file
        try {
            System.out.println("Start loading caption");
            fXmlFile = new File("search.xml");
        } catch (Exception e) {
            e.printStackTrace();
            System.exit(1); 
        }

        // load db
        try {
            System.out.println("Connecting DB");
            String db_url = "jdbc:mysql://localhost/" + db_name;
            Connection conn = null;
            Statement statement = null;
            int updateQuery = 0;

            Class.forName("com.mysql.jdbc.Driver").newInstance();

            conn = DriverManager.getConnection(db_url, db_user, db_pass);

            statement = conn.createStatement();
            
            // Create Album
            
            String queryString = "INSERT IGNORE Album (albumid, title, created, lastupdated, access, username) values" +
                    "(5, 'PA4 Album', NOW(), NOW(), 'public', 'traveler');";
            System.out.println(queryString);
            updateQuery = statement.executeUpdate(queryString);
            
            
            // load Photo table

            DocumentBuilderFactory dbFactory = DocumentBuilderFactory.newInstance();
            DocumentBuilder dBuilder = dbFactory.newDocumentBuilder();
            Document doc = dBuilder.parse(fXmlFile);

            doc.getDocumentElement().normalize();

            NodeList nList = doc.getElementsByTagName("photo");
            for (int i=0; i<nList.getLength(); i++) {
                Node nNode = nList.item(i);
                if (nNode.getNodeType() == Node.ELEMENT_NODE) {
                    Element el = (Element) nNode;

                    String url = "static/images/" + el.getAttribute("filename");
                    String code = "empty";
                    String format = "jpg";
                    String date = "NOW()";
                    String caption = el.getAttribute("caption");
                    String seq = el.getAttribute("sequencenum");
                        
                    // Load Photo table
                    queryString = "INSERT IGNORE Photo (url, code, format, date) VALUES " +
                            "('" + url + "','" + code + "','" + format + "'," + date + ")"; 

                    System.out.println(queryString);
                    updateQuery = statement.executeUpdate(queryString);
                   
                    // Load Contain table
                    queryString = "INSERT IGNORE Contain (albumid, url, caption, sequencenum) VALUES " +
                            "(5, '" + url + "',\"" + caption + "\"," + seq + ")"; 

                    System.out.println(queryString);
                    updateQuery = statement.executeUpdate(queryString);
                }
            }

        } catch (Exception e) {
            e.printStackTrace();
            System.exit(1); 
        }
    }
}
