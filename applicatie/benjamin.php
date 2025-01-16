<?php

$synths = [
    'Moog'=> ['Grandmother', 'Minimoog',],
    'Korg' => ['MS-10', 'VC-10'],
    'Roland' => ['Juno 106', 'TR-808']
  ];

foreach($synths as $merknaam => $modellen){
    foreach($modellen as $synth){
        echo "*$synth - $merknaam<br>";
    }
}

$a = 50;
$b = 10;
// echo $a + b;
echo $a / $b;

?>