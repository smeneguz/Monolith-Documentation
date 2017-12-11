<?php 

function makemenu(){
	$menu =<<<EOF
	<li><a href="requirements.php">Requisiti</a></li>
	<li><a href="usecase.php">Use Case</a></li>
	<li><a href="vis_uc.php">Visualizzazione Use Case</a></li>
	<li><a href="vis_req.php">Visualizzazione Requisiti</a></li>
	<li><a href="latexifier_uc.php">Latex output -- usecase </a></li>
   <li><a href="latexifier_re.php">Latex output -- requisiti </a></li>

EOF;
	echo $menu;

}


?>
