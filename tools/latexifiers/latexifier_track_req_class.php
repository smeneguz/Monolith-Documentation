<?php
include_once("lib/lib_core.php");
global $pathOutDdP;

////////////////////////////////     TRACCIAMENTO

$inizioTabellaTracciamento =<<<EOF
\\begin{center}
\\begin{longtable}{|
*{1}{>{\\centering\\arraybackslash}m{2.5cm}|}
*{1}{>{\\centering\\arraybackslash}m{7.5cm}|}}
\\hline \\textbf{Requisiti} & \\textbf{Classi}\\\\
\\hline \\endhead
\\hline \\endfoot

EOF;


// diversa da altri file
$fineTabella =<<<EOF
\\end{longtable}
\\captionof{table}{Tracciamento requisiti - classi}
\\end{center}
EOF;

$FH = fopen($pathOutDdP."tracciamento_req_class.tex", "w");
if($FH ===false){
	die("ERRORE FILE");
}

$data = [];
$db = new database();
$ans = $db->query("SELECT class, requirement, component FROM ReqClass NATURAL JOIN Classes order by component");
fwrite($FH,$inizioTabellaTracciamento."\n");
while($res = $ans->fetch_assoc()){
   if(!isset($data[$res['requirement']])){
      $data[$res['requirement']] =[];
   }
   array_push($data[$res['requirement']], $res['component'].'::'.$res['class']);
}

$keys = array_keys($data);
sort($keys);

foreach($keys as $k){
   while($datapiece = array_splice($data[$k],0,28)){
      fwrite($FH,printRequirement($k) . ' & ');
      fwrite($FH,'\makecell[l]{');
      $count = 1;
      foreach($datapiece as $r){
         if($r != ''){
            fwrite($FH,gestioneParoleSuperLunghe(str_replace("_","\\_",$r),0));
            fwrite($FH, "\n\\\\");
            $count++;
         }
      }
      fwrite($FH,"}\\\\\\hline\n");
   }
}

fwrite($FH,$fineTabella."\n");






fclose($FH);
?>
