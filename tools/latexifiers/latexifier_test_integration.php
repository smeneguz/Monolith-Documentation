<?php

include_once("lib/lib_core.php");
global $pathOutPdQ;

$db = new database();
// WHERE usecase  LIKE \"%-%\"
$ans = $db->query("SELECT * FROM IntegrationTest");

$data = array();
while($res = $ans->fetch_assoc()){
   $test = 'TI' . $res['idTest'];
   $component = str_replace('_', '\\_', $res['component']);
   if(strlen($component)>30){
     $component = str_lreplace('::', ':: ', $component);
   }

   $des = '\\textbf{' . $component . '}: '.
     check_punto($res["description"]);
   $impl = $res['implemented'];
   $sup = $res['satisfied'];
   //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
   $txt = $test . ' & ' . $des . ' & ' . colorTestStatus($impl) . ' & '. colorTestSup($sup) . '\\\\' ."\n \\hline \n";
   //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
   // Elimina gli a capo stile windows creati da PHPmyadmin
   $txt = str_replace("\r\n","\n",$txt);
   $txt = str_replace("\r","\n",$txt);
   $data[$res['idTest']] = $txt;
}
// elenco delle chiavi da riordinare
$ks = array_keys($data);
// riordino con la mia funzione  di confronto
usort($ks,"compare_uc");
$FH = fopen($pathOutPdQ."integration_test.tex", "w");
if($FH ===false){
   die("ERRORE FILE");
}
fwrite($FH, inizioTabella());
// scrivere nell'ordine giusto
foreach($ks as $k){
   fwrite($FH,$data[$k]);
}
fwrite($FH, fineTabella('Test di integrazione'));
fclose($FH);



function inizioTabella() {
$inizioTabella =<<<EOF
\\begin{center}
\\begin{longtable}{|
*{1}{>{\centering\arraybackslash}p{1cm}|}
*{1}{>{\centering\arraybackslash}p{6cm}|}
*{1}{>{\centering\arraybackslash}p{2.5cm}|}
*{1}{>{\centering\arraybackslash}p{2cm}|}}
\\hline \\textbf{Test} & \\textbf{Descrizione} & \\textbf{Stato} & \\textbf{Superato} \\\\
\\hline \\endhead


EOF;

return $inizioTabella;
}

function fineTabella($caption){
   $fineTabella =<<<EOF
\\end{longtable}
\\captionof{table}{{$caption}}
\\end{center}
EOF;

   return $fineTabella;
}

?>
