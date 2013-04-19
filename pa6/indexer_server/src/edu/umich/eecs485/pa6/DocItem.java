package edu.umich.eecs485.pa6;

import edu.umich.eecs485.pa6.utils.QueryHit;

public class DocItem extends QueryHit {

    double tfidf; 
    public DocItem(String id) {
        super(id, -1.0);
        
    }
    
    public DocItem(String id, String tfidf) {
        super(id.trim(), -1);
        
        this.tfidf = Double.parseDouble(tfidf);
    }
    
    public int getIntId() {
        return Integer.parseInt(this.getIdentifier().trim());
    }
    
    public int hashCode() {
        return Integer.parseInt(this.getIdentifier());
    }
    
    public boolean equals(Object obj) {
        DocItem that = (DocItem) obj;
        if (this.getIdentifier().equals(that.getIdentifier())) {
            return true;
        } else {
            return false;
        }
    }
}
