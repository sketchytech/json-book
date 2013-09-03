<?php
// TODO: Make file loading dynamic
$xml=file_get_contents('alchemist.xml');

// Currently captures citations written (Smith, 1990: 80) and (Smith, 1990: 80; Jones, 2001: 90)
// Extend for multiple authors 
$pattern='/(\(|; )([A-Za-z]{1,}), ([0-9]{1,}|[0-9]{4}\/[0-9]{4}): ([0-9\-&#x2013;]{1,})/';
$replacement='<citation>$2;$3;$4</citation>';
$subject=$xml;

$xml_revised=preg_replace($pattern, $replacement,$subject);

// replace en dash with hyphen for convenience (translate back to en dash when parsing json)
$pattern='/&#x2013;/';
$replacement='-';
$subject=$xml_revised;
// Develop regex for finding notes and placing them into json-book format
$xml_revised=preg_replace($pattern, $replacement,$subject);



?>
