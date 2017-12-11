<?php

include_once("lib/lib_core.php");

global $pathOutDdP;
$db = new database();
$ans = $db->query("SELECT * FROM Classes ORDER BY component");
$FH = fopen($pathOutDdP."classes.tex", "w");
if($FH ===false){
	die("ERRORE FILE");
}
$classes;
$sentinella = 0;
while($res = $ans->fetch_assoc()){
   $key = $res['class'];
   $class =  pulisci_chars($res['class']);
   $des = trim(pulisci_chars($res['description']));
   $app = trim(pulisci_chars($res['applications']));
   $comp = $txt = str_replace("_","\\_",pulisci_chars($res['component']));
   $comp = checkAcapo($comp);
   $img = $res['imagePath'];
   //$img = 'single-'.$key;

   //////////////////////////////

   $txt = '';

   //////

   $txt .= '\\subsubsection{'/*.$comp . '::'*/ .$class .'}'."\n";
   $txt .= '\\textbf{Componente:}  '.$comp."\\\\\n";

   $compPiccolo = substr(strrchr($comp,':'),1);
   if(!$compPiccolo){
      $compPiccolo = $comp;
   }
   //////
   if($img != ''){
      $txt .=<<<EOF
   \\FloatBarrier
   \\begin{figure}[ht]
   \\centering
   \\includegraphics[width=0.6\\textwidth]{img/$img}
   \\caption{{Diagramma per $class in $compPiccolo}}
\\end{figure}
\\FloatBarrier

EOF;
   }

   $txt .= "\\textbf{Descrizione}\\\\\n";
   $txt .= "$des \n\n\n";

   $txt .= "\\textbf{Applicazioni}\\\\\n";
   $txt .= "$app \n\n\n";
   //////////////////////

   ///////////////////////
   $classes[$class] = [$txt,$comp];
}

fwrite($FH,"\\clearpage\n\n");
$sentinella = 0;
fwrite($FH,'\\subsection{Architettura di dettaglio - Classi del sistema Monolith}');
fwrite($FH,"\n".'Nel caso dei componenti React la descrizione dello schema dell\'oggetto props passato come argomento al costruttore è fornita indirettamente dall\'elenco delle props.'."\n");
foreach($classes as $k => $v){
   if(strstr($v[1],'Monolith')){
      if($sentinella){
         fwrite($FH,"\\clearpage\n\n");
      }
      fwrite($FH,$v[0]);
      $sentinella = 1;
   }
}
$sentinella = 0;
fwrite($FH,'\\subsection{Architettura di dettaglio - Classi delle bolle demo}');
fwrite($FH,"\n".'Nel caso dei componenti React la descrizione dello schema dell\'oggetto props passato come argomento al costruttore è fornita indirettamente dall\'elenco delle props.'."\n");
foreach($classes as $k => $v){
   if(!strstr($v[1],'Monolith')){
      if($sentinella){
         fwrite($FH,"\\clearpage\n\n");
      }
      fwrite($FH,$v[0]);
      $sentinella = 1;
   }
}
fclose($FH);
?>
