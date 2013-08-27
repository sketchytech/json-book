<?php

include_once('readjson.php');

// Chapter
$chapter_json = file_get_contents("burn.json");
$chapter = json_decode($chapter_json);

processObject($chapter);

function processObject($chapter) {

if(is_object($chapter)){
foreach($chapter as $key=>$value)
{
$title_tags = array("h1","h2","h3","h4","h5");
if(in_array($key, $title_tags)) echo "<".$key.">".$value."</".$key.">";

// Confirm that chapter is array
if (is_array($value)) {
$numberOfItems=count($value);
$i=0;
while ($i<$numberOfItems){
//arrayProcess($value);
if(is_string($value[$i])) echo "<b>STRING NEEDS DISPLAY</b>"; // Part of the error testing 
elseif(is_array($value[$i])) arrayProcess($value[$i]);
elseif(is_object($value[$i])) bounceObject($value[$i]);

$i++;
}
}
}
}
}
function arrayProcess($array){

if(is_string($array[0])){
$para=new paragraph;
$para->returnParagraph($array);
}

else processObject($array);
}

function bounceObject($object) {
processObject($object);
}

	?>
