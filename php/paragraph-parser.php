<?php

include_once('readjson.php');

$paragraph_json = file_get_contents("paragraph.json");
$paragraph = json_decode($paragraph_json);

$para=new paragraph;
$para->returnParagraph($paragraph);


?>
