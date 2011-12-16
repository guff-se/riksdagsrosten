<?php
Class Ledamoter {
 
    
    public function get_ledamoter($id) {
        
        
        $SQL = "SELECT * FROM Ledamoter"; 
        
        if($id != "") {
       
            if(is_numeric($id)) {
              $SQL = "SELECT * FROM Ledamoter WHERE intressent_id = ".$id;                    
            }
        }
        
                    
        $Database = new Database();
        $Database->connectSQL();
        
        
        $result = $Database->executeSQLRows($SQL);
 
       
        return $result;
        
    }
    
    
    public function print_ledamoter_list($list) {
        
        foreach ($list as $ledamot) {
            
            echo "<li>
				<a href=\"?page=ledamot&id=$ledamot->intressent_id\">
				($ledamot->parti) - 
				$ledamot->tilltalsnamn $ledamot->efternamn
				f. $ledamot->fodd_ar
			</a></li>";
        }
        
        
    }
    
    public function print_ledamot($list) {
        
        foreach ($list as $ledamot) {
            
            echo '<li>';
            echo "(".$ledamot->parti.") - ";
            echo $ledamot->tilltalsnamn. " ".$ledamot->efternamn;
            echo "</a></li>";
            
            
        }
        
        
    }


    
}

?>