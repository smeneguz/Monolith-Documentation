<?php
include_once("lib/lib_core.php");

global $pathOutDdP;
////////////////////////////////     TRACCIAMENTO

$inizioTabellaTracciamento =<<<EOF
\\begin{center}
\\begin{longtable}{|
*{1}{>{\centering\arraybackslash}m{7.5cm}|}
*{1}{>{\centering\arraybackslash}m{2.5cm}|}}
\\hline \\textbf{Class} & \\textbf{Requisiti}\\\\
\\hline \\endhead
\\hline \\endfoot

EOF;


// diversa da altri file
$fineTabella =<<<EOF
\\end{longtable}
\\captionof{table}{Tracciamento classi - requisiti}
\\end{center}
EOF;

$FH = fopen($pathOutDdP."tracciamento_class_req.tex", "w");
if($FH ===false){
die("ERRORE FILE");
}

$data = [];
$db = new database();
$ans = $db->query("SELECT class, requirement, component FROM ReqClass NATURAL JOIN Classes order by component");
fwrite($FH,$inizioTabellaTracciamento."\n");
while($res = $ans->fetch_assoc()){
	if(!isset($data[$res['component'].'::'.$res['class']])){
		$data[$res['component'].'::'.$res['class']] = [];
	}
   array_push($data[$res['component'].'::'.$res['class']], $res['requirement']);
}

$keys = array_keys($data);
sort($keys);

foreach($keys as $k){
   fwrite($FH, gestioneParoleSuperLunghe(str_replace("_","\\_",$k),1) . ' & ');
   fwrite($FH,'\makecell{');
   foreach($data[$k] as $r){
      if($r != ''){
         fwrite($FH,printRequirement($r));
         fwrite($FH, "\n\\\\");
      }
   }
   fwrite($FH,"}\\\\\\hline\n");
}

fwrite($FH,$fineTabella."\n");


fclose($FH);
?>
