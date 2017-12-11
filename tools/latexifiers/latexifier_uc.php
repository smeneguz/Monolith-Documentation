<?php

include_once("lib/lib_core.php");
global $pathOutAdR;

$db = new database();
// WHERE usecase  LIKE \"%-%\"
$ans = $db->query("SELECT * FROM Usecases");

$data = array();
while($res = $ans->fetch_assoc()){
   /*   $txt = '';// '\FloatBarrier'."\n";

      $txt .= $res["usecase"] ."\n";
      if($res["imagePath"]==''){
      $txt .= "NOIMAGE\n";
      }
      $data[$res["usecase"]] = $txt;

    */

   $uc = $res["usecase"];
   $uc = 'UC' . $uc;
   // $dad = $res["dad"];
   $title = check_punto($res["title"]);
   $des = check_punto($res["description"]);
   $pre = check_punto($res["precondition"]);
   $post = check_punto($res["postcondition"]);
   $img = $res["imagePath"];
   $img = trim($img);
   //   $imgdid = $res["didascalia"];
   $imgdid = "Diagramma per il caso d'uso $uc.";
   $act = check_punto($res["actors"]);
   $scen = $res["scene"];
   $altsc = $res["extensions"];

   /* if(!preg_match('/UC0-/i',$uc)){
      $imgdid = "Diagramma per il caso d'uso $uc.";
      }
    */
   //////////////////////////////
   // Latex non permette più di un punto nell'estensione dei file immagine
   $img = str_replace(".","_",$img);
   $img = str_replace("_png",".png",$img);
   //////////////////////////////
   // '\FloatBarrier'."\n";
   $txt = '';
   if($img != '' && !preg_match('/^UC0$/',$uc)){
      $txt .= "\\clearpage\n\n";
   }
   // preg_match('/^UC0$/',$uc)
   if($uc == 'UC0'){
      $txt .=<<<EOF
   \\FloatBarrier
   \\begin{figure}[ht]
   \\centering
   \\includegraphics[scale=0.45]{img/$img}
   \\caption{Visione generale dei casi d'uso}
\\end{figure}
\\FloatBarrier

EOF;
   }
   else{
      $txt .= "\\subsection{Caso d'uso $uc: $title}\n";
      $txt .= "\\begin{itemize}\n";


      if($img != ''){
         $txt .=<<<EOF
   \\FloatBarrier
   \\begin{figure}[ht]
   \\centering
   \\includegraphics[scale=0.45]{img/$img}
   \\caption{{$imgdid}}
\\end{figure}
\\FloatBarrier

EOF;
      }
      $txt .= "\\item[]\\textbf{Descrizione:} $des\n";
      $txt .= "\\item[]\\textbf{Attori:} $act \n";
      $txt .= "\\item[]\\textbf{Precondizione:} $pre \n";
      $txt .= "\\item[]\\textbf{Postcondizione:} $post \n";
      if($scen != ''){
         $txt .= "\\item[]\\textbf{Scenario:}\n";
         $txt .= "$scen \n";
      }
      if($altsc != ''){
         $txt .= "\\item[]\\textbf{Estensioni:}\n";
         $txt .= "$altsc \n";
      }
      $txt .= "\\end{itemize}\n\n";
   }
   // Elimina gli a capo stile windows creati da PHPmyadmin
   $txt = str_replace("\r\n","\n",$txt);
   $txt = str_replace("\r","\n",$txt);
   $data[$uc] = $txt;
}


// elenco delle chiavi da riordinare
$ks = array_keys($data);
// riordino con la mia funzione  di confronto
usort($ks,"compare_uc");
$FH = fopen($pathOutAdR."uccases.tex", "w");
if($FH ===false){
	die("ERRORE FILE");
}
// scrivere nell'ordine giusto
foreach($ks as $k){
   fwrite($FH,$data[$k]);
}
fclose($FH);


?>
