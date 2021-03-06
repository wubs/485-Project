import javax.xml.parsers.DocumentBuilderFactory;
//import javax.xml.parsers.DocumentBuilder;

import javax.xml.parsers.SAXParser;
import javax.xml.parsers.SAXParserFactory;
import org.xml.sax.Attributes;
import org.xml.sax.SAXException;
import org.xml.sax.helpers.DefaultHandler;

/*import org.w3c.dom.Document;
import org.w3c.dom.NodeList;
import org.w3c.dom.Node;
import org.w3c.dom.Element;
import java.io.File;*/
import java.io.IOException;

import java.sql.*;
import java.util.Properties;


public class  XmlLoader {
    static String db_name;
    static String db_user;
    static String db_pass;
    static String db_host;

    static Properties cfg = new Properties();

    public static void loadCfg() {
        try {
            java.io.FileInputStream fis = new java.io.FileInputStream(new java.io.File("db.cfg"));
            cfg.load(fis);
        } catch (IOException e) {
            e.printStackTrace();
        }
        db_name = cfg.getProperty("dbName", "pa1_db");
        db_user = cfg.getProperty("dbUser", "ruoran");
        db_pass = cfg.getProperty("dbPass", "1216");
        db_host = cfg.getProperty("dbHost", "localhost");
    }
    
    public static PreparedStatement stmt = null;

