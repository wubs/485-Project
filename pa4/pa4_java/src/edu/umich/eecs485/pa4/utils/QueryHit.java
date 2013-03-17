package edu.umich.eecs485.pa4.utils;

public class QueryHit {
    String id;
    double score;

    public QueryHit(String id, double score) {
        this.id = id;
        this.score = score;
    }

    public String getIdentifier() {
        return id;
    }

    public double getScore() {
        return score;
    }
    
    public int compare(QueryHit that) {
       
        if (Integer.parseInt(this.id) > Integer.parseInt(that.id) ) {
            return 1;
        } else if (Integer.parseInt(this.id) == Integer.parseInt(that.id) ) {
            return 0;
        } else {
            return -1;
        }
    }
}
