package edu.umich.eecs485.pa4;

import java.util.HashSet;

public class TestE {

    /**
     * @param args
     */
    public static void main(String[] args) {
        // TODO Auto-generated method stub
        DocItem a = new DocItem("1", "url", "cap");
        DocItem b = new DocItem("1", "url", "cap");
        
        System.out.println(a.equals(b));
        
        HashSet<DocItem> s1 = new HashSet<DocItem>();
        HashSet<DocItem> s2 = new HashSet<DocItem>();
        HashSet<DocItem> s3 = new HashSet<DocItem>();
        
        s1.add(a);
        
        s2.add(b);
        
        
        s1.addAll(s2);
        
        System.out.println("should be 1 " + s1.size());
        
        // s1 => 1
        // s2 => 1
        // 
        
        // add ele in s1 to s3
        
        for (DocItem t : s1) {
            if (!s3.contains(t)){
                s3.add(t);
            }
        }
        System.out.println(s3.size());
        
        // add ele in s2 to s3
        for (DocItem t : s2) {
            if (!s3.contains(t)){
                s3.add(t);
            }
        }
        
        // s3 -> 1
        
        
        System.out.println(s3.size());
        

    }
    
}
