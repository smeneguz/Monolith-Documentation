<?php

include_once("lib/lib_core.php");

global $pathOutDdP;
$db = new database();
$ans = $db->query("SELECT * FROM Components  ");  // WHERE INSTR(component,'Monolith')>0
$FH = fopen($pathOutDdP."components.tex", "w");
$components;

if($FH ===false){
	die("ERRORE FILE");
}
while($res = $ans->fetch_assoc()){
   $key = $res['component'];
   $comp =  $txt = str_replace("_","\\_",pulisci_chars($res['component']));
	$comp = checkAcapo($comp);
   $des = pulisci_chars($res['description']);
   $img = pulisci_chars($res['imagePath']);
   $did = pulisci_chars($res['didascalia']);


   if(!$did){
      $did = "Diagramma per $comp.";
   }
   //////////////////////////////

   $txt = '';
  /* if($img != '' && $img != 'Monolith'){
      $txt .= "\\clearpage\n\n";
   }*/

   //////////////////////////////
   // GET SIZE
   //////////////////////////////

   $txt .= '\\subsubsection{'.$comp .'}'."\n";

   if($img != ''){
      $size = getimagesize($pathOutDdP.'img/'.$img.'.png');
      $width = $size[0];
      // print '!!/home/nick/Documenti/INGEGNERIA/Obelix/2-RP/Esterni/SpT/img/'.$img.'.png!!'."\n";
   //   print "<<$comp  ---  ". ceil($width/1.333) .">>\n"; // conversione da pixel (png) a pti (tex)
      // 345 è il \textwidth
      if((ceil($width/1.333)/345)>3.2){

         $txt .=<<<EOF
   \\FloatBarrier
 %  \\begin{sidewaysfigure}[ht]
%\\begin{center}
 \\makebox[\\textwidth][c]{
\\rotatebox{90}{
% \\centering
\\begin{minipage}{0.8\\textheight}
   \\includegraphics[width=\\textwidth,keepaspectratio]{img/$img}
  \\captionof{figure}{{$did}}
\\end{minipage}}
}
%\\end{center}
%\\end{sidewaysfigure}
\\FloatBarrier

EOF;
      }
      else{
         $txt .=<<<EOF
   \\FloatBarrier
   \\begin{figure}[ht]
   \\centering
\\includegraphics[width=\\textwidth,keepaspectratio]{img/$img}
   \\caption{{$did}}
\\end{figure}
\\FloatBarrier

EOF;
      }
   }
   /*
      ////////////////////////////////   //////
      $txt .= '\\subsubsection{'.$comp .'}'."\n";
      if($img != ''){
      $txt .=<<<EOF
      \\FloatBarrier
      \\begin{figure}[ht]
      \\centering
      \\includegraphics[width=\\textwidth,keepaspectratio]{img/$img}
      \\caption{{$did}}
      \\end{figure}
      \\FloatBarrier

      EOF;
      }*/
   // $txt .= "\\begin{itemize}\n";
   $txt .= "\\textbf{Descrizione}:\\\\\n";
   $txt .= " $des \n";

   //////////////////////
   global $db;
   $ans2 = $db->query("SELECT class FROM Classes WHERE component=\"$key\" ORDER BY class");
   if($ans2->num_rows){
      $txt .= "\\\\ \\textbf{Classi contenute}:\\\\\n";
      $txt .= "\\begin{itemize}\n";
      while($res2 = $ans2->fetch_assoc()){
         $txt .= "\\item " . $res2["class"] . "\n";
      }
      $txt .= "\\end{itemize}\n";
   }
   $txt .= "\n\n";
   ///////////////////////

   $components[$comp] = $txt;
}

$sentinella = 0;
fwrite($FH,'\\subsection{Architettura generale - Componenti del sistema}'."\n");
foreach($components as $k => $v){
   if(strstr($k,'Monolith')){
      if($sentinella){
         fwrite($FH,"\\clearpage\n\n");
      }
      fwrite($FH,$v);
      $sentinella = 1;
   }
}
$sentinella = 0;
fwrite($FH,'\\clearpage \\subsection{Architettura generale - Bolle Demo}'."\n");
foreach($components as $k => $v){
   if(!strstr($k,'Monolith')){
      if($sentinella){
         fwrite($FH,"\\clearpage\n\n");
      }
      fwrite($FH,$v);
      $sentinella = 1;
   }
}
fclose($FH);


?>
