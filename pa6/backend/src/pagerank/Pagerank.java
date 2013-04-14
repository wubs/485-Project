import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.io.UnsupportedEncodingException;
import java.util.*;

class Pagerank {
	
	class PRNode{
		HashSet<Integer> inputLinks;
		int output;
		double PRWeight;
		
		public PRNode(double inputWeight)
		{
			PRWeight = inputWeight;
			output = 0;
			inputLinks = new HashSet<Integer>();
		}
	}
	
	HashMap<Integer, PRNode> PRMap;
	HashMap<Integer, Double> OldWeight;
	HashMap<Integer, Double> NewWeight;
	HashSet<Integer> VirtualLink;
	
	double dvalue;
	int itenum;
	double maxchange;
	boolean continueUpdate;
	String inputFileName;
	String outputFileName;
	int NodeNum;
	
	int useIteOrMax; 
	
	public Pagerank()
	{
		dvalue = 0;
		itenum = 0;
		maxchange = 0 ;
		continueUpdate = true;
		useIteOrMax = -1;
		NodeNum = 0 ;
		PRMap = new HashMap<Integer, PRNode>();
		OldWeight = new	HashMap<Integer, Double>();
		NewWeight = new	HashMap<Integer, Double>();
		VirtualLink = new HashSet<Integer>();
	}
	
	public void BuildMap()
	{
		try {
			String words[];
			FileReader fileReader = 
						new FileReader(inputFileName);
	
			BufferedReader bufferedReader = 
						new BufferedReader(fileReader);
            
            bufferedReader.readLine();
            String readLine;
            while(!bufferedReader.readLine().equalsIgnoreCase("</eecs485_edges>"))
			{
                readLine = bufferedReader.readLine();
				Integer outgoingNode = new Integer(readLine.substring(14,readLine.indexOf("</eecs485_from>")));
                
                if(outgoingNode == 5262)
                    System.out.println(readLine);
                
                readLine = bufferedReader.readLine();
				Integer incomingNode = new Integer(readLine.substring(12,readLine.indexOf("</eecs485_to>")));
                if(incomingNode == 5262)
                    System.out.println(readLine);
				            
				if(!outgoingNode.equals(incomingNode))
				{
                    if(!PRMap.containsKey(incomingNode))
                    {
                        NodeNum ++;
                        PRMap.put(incomingNode, new PRNode(0));
                    }
                    PRMap.get(incomingNode).inputLinks.add(outgoingNode);
                    
                    if(!PRMap.containsKey(outgoingNode))
                    {
                        NodeNum ++;
                        PRMap.put(outgoingNode, new PRNode(0));
                    }
                    PRMap.get(outgoingNode).output++;

					
				}
                
                bufferedReader.readLine();
			}
			
			double weight = 1.0/((double)(NodeNum));
            
            
            Iterator it = PRMap.entrySet().iterator();
            while (it.hasNext()) {
                Map.Entry<Integer, PRNode> pairs = (Map.Entry)it.next();
                pairs.getValue().PRWeight = weight;
                OldWeight.put(pairs.getKey(), weight);
                if(pairs.getValue().output == 0)
                {
                    VirtualLink.add(pairs.getKey());
                }
            }
			
			bufferedReader.close();
           /* Set<Integer> treeSet = new TreeSet<Integer>(VirtualLink);
            System.out.println("\n***%%%$$$$$$$\nThere are "+treeSet.size()+"virtual links");
            Iterator virtualItr = treeSet.iterator();
            while(virtualItr.hasNext())
            {
                System.out.println(virtualItr.next());
            }
            System.out.println("***%%%$$$$$$$\n");*/

		}
		catch(FileNotFoundException ex) {
			System.out.println("Unable to open file '" + 
						inputFileName + "'");				
		}
		catch(IOException ex) {
			System.out.println("Error reading file");
		}
	
	}
	