    public static void main(String argv[]) {
        loadCfg();

        try {
            SAXParserFactory factory = SAXParserFactory.newInstance();
            SAXParser saxParser = factory.newSAXParser();

            System.out.println("Connecting DB");
            String db_url = "jdbc:mysql://localhost/" + db_name;

            Class.forName("com.mysql.jdbc.Driver").newInstance();

            final Connection conn = DriverManager.getConnection(db_url, db_user, db_pass);

            final Statement statement = conn.createStatement();
            
            String queryString = "DROP TABLE IF EXISTS Article, Category, Edge, imageUrl, infoBox;";
            System.out.println(queryString);
            statement.executeUpdate(queryString);
            
            // Create Table Article
            queryString = "CREATE TABLE IF NOT EXISTS Article ( id INT NOT NULL PRIMARY KEY, title VARCHAR(50), body TEXT);";
            System.out.println(queryString);
            statement.executeUpdate(queryString);
            // Create Table Category
            queryString = "CREATE TABLE IF NOT EXISTS Category ( id INT, category VARCHAR(50), PRIMARY KEY(id, category));" ;
            System.out.println(queryString);
            statement.executeUpdate(queryString);
            // Create Table Edge
            queryString = "CREATE TABLE IF NOT EXISTS Edge ( id_from INT, id_to INT, PRIMARY KEY(id_from, id_to));" ;
            System.out.println(queryString);
            statement.executeUpdate(queryString);
            // Create Table imageUrl
            queryString = "CREATE TABLE IF NOT EXISTS imageUrl ( id INT NOT NULL PRIMARY KEY, url TEXT);" ;
            System.out.println(queryString);
            statement.executeUpdate(queryString);
            // Create Table infoBox
            queryString = "CREATE TABLE IF NOT EXISTS infoBox ( id INT NOT NULL PRIMARY KEY, summary TEXT);" ;
            System.out.println(queryString);
            statement.executeUpdate(queryString);

            DefaultHandler handler = new DefaultHandler() {

                boolean bid = false;
                boolean btitle = false;
                boolean bbody = false;
                boolean bcategory = false;
                boolean bfrom = false;
                boolean bto = false;
                boolean bsummary = false;
                boolean bpng = false;
                boolean burl = false;

                StringBuilder id = new StringBuilder();
                StringBuilder title = new StringBuilder();
                StringBuilder body= new StringBuilder();
                StringBuilder category= new StringBuilder();
                StringBuilder from= new StringBuilder();
                StringBuilder to= new StringBuilder();
                StringBuilder summary = new StringBuilder();
                StringBuilder url= new StringBuilder();

                public void startElement(String uri, String localName,String qName, 
                        Attributes attributes) throws SAXException {

                    if (qName.equalsIgnoreCase("eecs485_article_id")) {
                        bid = true;
                    }

                    if (qName.equalsIgnoreCase("eecs485_article_title")) {
                        btitle = true;
                    }

                    if (qName.equalsIgnoreCase("eecs485_article_body")) {
                        bbody = true;
                    }

                    if (qName.equalsIgnoreCase("eecs485_article_category")) {
                        bcategory = true;
                    }

                    if (qName.equalsIgnoreCase("eecs485_from")) {
                        bfrom = true;
                    }

                    if (qName.equalsIgnoreCase("eecs485_to")) {
                        bto = true;
                    }

                    if (qName.equalsIgnoreCase("eecs485_article_summary")) {
                        bsummary = true;
                    }

                    if (qName.equalsIgnoreCase("eecs485_pngs")) {
                        bpng = true;
                    }

                    if (qName.equalsIgnoreCase("eecs485_png_url")) {
                        burl = true;
                    }
                }

                public void endElement(String uri, String localName, String qName) throws SAXException {
                    try{

                        if (qName.equalsIgnoreCase("eecs485_article_body")) {
                            stmt = conn.prepareStatement("INSERT IGNORE Article (id, title, body) VALUES (?,?,?);");
                            stmt.setString(1, id.toString());
                            stmt.setString(2, title.toString());
                            stmt.setString(3, body.toString());
                            System.out.println(stmt.toString());
                            stmt.executeUpdate();
                            id = new StringBuilder(); 
                            title = new StringBuilder();
                            body = new StringBuilder();
                            bbody = false;
                        }

                        if (qName.equalsIgnoreCase("eecs485_category")) {
                            if ( !category.toString().matches("^All_articles_.*") 
                                    && !category.toString().matches("^Wikipedia_.*") 
                                    && !category.toString().matches("^Articles.*") 
                                    && !category.toString().matches("^Use_mdy_dates")) {
                                stmt = conn.prepareStatement("INSERT IGNORE Category (id, category) VALUES (?, ?);"); 
                                stmt.setString(1, id.toString());
                                stmt.setString(2, category.toString());
                                System.out.println(stmt.toString());
                                stmt.executeUpdate();
                                category = new StringBuilder();
                                bcategory = false;
                            }
                        }

                        if (qName.equalsIgnoreCase("eecs485_edge")) {
                            stmt = conn.prepareStatement("INSERT IGNORE Edge (id_from, id_to) VALUES (?, ?);"); 
                            stmt.setString(1, from.toString());
                            stmt.setString(2, to.toString());
                            System.out.println(stmt.toString());
                            stmt.executeUpdate();
                            from = new StringBuilder();
                            to = new StringBuilder();
                            bfrom = false;
                            bto = false;
                        }

                        if (qName.equalsIgnoreCase("eecs485_png_url") && bpng) {
                            
                            stmt = conn.prepareStatement("INSERT IGNORE imageUrl (id, url) VALUES (?, ?);"); 
                            stmt.setString(1, id.toString());
                            stmt.setString(2, url.toString());
                            System.out.println(stmt.toString()); 
                            stmt.executeUpdate();
                            url = new StringBuilder();
                            bpng = false;	      
                            burl = false;	      
                        }


                        if (qName.equalsIgnoreCase("eecs485_summary")) {
                            stmt = conn.prepareStatement("INSERT IGNORE infoBox (id, summary) VALUES (?, ?);"); 
                            stmt.setString(1, id.toString());
                            stmt.setString(2, summary.toString());
                            System.out.println(stmt.toString());
                            stmt.executeUpdate();
                            summary = new StringBuilder();
                            bsummary = false;
                        }
                    }catch (Exception e) {
                        e.printStackTrace();
                        System.exit(1); 
                    }

                }

                public void characters(char ch[], int start, int length) throws SAXException {

                    if (bid) {
                        id = new StringBuilder(new String(ch, start, length));
                        bid = false;
                    }

                    if (btitle) {
                        title = new StringBuilder(new String(ch, start, length));
                        btitle = false;
                    }

                    if (bbody) {
                        if (body.length() > 500) {
                            bbody = false;
                        } else {
                            body.append(new String(ch, start, length));
                        }
                    }

                    if (bcategory) {
                        category = new StringBuilder( new String(ch, start, length));
                        bcategory = false;
                    }

                    if (bfrom) {
                        from = new StringBuilder(new String(ch, start, length));
                        bfrom = false;
                    }

                    if (bto) {
                        to = new StringBuilder(new String(ch, start, length));
                        bto = false;
                    }

                    if (bsummary) {
                        summary.append(new String(ch, start, length));
                    }

                    if (burl) {
                        url = new StringBuilder(new String(ch, start, length));
                        burl = false;
                    }

                }
            }; 
            saxParser.parse("../hadoop/dataset/prod/mining.articles.xml", handler);
            saxParser.parse("../hadoop/dataset/mining.category.xml", handler);
            saxParser.parse("../hadoop/dataset/mining.edges.xml", handler);
            saxParser.parse("../hadoop/dataset/mining.imageUrls.xml", handler);
            saxParser.parse("../hadoop/dataset/mining.infobox.xml", handler);
        } catch (Exception e) {
            e.printStackTrace();
            System.exit(1);
        }

    }
}

