<?php 

       $ch = curl_init();

        //get contents from the nelly data 
        //file get contents is disbled by hosting
        //  $html = file_get_contents( "http://www.nellydata.com/CapitalFM/livedata.asp");
	
	//try this
	 
	//$url = "http://www.jigger-ahadi.org/calendar.html";
	// scrape localy
    
    $url = "localhost/ahadi/calendar.html";


	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
	$html = curl_exec($ch);
	if (curl_errno($ch)) {
  	echo curl_error($ch);
  	echo "\n<br />";
	  $html = '';
	} else {
 	 curl_close($ch);
	}

	if (!is_string($html) || !strlen($html)) {
	echo "Failed to get contents.";
	$html = '';
	}
	else{
	//echo $html;
	}
	  //declare new dom document
      $dom = new DOMDocument();

      libxml_use_internal_errors(TRUE); //disable libxml errors
             //if any html is actually returned
      if(!empty($html))
      {   
             //load html to dom
            $dom->loadHTML($html);
             //remove errors for yucky html
            libxml_clear_errors(); 
            $dom_xpath = new DOMXPath($dom);
             //get the whole table
            $dom_table = $dom_xpath->query('//table[@width = "750"]');
             //$dom_rows = $dom->find("table#data tr");
        

       if($dom_table->length > 0)
       {
         $n = 0 ;
         foreach($dom_table as $dom_row)
         {
           $table[$n] =  $dom_row->nodeValue;
          }
        }

      }
    
    $table =  preg_replace('/([\200-\277])/e', "'&#'.(ord('\\1')).';'", $table[0]);

  // var_dump($table);
        //return $table ;

        $i = strpos($table, ":");
        //echo $table;



        $i = $i-6;


        $delimit = substr($table, 0,$i);
       
        $delimit  = str_replace(chr(194)," ",$delimit 	);
         //echo $delimit;



        $delimited = delimiter($delimit);
    
        $no = 0;
        $date;
        $event;
        $org;
        foreach($delimited as $dm){
            

            if((count($dm))>0){
                 if($dm==""){

                 }
                 else{
                 	global $date, $event,$org;
                 //echo (trim($dm))."<br>";
                   $date[$no] = substr($dm ,0,strpos($dm,"-"));

                   $date[$no] = substr($date[$no],0,strpos($date[$no],"Usafi Bora"));

                   $org[$no] = "Usafi Bora";



                   $event[$no] = substr($dm ,(strpos($dm,"-")+1));
                   $no++;






                 }
            		


            }
   
        }
         
          $data = array("date"=>$date ,"organisation"=>$org ,"event"=>$event);

           var_dump(json_encode($data));

          

          return   json_encode($data);





     function delimiter($string)
  {
      $delimited = preg_split("/[_]/", $string);
      $n= 0;
      return $delimited;

  }
  




?>