	public void getArguments(String [] args)
	{
		dvalue = Double.parseDouble(args[0]);
		if(args[1].equalsIgnoreCase("-k"))
		{
			itenum = Integer.parseInt(args[2]);
			useIteOrMax = 0;
		}else
		{
			maxchange = Double.parseDouble(args[2]);
			useIteOrMax = 1;
		}
		inputFileName = args[3];
		outputFileName = args[4];
		
		PRMap = new HashMap<Integer, PRNode>();
		
		BuildMap();
		
	}
	
	public void writeToFile(){
		try {
		
			Map<Integer, PRNode> pagerank_treemap = new TreeMap<Integer, PRNode>(PRMap);
			
			FileWriter fileWriter =
					new FileWriter(outputFileName);
	
			BufferedWriter bufferedWriter =
					new BufferedWriter(fileWriter);
					
			Iterator it = pagerank_treemap.entrySet().iterator();
    	while (it.hasNext()) {
        Map.Entry<Integer, PRNode> pairs = (Map.Entry)it.next();

        bufferedWriter.write(pairs.getKey() + " " + pairs.getValue().PRWeight);
        bufferedWriter.newLine();
        
  	  }
			bufferedWriter.close();
		}
		catch(IOException ex) {
			System.out.println("Error writing to file '" + outputFileName + "'");
		}
	}
	
	public void updatePROnce()
	{
		System.out.println("*****************\n start iteration");
		
		double virtualLinkTotal = 0;		
		Iterator virtualItr = VirtualLink.iterator();
		while(virtualItr.hasNext())
		{
			virtualLinkTotal += dvalue*OldWeight.get((Integer) virtualItr.next());
		}
		virtualLinkTotal /= ((double)NodeNum - 1.0);
		System.out.println("done with virtual link");	
		
		Iterator it = PRMap.entrySet().iterator();
		while (it.hasNext()) {
			Map.Entry<Integer, PRNode> pairs = (Map.Entry)it.next();
			Integer pairsKey = (Integer)pairs.getKey();
			double tempWeight = (1-dvalue)/(NodeNum) + virtualLinkTotal;
			
			Iterator it2 = pairs.getValue().inputLinks.iterator();
			while(it2.hasNext())
			{
				Integer tempKey = (Integer)it2.next();
				PRNode tempNode= PRMap.get(tempKey);
				tempWeight += dvalue*OldWeight.get(tempKey)/((double)tempNode.output);
			}
			
			if(VirtualLink.contains(pairsKey))
			{
				tempWeight -= 	dvalue*OldWeight.get(pairsKey)/((double)NodeNum - 1.0);
			}
			
			double tempOldWeight = OldWeight.get(pairsKey);
			
			if(100.0* Math.abs(tempOldWeight- tempWeight)/tempOldWeight > maxchange)
			{
				continueUpdate = true;
			}
			
			NewWeight.put(pairsKey,tempWeight);
			PRMap.get(pairsKey).PRWeight = tempWeight;
			
		}
		
		System.out.println("copying hashmaps");
		HashMap<Integer, Double> tempStorage = NewWeight;
		NewWeight = OldWeight;
		OldWeight = tempStorage;
		
		System.out.println("iteration done!\n*****************");
		
	}
	
	public void CalculatePR()
	{
		if(PRMap.size()!= 0){
			if(useIteOrMax == 0)
			{
				for(int i = 0 ; i < itenum; i ++)
				{
					updatePROnce();
				}
			}else if(useIteOrMax == 1)
			{
				while(continueUpdate)
				{
					continueUpdate = false;
					updatePROnce();
				}
			}
			else{
				System.out.println("Did not specify using K or max change");
				System.exit(1);
			}
		}	
	}
	
  public static void main(String [] args) {
  	Pagerank pr = new Pagerank();
  	pr.getArguments(args);
  	System.out.println("finish building map");
  	pr.CalculatePR();
  	
  	System.out.println("$$$$$$$$$$ Start writing to file%");
  	pr.writeToFile();
  }
}
