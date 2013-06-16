<?php
$pandocParam = array(
				'--section-divs' => true,
				'-t' => 'html5',
				'-s' => true,
				'--template' => '../template/revealjs.html',
				'-o' => 'index.html',
				'-V' => array(
					'title' => false,
					'subtitle' => false,
					'theme' => 'beige',
					)
				);

$cliOption = getopt('f:t::s::o::');
if($cliOption){
	if(file_exists($cliOption['f']) && is_file($cliOption['f'])){
		if(isset($cliOption['t'])){
			$pandocParam['-V']['title'] = $cliOption['t'];
		}else{
			$lines = file($cliOption['f']);
			$pandocParam['-V']['title'] = trim($lines[0]);
		}
		if(isset($cliOption['s'])) $pandocParam['-V']['subtitle'] = $cliOption['s'];
		if(isset($cliOption['o'])) $pandocParam['-o'] = $cliOption['o'];
	}
	$cmdParam = array();
	foreach ($pandocParam as $name => $value){
		switch (gettype($value)) {
			case 'boolean':
				if($value){
					$cmdParam[] = $name;
				}
				break;
			case 'string':
			case 'integer':
			case 'double':
				$cmdParam[] = $name;
				$cmdParam[] = $value;
				break;
			case 'array':
				foreach ($value as $subName => $subValue){
					if($subValue){
						$cmdParam[] = $name;
						$cmdParam[] = '"'.$subName.':'.$subValue.'"';
					}
				}
				break;
		}
	}
	exec('pandoc '.join(' ', $cmdParam));
}else{
	$helpText=<<<EOT

--- Help ---

-f=<Markdown File(required)>
-t=<title(option)>
-s=<subtitle(option)>
-o=<output File(option)>

EOT;
	echo $helpText;
}
//pandoc --section-divs -t html5 -s --template ../template/revealjs.html -o index.html -V theme:beige -V title:姫路Git勉強会 -V subtitle:～のび太、ギットギトにしてやるよ～ %*
?>
