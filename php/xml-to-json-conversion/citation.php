<?php

$xml=file_get_contents('alchemist.xml');

// Currently captures citations written (Smith, 1990: 80) and (Smith, 1990: 80; Jones, 2001: 90)
// Extend for multiple authors 
$pattern='/(\(|; )([A-Za-z]{1,}), ([0-9]{1,}): ([0-9]{1,})/';
$replacement='<citation>$2;$3;$4</citation>';
$subject=$xml;

// Develop regex for finding notes and placing them into json-book format

$xml_revised=preg_replace($pattern, $replacement,$subject);

?>
	
