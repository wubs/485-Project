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
		double oldPRWeight;
		
		public PRNode(double inputWeight)
		{
			oldPRWeight = inputWeight;
			PRWeight = inputWeight;
			output = 0;
			inputLinks = new HashSet<Integer>();
		}
	}
	
	HashMap<Integer, PRNode> PRMap;
	HashSet<Integer> VirtualLink;
	
	double dvalue;
	int itenum;
	double maxchange;
	boolean continueUpdate;
	String inputFileName;
	String outputFileName;
	int NodeNum;
	
	// if -1; means error; 
	// if 0, use itenum as criteria
	// if 1, use maxChange as stop criteria
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
			
			for(int i = 0; i < NodeNum; i ++)
			{
				//System.out.println(i);
				words = bufferedReader.readLine().split(" ");
				Integer tempNodeID = new Integer(words[0]);
				PRMap.put(tempNodeID, new PRNode(1.0/NodeNum));
//				VirtualLink.add(tempNode);
			}
	
			words = bufferedReader.readLine().split(" ");
			
			if(!words[0].equals("*Arcs"))
			{
				System.out.println("There are more than " + NodeNum + " nodes in this file");
				System.exit(1);
			}
			
			String line = null;
			while((line = bufferedReader.readLine()) != null)
			{
				words = line.split(" ");
				Integer outgoingNode = new Integer(words[0]);
				Integer incomingNode = new Integer(words[1]);
				
				if(!outgoingNode.equals(incomingNode))
				{
					//VirtualLink.remove(outgoingNode);
					PRMap.get(incomingNode).inputLinks.add(outgoingNode);
					PRMap.get(outgoingNode).output++;
				}
			}
			
			bufferedReader.close();			
			Iterator it = PRMap.entrySet().iterator();
			while (it.hasNext()) {
				Map.Entry<Integer, PRNode> pairs = (Map.Entry)it.next();
				if(pairs.getValue().output==0)
				{
					VirtualLink.add(pairs.getKey());
				}
			}			
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
	
	// Update page rank for once and make sure to check the max change
	public void updatePROnce()
	{
		Iterator it = PRMap.entrySet().iterator();
		while (it.hasNext()) {
			Map.Entry<Integer, PRNode> pairs = (Map.Entry)it.next();
			double tempWeight = (1-dvalue)/(NodeNum);
			
			Iterator it2 = pairs.getValue().inputLinks.iterator();
			while(it2.hasNext())
			{
				PRNode tempNode= PRMap.get(it2.next());
				tempWeight += dvalue*tempNode.oldPRWeight/((double)tempNode.output);
			}
			
			//Handle virtual links
			Iterator virtualItr = VirtualLink.iterator();
			while(virtualItr.hasNext())
			{
				int VirtualLinkCurrentNodeID = (Integer) virtualItr.next();
				//System.out.println(VirtualLinkCurrentNodeID);
				if(pairs.getKey() != VirtualLinkCurrentNodeID)
				{
					tempWeight += dvalue*PRMap.get(VirtualLinkCurrentNodeID).oldPRWeight / ((double)NodeNum - 1.0);
				}
			}
			
			
			if(100.0* Math.abs(pairs.getValue().oldPRWeight - tempWeight)/pairs.getValue().oldPRWeight > maxchange)
			{
				continueUpdate = true;
			}
			pairs.getValue().PRWeight = tempWeight;
			
		}
		
		it = PRMap.entrySet().iterator();
		while (it.hasNext()) {
			Map.Entry<Integer, PRNode> pairs = (Map.Entry)it.next();
			pairs.getValue().oldPRWeight = pairs.getValue().PRWeight;
		}
		
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
  	
  	pr.CalculatePR();
  	
  	pr.writeToFile();
  }
}
