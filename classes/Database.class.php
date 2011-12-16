<?php
/*
 * Description of Database
 *
 * @author Johan Sanden <johan@labyrint.com>
 */

class Database {

    private $username = "riksdagsrosten";
    private $password = "rrwww01";
    private $host     = "127.0.0.1";
    private $database = "riksdagsrosten";


    /** Constructor **/
    function Database() {
    }


    /**
     * change current DB in object
     *
     * @author Johan Sanden <johan@marquee.se>
     *
     */

    function setDb($db) {
        $this->database = $db;

    }

    /**
     * Just connect connect to database with the correct credentials
     *
     * @author Johan Sanden <johan@labyrint.com>
     *
     */

    function connectSQL() {
        $link = mysql_connect($this->host, $this->username, $this->password);

        mysql_select_db($this->database, $link);

        if (!$link) {
     	   mail("sounden@gmail.com"," Database - mysql error","client: ".$_SERVER['REMOTE_ADDR']."\n from: ".$_SERVER['HTTP_REFERER']."\n error time: ".date("H:i:s").";\n".mysql_error()."","From: jam.se\n");
            die('Could not connect: ' . mysql_error());
        }else{
            //echo "Connected!";
        }
        return($link);
    }


    /**
     * Execute a statement that is expected to return one row
     *
     * @author Johan Sanden <johan@labyrint.com>
     *
     * @return object $mysqlDataSetObject Returns ONE object dataset
     *
     */
     function executeSQL($sql,$type) {
        
        
        $mysqlResult = mysql_query($sql);

		print(mysql_error());
         // only return a resultset if the query will return one //
         if($type == "SELECT" && mysql_num_rows($mysqlResult) > 0) {
            $mysqlDataSetObject = mysql_fetch_object($mysqlResult);
            return($mysqlDataSetObject);
        }

     }

     /**
     * Execute a statement that is expected to return one row
     *
     * @author Johan Sanden <johan@marquee.se>
     *
     * @return object $mysqlDataSetObject Returns ONE object dataset
     *
     */
     function executeSQLRows($sql) {
        

        $mysqlResult = mysql_query($sql);
		print(mysql_error());
		if(mysql_num_rows($mysqlResult)){
        	while ($row = mysql_fetch_object($mysqlResult)) {
            	$objectArray[] = $row;
        	}
			return($objectArray);
		}
		else
			return(NULL);
 
     }


}
