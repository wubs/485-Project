package edu.umich.eecs485.pa4;

import org.json.simple.JSONAware;
import org.json.simple.JSONObject;

import edu.umich.eecs485.pa4.utils.QueryHit;

public class DocItem extends QueryHit implements JSONAware{

    String url;
    String caption;
    
    public DocItem(String id, String url, String caption) {
        super(id, -1.0);
        this.url = url;
        this.caption = caption;
    }

    public String toJSONString() {
        // TODO Auto-generated method stub
        
        StringBuffer sb = new StringBuffer();
        
        sb.append("{");
        
        sb.append(JSONObject.escape("id"));
        sb.append(":");
        sb.append("\"" + JSONObject.escape(this.getIdentifier()) + "\"");
        sb.append(",");
        
        sb.append(JSONObject.escape("url"));
        sb.append(":");
        sb.append("\"" + JSONObject.escape(this.url) + "\"");
        sb.append(",");
        
        sb.append(JSONObject.escape("caption"));
        sb.append(":");
        sb.append("\"" + JSONObject.escape(this.caption) + "\"");
        sb.append(",");
        
        sb.append(JSONObject.escape("score"));
        sb.append(":");
        sb.append("\"" + JSONObject.escape(String.valueOf(this.getScore())) + "\"");
        
        sb.append("}");
        
        return sb.toString(); 
    }
}
