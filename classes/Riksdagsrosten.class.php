<?php



/**
 * Description of Riksdagsrosten Class
 *
 * @author Johan Sanden <johan.se>
 */


    Class Users {
        
        public $username;
        public $password;
        
        function get_username() {
            return($this->username);
        }
    
        function set_username($username) {
            $this->username = $username;
        }
    
        function get_password() {
            return($this->password);
        }
    
        function set_password($password) {
            $this->password = $password;
        }
        
        
        
    }
    

    
    Class Utskottsforslag {
        
      
        public $dok_id;
        public $titel;
        public $bik;
        public $publicerad;
        public $ja;
        public $nej;
        public $absent;
        
        
        public function get_dok_id() {
            return $this->dok_id;
        }

        public function set_dok_id($dok_id) {
            $this->dok_id = $dok_id;
        }

        public function get_titel() {
            return $this->titel;
        }

        public function set_titel($titel) {
            $this->titel = $titel;
        }

        public function get_bik() {
            return $this->bik;
        }

        public function set_bik($bik) {
            $this->bik = $bik;
        }

        public function get_publicerad() {
            return $this->publicerad;
        }

        public function set_publicerad($publicerad) {
            $this->publicerad = $publicerad;
        }

        
        public function get_latest_10_utskottsforslag() {
                        
            $Database = new Database();
            $Database->connectSQL();
            
            
            
            $result = $Database->executeSQLRows("SELECT * FROM Utskottsforslag ORDER BY publicerad DESC LIMIT 5");
     
            
            
            
            return $result;
            
        }
        
        
        
        
        public function get_latest_utskottsforslag($limit) {
                       
            if($limit == "") {
                $limit = 5;
            }
            
            $Database = new Database();
            $Database->connectSQL();
            
            
            $SQL = "SELECT Utskottsforslag.* FROM Utskottsforslag, Voteringar 
                    WHERE Utskottsforslag.dok_id = Voteringar.dok_id AND Voteringar.punkt = 1
                    ORDER BY Utskottsforslag.publicerad DESC LIMIT ".$limit;
            
            
            
            $result = $Database->executeSQLRows($SQL);
     
            
            
            return $result;
            
        }
        
        public function get_voteringar_by_dok_id($dok_id) {
                       
            
            $Database = new Database();
            $Database->connectSQL();
            
            $sql_dok_id = mysql_real_escape_string($dok_id);
            
            $result = $Database->executeSQLRows("SELECT * FROM Voteringar WHERE dok_id = '".$sql_dok_id."' AND punkt = 1");
     
            return $result;
            
        }
        
        
         public function get_utskottsforslag_by_id($id) {
                       
            if(!is_numeric($id)) {
                echo "ERROR!";
                die();
                
            }
            
            $Database = new Database();
            $Database->connectSQL();
            
            $sql_id = mysql_real_escape_string($id);
            
            $result = $Database->executeSQLRows("SELECT * FROM Utskottsforslag WHERE id = ".$sql_id." LIMIT 1");
     
            return $result;
            
        }
        
        public function get_percentage_result_by_votering_id($votering_id) {


        if (strlen($votering_id) != 36) {
            echo "ERROR!";
            die();
        }

        $Database = new Database();
        $Database->connectSQL();

        $sql_votering_id = mysql_real_escape_string($votering_id);

        $result = $Database->executeSQLRows("select count(*) as antal, rost from Roster WHERE votering_id = '" . $sql_votering_id . "' GROUP BY rost");

        return $result;
    }
    
        
    
        // get the first vote id from a dok_id //
        public function set_vote_data($dok_id) {
        
        $Database = new Database();
        $Database->connectSQL();

         $result = $Database->executeSQLRows("SELECT * FROM Voteringar WHERE dok_id = '".$dok_id."' AND punkt = 1");

        
        }
        
        
        
        
        
        public function print_li_forslag($arrayOfObjects) {
   
            
            
            foreach ($arrayOfObjects as $forslag) {
            
               $test= $this->get_voteringar_by_dok_id($forslag->dok_id);
                
                echo "<!-- ";
                var_dump($test);
                
                $total = $test[0]->roster_ja + $test[0]->roster_nej;
                echo $total." - ";
                
                $total_yes_percentage = ($test[0]->roster_ja / $total) * 100;
                $total_no_percentage = ($test[0]->roster_nej / $total) * 100;
                
                $total_yes_percentage = round($total_yes_percentage, 0);
                $total_no_percentage = round($total_no_percentage, 0);
                
                echo "-->";
                
                
                
                
                
                
                            echo '<li>
					<a href="?page=vote&id='.$forslag->id.'">
						<div class="left">
							<h6>'.$forslag->titel.'</h6>
							<p class="description">'.$forslag->bik.'</p>
							<div class="meta">Voteringsdag:'.$forslag->publicerad.'</div>
						</div>
						<div class="right">
							<div class="votes">
								<div class="yes" style="width:'.$total_yes_percentage.'%;">'.$total_yes_percentage.'%</div>
								<div class="no" style="width:'.$total_no_percentage.'%;">'.$total_no_percentage.'%</div>
								<div class="clearer">&nbsp;</div>
							</div>
						</div>
						<div class="clearer">&nbsp;</div>
					</a>
				</li>';
                
            }
            
        }
        
        
        
    }



?>