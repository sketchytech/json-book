<?php

include_once('readjson.php');


// This index search currently only searches for strings within paragraphs not objects
// Chapter
$chapter_json = file_get_contents("burn.json");
$chapter = json_decode($chapter_json);

processObject($chapter,0);

function processObject($chapter,$itemNumber) {
$itemNumber++; // increment item when an object is processed - this shouldn't include objects within paragraphs
if(is_object($chapter)){
foreach($chapter as $key=>$value)
{

if (is_array($value)) {

$numberOfItems=count($value);
$i=0;
while ($i<$numberOfItems){
//arrayProcess($value);

if(is_array($value[$i])){
$itemNumber++; // increments number for each paragraph
arrayProcess($value[$i],$itemNumber);
}
elseif(is_object($value[$i])) bounceObject($value[$i],$itemNumber);

$i++;
}
}
if($key=="blockquote") echo "</".$key.">"; // this handles blockquotes that come between paragraphs but blockquotes within paragraphs must also be handled, because those are correct


}

}

}
function arrayProcess($array,$itemNumber){
$i=0;

while ($i<count($array)) {

if(is_string($array[$i])){
if(preg_match('/(what)/i',$array[$i])) {
// Replace fixed search term with $_GET from URL
$para=new paragraph;
$para->returnParagraph($array,'/(what)/i');
echo "Item ".$itemNumber; // this will be replaced by a hyperlink to chapter parser with itemNumber in query string, which will create an anchor point at the paragraph and take reader straight there
}
}
else if(is_object($array[$i])){
// Send to object to function with array to search styled string
//searchStyledString($array[$i],$array,$itemNumber);
echo "<b>OBJECT IN PARA</B>";

}
//else processObject($array,$itemNumber); // check sense of this
$i++;

}
}

function bounceObject($object,$itemNumber) {
processObject($object,$itemNumber);
}

function searchStyledString ($object,$paragraph,$itemNumber){
foreach($object as $key=>$value)
{
/*if(is_string($value)) {
if(preg_match('/(what)/i',$value)) {
// Replace fixed search term with $_GET from URL
//$para=new paragraph;
//$para->returnParagraph($array,'/(wigan)/i');
echo "Wigan Item ".$itemNumber; 
}
}*/
echo $value;
}
}

	?>
