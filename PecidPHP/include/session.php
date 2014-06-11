<?php 
/*CREATE TABLE `sessions ` ( 
  `id` varchar(255) binary NOT NULL default '', 
  `expires` int(10) unsigned NOT NULL default '0', 
  `data` text, 
  PRIMARY KEY  (`id`) 
) TYPE=InnoDB; */
class Session 
{ 
    // session-lifetime 
    var $lifeTime; 
    // mysql-handle 
    var $dbHandle; 
    function open($savePath, $sessName) { 
       // get session-lifetime 
       $this->lifeTime = get_cfg_var("session.gc_maxlifetime"); 
       // open database-connection 
       $dbHandle = @mysql_connect(C('db_host'),C('db_user'),C('db_pass')); 
       $dbSel = @mysql_select_db(C('db_name'),$dbHandle); 
       // return success 
       if(!$dbHandle || !$dbSel) 
           return false; 
       $this->dbHandle = $dbHandle; 
       return true; 
    } 
    function close() { 
        $this->gc(ini_get('session.gc_maxlifetime')); 
        // close database-connection 
        return @mysql_close($this->dbHandle); 
    } 
    function read($sessID) { 
        // fetch session-data 
        $res = mysql_query("SELECT data AS d FROM sessions  
                            WHERE id = '$sessID' 
                            AND expires > ".time(),$this->dbHandle); 
        // return data or an empty string at failure 
        if($row = mysql_fetch_assoc($res)) 
            return $row['d']; 
        return ""; 
    } 
    function write($sessID,$sessData) { 
        // new session-expire-time 
        $newExp = time() + $this->lifeTime; 
        // is a session with this id in the database? 
        $res = mysql_query("SELECT * FROM sessions  
                            WHERE id = '$sessID'",$this->dbHandle); 
        // if yes, 
        if(mysql_num_rows($res)) { 
            // ...update session-data 
            mysql_query("UPDATE sessions  
                         SET expires = '$newExp', 
                         data = '$sessData' 
                         WHERE id = '$sessID'",$this->dbHandle); 
            // if something happened, return true 
            if(mysql_affected_rows($this->dbHandle)) 
                return true; 
        } 
        // if no session-data was found, 
        else { 
            // create a new row 
            mysql_query("INSERT INTO sessions  ( 
                         id, 
                         expires, 
                         data) 
                         VALUES( 
                         '$sessID', 
                         '$newExp', 
                         '$sessData')",$this->dbHandle); 
            // if row was created, return true 
            if(mysql_affected_rows($this->dbHandle)) 
                return true; 
        } 
        // an unknown error occured 
        return false; 
    } 
    function destroy($sessID) { 
        // delete session-data 
        mysql_query("DELETE FROM sessions  WHERE id = '$sessID'",$this->dbHandle); 
        // if session was deleted, return true, 
        if(mysql_affected_rows($this->dbHandle)) 
            return true; 
        // ...else return false 
        return false; 
    } 
    function gc($sessMaxLifeTime) { 
        // delete old sessions 
        mysql_query("DELETE FROM sessions  WHERE expires < ".time(),$this->dbHandle); 
        // return affected rows 
        return mysql_affected_rows($this->dbHandle); 
    } 
} 

?>