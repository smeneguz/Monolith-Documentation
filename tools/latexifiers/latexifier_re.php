<?php

include_once("lib/lib_core.php");
global $pathOutAdR;

$db = new database();
// WHERE usecase  LIKE \"%-%\"
$ans = $db->query("SELECT * FROM Requirements");

$fun = array();
$qua = array();
$dic = array();
while($res = $ans->fetch_assoc()){

   $req = $res["requirement"];
   $imp = $res["importance"];
   $tipo = $res["type"];
   $des = check_punto($res["description"]);
   // SORGENTI
   //$src = $res["source"];

  // echo $req ."           ". $des ."<br/>";

   //////////////////////////////
   /*
      utilità
      Ob: Obbligatori
      De: Desiderabili
      Op: Opzionali
      tipo:
      Fu: Funzionale
      Qu: Qualitativo
      Di: Dichiarativo
    */
   $imp = trim($imp);
   $tipo = trim($tipo);
   $txt = 'R'. ucfirst($imp) . ucfirst($tipo). $req;
   switch($imp){
      case 'ob':
         $imp = 'Obbligatorio';
         break;
      case 'de':
         $imp = 'Desiderabile';
         break;
      case 'op':
         $imp = 'Opzionale';
         break;
      default:
         die("Importanza non impostata correttamente: <$imp>\n\n");
   }

   switch($tipo){
      case 'fu':
         $tipo = 'Funzionale';
         break;
      case 'qu':
         $tipo = 'Qualitativo';
         break;
      case 'di':
         $tipo = 'Dichiarativo';
         break;
      default:
         die("Tipologia non impostata correttamente: <$tipo>\n\n");
   }

   $txt .= ' & \\makecell{' . "$imp \\\\ $tipo} & $des & \\makecell{";;
   // fonti:

   $src = array();
   $ans1 = $db->query("SELECT CONCAT('UC',uc) AS source FROM UcReq WHERE req='$req' UNION SELECT source FROM SourceReq WHERE req='$req'");
   while($res1 = $ans1->fetch_assoc()){
      array_push($src, $res1['source']);
   }

   sort($src);

   $txt .= implode('\\\\',$src)."}\\\\\n\\hline\n";

   // Elimina gli a capo stile windows creati da PHPmyadmin
   $txt = str_replace("\r\n","\n",$txt);
   $txt = str_replace("\r","\n",$txt);
   switch($tipo){
      case 'Funzionale':
         $fun[$req] = $txt;
         break;
      case 'Qualitativo':
         $qua[$req] = $txt;
         break;
      case 'Dichiarativo':
         $dic[$req] = $txt;
         break;
      default:
         die("crimson death\n\n");
   }
}

//p{0.2\columnwidth}p{0.6\textwidth}p{0.2\columnwidth}
// {|l|c|l|c|}
$inizioTabella =<<<EOF
\\begin{center}
\\begin{longtable}{|
*{1}{>{\centering\arraybackslash}p{2.5cm}|}
*{1}{>{\centering\arraybackslash}p{2cm}|}
*{1}{>{\centering\arraybackslash}p{5cm}|}
*{1}{>{\centering\arraybackslash}p{2.5cm}|}}
\\hline \\textbf{Requisito} & \\textbf{Tipologia} & \\textbf{Descrizione} & \\textbf{Fonti}\\\\
\\hline \\endhead
\\hline \\endfoot

EOF;
function fineTabella($caption){
   $fineTabella =<<<EOF
\\hline
\\end{longtable}
\\captionof{table}{{$caption}}
\\end{center}
EOF;

   return $fineTabella;
}
$FH = fopen($pathOutAdR . "requirements.tex", "w");
if($FH ===false){
	die("ERRORE FILE");
}

if(count($fun)){
   $inizio = "\\subsection{Requisiti funzionali}\n\n";
   fwrite($FH,$inizio);
   // elenco delle chiavi da riordinare
   $ks = array_keys($fun);
   // riordino con la mia funzione  di confronto
   usort($ks,"compare_uc");

   // scrivere nell'ordine giusto
   fwrite($FH,$inizioTabella."\n");
   foreach($ks as $k){
      fwrite($FH,$fun[$k]."\n");
   }
   fwrite($FH,fineTabella('Requisiti funzionali')."\n");
}
if(count($qua)){
   $inizio = "\\subsection{Requisiti qualitativi}\n\n";
   fwrite($FH,$inizio);
   // elenco delle chiavi da riordinare
   $ks = array_keys($qua);
   // riordino con la mia funzione  di confronto
   usort($ks,"compare_uc");

   // scrivere nell'ordine giusto
   fwrite($FH,$inizioTabella."\n");
   foreach($ks as $k){
      fwrite($FH,$qua[$k]."\n");
   }
   fwrite($FH,fineTabella('Requisiti qualitativi')."\n");
}
if(count($dic)){
   $inizio = "\\subsection{Requisiti dichiarativi}\n\n";
   fwrite($FH,$inizio);
   // elenco delle chiavi da riordinare
   $ks = array_keys($dic);
   // riordino con la mia funzione  di confronto
   usort($ks,"compare_uc");

   // scrivere nell'ordine giusto
   fwrite($FH,$inizioTabella."\n");
   foreach($ks as $k){
      fwrite($FH,$dic[$k]."\n");
   }
   fwrite($FH,fineTabella('Requisiti dichiarativi')."\n");
}

/////////////////////////////////////////////////////////////////////////////////////////////
// RIASSUNTO

function conta_riepilogo($i,$t){
   $db = new database();
   $risposta = $db->query("select count(*) as num from Requirements WHERE importance='$i' AND type='$t'");
   $r = $risposta->fetch_assoc();
   $r = $r['num'];
   if($r){
      return $r;
   }
   else{
      return 0;
   }
}

$risposta = $db->query("select count(*) as num from Requirements");
$r = $risposta->fetch_assoc();
$tot = $r['num'];

$obfu = conta_riepilogo('ob','fu');
$obqu = conta_riepilogo('ob','qu');
$obdi = conta_riepilogo('ob','di');
$defu = conta_riepilogo('de','fu');
$dequ = conta_riepilogo('de','qu');
$dedi = conta_riepilogo('de','di');
$opfu = conta_riepilogo('op','fu');
$opqu = conta_riepilogo('op','qu');
$opdi = conta_riepilogo('op','di');

fwrite($FH,"\\subsection{Riepilogo requisiti}\n\n");
$txttex =<<<EOF
\\begin{center}
  \\centering
  \\begin{tabular}{|l|c|c|c|}
    \\hline
      & Funzionali & Qualitativi & Dichiarativi   \\\\
\\hline
Obbligatori &      $obfu     &    $obqu     & $obdi       \\\\
\\hline
Desiderabili &     $defu     &     $dequ     & $dedi    \\\\
\\hline
Opzionali   &      $opfu     &    $opqu     & $opdi    \\\\
\\hline
  \\end{tabular}
  \\captionof{table}{Riepilogo del numero di requisiti individuati.}
\\end{center}
EOF;

fwrite($FH,"I $tot requisiti individuati si suddividono come segue:\n");

fwrite($FH,"$txttex\n\n");


fclose($FH);

?>
