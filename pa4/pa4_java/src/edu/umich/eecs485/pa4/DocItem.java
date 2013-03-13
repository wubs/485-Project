package edu.umich.eecs485.pa4;

import edu.umich.eecs485.pa4.utils.QueryHit;

public class DocItem extends QueryHit{

    String url;
    String caption;
    
    public DocItem(String id, String url, String caption) {
        super(id, -1.0);
        this.url = url;
        this.caption = caption;
    }
}
