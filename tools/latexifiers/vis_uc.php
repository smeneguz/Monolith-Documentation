<!DOCTYPE html>
<html lang="it">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="">
      <meta name="author" content="">
      <link type="text/css" rel="stylesheet" href="stile.css">
      <title>Use case</title>
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
         <h1>Visualizzazione use case</h1>
         <p>Da fare: link a edit e a elimina</p>
         <?php
         $ans = $db->query("SELECT * FROM Usecases WHERE INSTR(usecase,'-ls')>0");
			// nuovo
         while($res = $ans->fetch_assoc()){
            echo '<div class="saveddata">';
            echo '<p><b>usecase</b>:   '.$res["usecase"].'</p>';
            //echo '<p><b>dad</b>:   '.$res["dad"].'</p>';
            echo '<p><b>title</b>:   '.$res["title"].'</p>';
            echo '<p><b>description</b>:   '.$res["description"].'</p>';
            echo '<p><b>precondition</b>:   '.$res["precondition"].'</p>';
            echo '<p><b>postcondition</b>:   '.$res["postcondition"].'</p>';
            echo '<p><b>imagePath</b>:   '.$res["imagePath"].'</p>';
//            echo '<p><b>didascalia</b>:   '.$res["didascalia"].'</p>';
				if($res["extensions"]){
					echo '<p><b>extension</b>:   '.$res["extensions"].'</p>';
				}
            $scena = str_replace('\item','</br>',$res["scene"]);
            $scena = str_replace('\begin{enumerate}','</br>',$scena);
            $scena = str_replace('\end{enumerate}','</br>',$scena);
            echo '<p><b>scene</b>:   '. $scena .'</p>';
            //    echo '<p><b>alternativeScene</b>:   '.$res["alternativeScene"].'</p>';
            echo '<p><b>actors</b>:   '.$res["actors"].'</p>';
            //          echo "<hr/>";
            echo '</div>';
         }


         ?>

      </div>
   </body>
</html>
