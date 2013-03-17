package edu.umich.eecs485.pa4;

import java.util.HashMap;

import org.json.simple.JSONAware;
import org.json.simple.JSONObject;
import org.json.simple.JSONValue;

import edu.umich.eecs485.pa4.utils.QueryHit;

public class DocItem extends QueryHit implements JSONAware{

    public String url;
    public String caption;
    
    public HashMap<String, Long> tf;
    
    public DocItem(String id, String url, String caption) {
        super(id, -1.0);
        
        this.url = url;
        this.caption = caption;
        this.tf = new HashMap<String, Long>();
    }
    
    public DocItem(String id, double score, String url, String caption, HashMap<String, Long> tf) {
        super(id.trim(), score);
        
        this.url = url;
        this.caption = caption;
        this.tf = tf;
    }
    
    public int getIntId() {
        return Integer.parseInt(this.getIdentifier().trim());
    }

    public String toJSONString() {
        // TODO Auto-generated method stub
        JSONObject obj = new JSONObject();
        
        obj.put("id", this.getIdentifier());
        obj.put("score", this.getScore());
        obj.put("url",  this.url );
        obj.put("caption",  this.caption  );
        obj.put("tf", JSONValue.toJSONString(this.tf));
        
        return obj.toString(); 
    }
}
