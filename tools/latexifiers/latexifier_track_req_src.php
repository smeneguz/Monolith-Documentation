<?php
include_once("lib/lib_core.php");
global $pathOutAdR;
////////////////////////////////     TRACCIAMENTO

$inizioTabellaTracciamento =<<<EOF
\\begin{center}
\\begin{longtable}{|
*{1}{>{\centering\arraybackslash}p{5cm}|}
*{1}{>{\centering\arraybackslash}p{5cm}|}}
\\hline \\textbf{Fonte} & \\textbf{Requisiti}\\\\
\\hline \\endhead
\\hline \\endfoot

EOF;


// diversa da altri file
$fineTabella =<<<EOF
\\end{longtable}
\\captionof{table}{Tracciamento requisiti - sorgenti}
\\end{center}
EOF;

$FH = fopen($pathOutAdR."tracciamento_src_req.tex", "w");

if($FH ===false){
	die("ERRORE FILE");
}


////////////////////////////////////////////////////////////////////////////////////////////



function track_f_r($sdk,$FH){
	global /*$FH, */$inizioTabellaTracciamento, $fineTabella;
	fwrite($FH,$inizioTabellaTracciamento."\n");
   $db = new database();
   $src ='';
   if($sdk){
      $src = 'select distinct uc AS source from UcReq  WHERE INSTR(req,\'-\')=0 UNION select distinct source from SourceReq  WHERE INSTR(req,\'-\')=0';
   }
   else{
      $src = 'select distinct uc AS source from UcReq  WHERE INSTR(req,\'-\')>0 UNION select distinct source from SourceReq  WHERE INSTR(req,\'-\')>0';
   }
   $ans = $db->query($src);
   $fonti = array();
   while($res = $ans->fetch_assoc()){
      array_push($fonti,$res['source']);
   }
   sort($fonti);

   // per ciascuna fonte cerco i requisiti associati
   foreach($fonti as $v){
      $src = '';
      if($sdk){
         $src = "SELECT requirement, type, importance from Requirements WHERE requirement IN (SELECT req from UcReq where uc='$v' UNION SELECT req from SourceReq WHERE source='$v') AND INSTR(requirement,'-')=0";
      }
      else{
         $src = "SELECT requirement, type, importance from Requirements WHERE requirement IN (SELECT req from UcReq where uc='$v' UNION SELECT req from SourceReq WHERE source='$v') AND INSTR(requirement,'-')>0";
      }
      $ans = $db->query($src);
		if(!preg_match('/interno/i',$v) && !preg_match('/capitolato/i',$v) && !preg_match('/verbale/i',$v)){
			$v = 'UC' . $v;
		}
      fwrite($FH,$v . ' & ');
      fwrite($FH,'\makecell{');
      $cella='';
      while($res = $ans->fetch_assoc()){
         $cella = $cella . 'R' . ucfirst($res['importance']) . ucfirst($res['type']) . $res['requirement'] . "\n\\\\";
      }
      $cella = substr($cella,0,strlen($cella)-2);
      fwrite($FH, $cella);
      fwrite($FH,"}\\\\\\hline\n");
   }
   fwrite($FH,$fineTabella."\n");
}



//function track_r_f($sdk)



fwrite($FH,'\subsection{Tracciamento fonti - requisiti per l\'SDK}'."\n");
track_f_r(1,$FH);
fwrite($FH,'\subsection{Tracciamento fonti - requisiti per le bolle}'."\n");
track_f_r(0,$FH);

fclose($FH);
?>
