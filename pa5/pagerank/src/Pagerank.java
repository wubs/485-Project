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
						
			NodeNum = Integer.parseInt(bufferedReader.readLine().split(" ")[1]);
			
			double weight = 1.0/((double)(NodeNum));
			
			for(int i = 0; i < NodeNum; i ++)
			{
				words = bufferedReader.readLine().split(" ");

				Integer tempNodeID = new Integer(words[0]);
				OldWeight.put(tempNodeID, weight);
				PRMap.put(tempNodeID, new PRNode(weight));
				VirtualLink.add(tempNodeID);
			}
	
			words = bufferedReader.readLine().split(" ");
			
			if(!words[0].equals("*Arcs"))
			{
				System.out.println("There are more than " + NodeNum + " nodes in this file");
				System.exit(1);
			}
			
			int connectionsNum = Integer.parseInt(words[1]);
			System.out.println("Start building connections " + connectionsNum);
			for(int i = 0; i < connectionsNum; i ++)
			{
				words = bufferedReader.readLine().split(" ");
				Integer outgoingNode = new Integer(words[0]);
				Integer incomingNode = new Integer(words[1]);
				
				if(!outgoingNode.equals(incomingNode))
				{
					if(VirtualLink.contains(outgoingNode))
					{
						VirtualLink.remove(outgoingNode);
					}
					PRMap.get(incomingNode).inputLinks.add(outgoingNode);
					PRMap.get(outgoingNode).output++;
				}
			}
			
			bufferedReader.close();
            
            
            
            Set<Integer> treeSet = new TreeSet<Integer>(VirtualLink);
            System.out.println("\n***%%%$$$$$$$\nThere are "+treeSet.size()+"virtual links");
            Iterator virtualItr = treeSet.iterator();
            while(virtualItr.hasNext())
            {
                System.out.println(virtualItr.next());
            }
            System.out.println("***%%%$$$$$$$\n");
            
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
