<?php

require_once("configurazione.php");

global $repoPath;
$pathOutDdP = $repoPath.'4-RA/Esterni/DdP/';
$pathOutAdR = $repoPath.'4-RA/Esterni/AdR/';
$pathOutPdQ = $repoPath.'4-RA/Esterni/PdQ/';

class database
{
   private $conn;
   function __construct()
   {
      // $this->conn = new mysqli("", "root", "", "Meridian2");
      global $username ,$passwd;
      $port = argumentHandler();
      //echo "\n$username\n$passwd\n$port\n";
      $this->conn = new mysqli("127.0.0.1", $username ,$passwd , "nrigato-ES", $port); // 23306
      if($this->conn->connect_error)
      {
         die('Error: ( ' . $this->conn->connect_errno . ' ) ' . $this->conn->connect_error );
      }
      if(!$this->conn->set_charset("utf8")){
         die("Error loading character set utf8: $mysqli->error \n");
      }
   }

   function __destruct()
   {
      $this->conn->close();
   }


   // espone il metodo query della connessione mysqli
   function query($dom)
   {
      if(!($res = $this->conn->query($dom)))
      {
         echo "<p>";
         echo "CALL failed: (" . $this->conn->errno . ") " . $this->conn->error;
         echo "</p><p><em>".$dom."</em></p>";
      }
      else
         return $res;
   }
   function error_string(){
      return $this->conn->error;
   }

}



// serve per raggruppare secondo le sigle dopo il trattino. se no sarebbero in ordine sparso
function compare_uc($a,$b){
   $a = implode(array_reverse(explode('-',$a)));
   $b = implode(array_reverse(explode('-',$b)));
   // echo "$a||||$b\n<br/>\n";
   if ($a == $b) {
      return 0;
   }
   return ($a < $b) ? -1 : 1;
}

function check_punto($s){
   $s = trim($s);
   if(preg_match('/\.$/',$s) || preg_match('/}$/',$s))
      return $s;
   else
      return $s . '.';
}


function checkAcapo ($txt){
   // $txt = str_replace('::','::\\',$txt);
   return $txt;
}

function pulisci_chars($txt){
   $txt = str_replace("\r\n","\n",$txt);
   $txt = str_replace("\r","\n",$txt);
   // $txt = str_replace("_","\\_",$txt);
   $txt = str_replace(">","$>$",$txt);
   $txt = str_replace("<","$<$",$txt);
   return trim($txt);
}


function gestioneParoleSuperLunghe($txt,$cell){
   $numerocritico=45;
   // 47 è il massimo ammissibile
   //    \makecell{
   if(strlen($txt)>$numerocritico){
      // print strlen($txt) . "  ".$txt ."\n";
      $str_arr = explode('::',$txt);
      // print implode('--',$str_arr);
      $str_piece = '';
      $numACapo = 0; //inteso +1
      for($i=0;$i< count($str_arr) ;$i++){
         // print "$str_piece\n";
         $str_piece2 = $str_piece;
         if(strlen($str_piece2)){
            $str_piece2 .= '::'.$str_arr[$i];
         }
         else{
            $str_piece2 .= $str_arr[$i];
         }
         if(strlen($str_piece2)>$numerocritico){
            if($numACapo){
               $str_piece .= '::'.$str_arr[$i];
            }
            else{
               $str_piece .= ':: \\\\ \\hfill '.$str_arr[$i]; //$_\\hookleftarrow$
            }
            $numACapo++;
         }
         else{
            $str_piece = $str_piece2;
         }
      }
      if($cell){
         $txt =  '\\makecell[l]{'. $str_piece ."}";
      }
      else{
         $txt =  /*'\\makecell[l]{'. */$str_piece /*."}"*/;
      }
   }
   //print "$txt\n";
   return $txt;
}

function printRequirement($num){
   $db = new database();
   $ans = $db->query("SELECT *  FROM Requirements WHERE requirement='$num'");
   $res = $ans->fetch_assoc();
   return 'R'.ucfirst($res['importance']).ucfirst($res['type']).$num;
}



function argumentHandler(){
   global $argv;
   if(!isset($argv[1])){
      die("Non sono stati forniti argomenti\n\n");
   }
   else{
      $x = $argv[1];
      if($x === "home"){
         return 33306;
      }
      elseif($x === "unipd"){
         return 23306;
      }
      else{
         die("Parametro <$x> non corretto. inserire 'home' o 'unipd'\n");
      }
   }

}


function colorTestStatus($status){
   $status = trim($status);
   if($status == 'Implementato'){
      $status = "\\textcolor{ForestGreen}{". $status. "}";
   }
   if($status == 'Non Implementato'){
      $status = "\\textcolor{Red}{".$status."}";
   }
   return $status;
}


function colorTestSup($sup){
   $sup = trim($sup);
   if($sup == 'Superato'){
      $sup = "\\textcolor{ForestGreen}{". $sup. "}";
   }
   if($sup == 'Non Superato'){
      $sup = "\\textcolor{Red}{".$sup."}";
   }
   return $sup;
}

function str_lreplace($search, $replace, $subject)
{
   $pos = strrpos($subject, $search);

   if($pos !== false)
   {
      $subject = substr_replace($subject, $replace, $pos, strlen($search));
   }

   return $subject;
}

/*
   function dumpDB(){
   global $username ,$passwd;
   $port = argumentHandler();
   //
   $return_var = NULL;
   $output = NULL;
   $command = "/usr/bin/mysqldump -u $username -h 127.0.0.1 -p$pass nrigato-ES > $dump_directory";
   exec($command, $output, $return_var);
   }
 */
?>
