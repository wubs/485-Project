package edu.umich.eecs485.pa6.utils;

public class QueryHit {
    String id;
    double score;

    public QueryHit(String id, double score) {
        this.id = id;
        this.score = score;
    }
    
    public QueryHit(String id) {
        this.id = id;
        this.score = 0;
    }

    public String getIdentifier() {
        return id;
    }

    public double getScore() {
        return score;
    }
    
    public boolean equals(QueryHit other) {
        if (this.id.equals(other.id)) {
            return true;
        } else {
            return false;
        }
    }
    
    public void addOne() {
        this.score += 1;
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
