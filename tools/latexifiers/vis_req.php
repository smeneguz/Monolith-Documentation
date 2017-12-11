<!DOCTYPE html>
<html lang="it">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <link type="text/css" rel="stylesheet" href="stile.css">
      <title>Requisiti</title>
   </head>
   <?php
include_once("lib/lib_core.php");
	include_once("lib/lib_menu.php");
   $db = new database();

   ?>
   <body>
      <div class="menu">
         <ul class="menu">
            <?php makemenu(); ?>
         </ul>
      </div>
      <div class="content">
         <h1>Visualizzazione requisiti</h1>
         <p>Da fare: link a edit e a elimina</p>
         <?php
		//	$FH = fopen("req_dump.sql", "w");
         //$ans = $db->query("SELECT * FROM Requirements");
		//	$ans = $db->query("SELECT DISTINCT requirement, importance, type, description FROM Requirements JOIN UcReq ON requirement=req WHERE uc='0-mt'");
			$ans = $db->query("SELECT * FROM Requirements LEFT JOIN UcReq ON requirement=req  WHERE INSTR(requirement,'-ls')>0");
         while($res = $ans->fetch_assoc()){
            echo '<div class="saveddata">';
            echo '<p>requirement:   ' . $res['importance']. ' '.$res['type'] . ' ' .$res["requirement"].'</p>';
				$ucc = $res['uc']?$res['uc']:'altro';
				echo '<p>from UC '. $ucc .'</p>';
            echo '<p>description:   '.$res["description"].'</p>';
            echo '</div>';
/*
				fwrite($FH, 'INSERT  INTO Requirements (`requirement`,`importance`,`type`,`description`) values ("'. correct_escape($res["requirement"]).'","'.correct_escape($res["importance"]).'","'.
					correct_escape($res["type"]).'","'.correct_escape($res["description"]).'");'."\n" );*/
         }


		/*	fclose($FH);
			function correct_escape($str){
				return str_replace('\\','\\\\',$str);

			}*/
         ?>




      </div>
   </body>
</html>
