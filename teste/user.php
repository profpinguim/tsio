<?php
session_start();
class User {
   public  $host;
   public  $DB;
   public  $DB_User;
   public  $DB_User_Pw;
   public  $table;
   public  $table_user_col;
   public  $table_user_pw_col;


   function dbconnect(){ 
	/**
	 * ligacao a base de dados
	 */
	$connections = mysql_connect($this->host, $this->DB_User, $this->DB_User_Pw)
	or die ('Unabale to connect to the SGBD');
	mysql_select_db($this->DB) or die ('Unable to select database!');
	return;
   }
   function register($table, $username, $password){
	/**
	 * Regista utilizador e envia para fquery
	 * @param string $table
	 * @param string $username
	 * @param string $password
	 */
		$this->dbconnect();
		if($this->table == ""){
		   $this->table = $table;
		}
	$result = $this->fquery("INSERT INTO ".$this->table." VALUES(DEFAULT,'".$username."','".$password."',DEFAULT)"); 
   }
   function login($table, $username, $password){
		/**
	 * Verifica login a partir de dados 
	 * @param string $table
	 * @param string $username
	 * @param string $password
	 */   
	$this->dbconnect();
	if($this->table == ""){
		$this->table = $table;
	}
	$result = $this->fquery("SELECT * FROM ".$this->table." WHERE ".$this->table_user_col."='".$username."' AND ".$this->table_user_pw_col." = '".$password."';");
	$row=mysql_fetch_assoc($result);
	if($row != "Erro"){
		if($row[$this->table_user_col] !="" && $row[$this->table_user_pw_col] !=""){
			$_SESSION['loggedin'] = $row[$this->table_user_pw_col];
			return true;
		}else{
			session_destroy();
			return false;
		}	
	}else{
		return false;
	} 

	}
   function fquery($query) {
		/**
	 * Recebe comandos SQL e retorna resultado 
	 * @param string $query
	 * @return resource $result
	 */   
	$this->dbconnect();
	$result = mysql_query($query) or die(mysql_error());
	if($result){
	return $result;
	}else{
	$error = "Error";
	return $result;
	}      
   }
   function logout(){ 
	/**
	 * Apaga sessao
	 */  
	session_destroy();
	return;
   }
   function loginform($formname, $formid, $formaction){ 
		 /**
	 * Desenha formulario para login 
	 * @param string $formname
	 * @param string $formid
	 * @param string $formaction
	 * @return HTML	 
	 */ 
	$this->dbconnect();
	echo'<form name="'.$formname.'" method="post" id="'.$formid.'" enctype="application/x-www-form-urlencoded" action="'.$formaction.'">
	<div><label for="username">Username</label>
	<input name="username" id="username" type="text"></div>
	<div><label for="password">Password</label>
	<input name="password" id="password" type="password"></div>
	<input name="action" id="action" value="login" type="hidden">
	<div><input name="submit" id="submit" value="Login" type="submit"></div>
	</form>';
   }
   function createtable(){
	 /**
	 * Cria tabela
	 */  
	$this->dbconnect();
	$fquery = "CREATE TABLE IF NOT EXISTS ".$this->table." (
	userid int(11) NOT NULL auto_increment,
	username varchar(50) NOT NULL default '',
	password varchar(50) NOT NULL default '',
	userlevel int(11) NOT NULL default '0',
	PRIMARY KEY  (userid)
	)";
	$result = mysql_query($fquery) or die(mysql_error());
	return;
   }
   function User(){
	 /**
	 * Construtor
	 */  
    $this->host = 'localhost';
	$this->DB = 'teste1';
	$this->DB_User = 'root';
	$this->DB_User_Pw = '';
	$this->table = 'UserTb';
	$this->table_user_col = 'username';
	$this->table_user_pw_col = 'password';
   }
}
?>