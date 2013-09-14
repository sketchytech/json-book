<?php

include_once('readjson.php');
global $searchTerm;
if(isset($_GET['search_term'])){
 $searchTerm=$_GET['search_term'];
}
else $searchTerm='what'; 
global $itemNumber;
$itemNumber=0;
// This index search currently only searches for strings within paragraphs not objects
// Chapter
$chapter_json = file_get_contents("burn.json");
$chapter = json_decode($chapter_json);

processObject($chapter);

function processObject($chapter) {
global $itemNumber;
//$itemNumber++; // increment item when an object is processed - this shouldn't include objects within paragraphs
if(is_object($chapter)){
foreach($chapter as $key=>$value)
{
// the item number needs to be incremented with each paragraph, heading, blockquote but not character styles

if (is_array($value)) {

$numberOfItems=count($value);
$i=0;
while ($i<$numberOfItems){
//arrayProcess($value);

if(is_array($value[$i])){
//$itemNumber++; // increments number for each paragraph
arrayProcess($value[$i]);
}
elseif(is_object($value[$i])) bounceObject($value[$i]);

$i++;
}
}
if($key=="blockquote"&&is_array($value)) {
 // include paragraphs within blockquotes
//$itemNumber=$itemNumber+count($value); // NOT NECESSARY
}



}

}

}
function arrayProcess($array){
$i=0;
global $itemNumber;
// Add to paragraph number for each array processed - we need to include headings but not styled text as well	
$itemNumber++;
while ($i<count($array)) {
global $searchTerm;
if(is_string($array[$i])){

if(preg_match('/('.$searchTerm.')/i',$array[$i])) {
// Replace fixed search term with $_GET from URL
$para=new paragraph;
$para->returnParagraph($array,$itemNumber,'/('.$searchTerm.')/i');

$i=count($array); // Once the word has been found once in the sentence, force the end of the search for this paragraph 
}
}
else if(is_object($array[$i])){

// Send to object to function with array to search styled string
//searchStyledString($array[$i],$array,$itemNumber);
$basic_tags = array("h1","h2","h3","h4","h5","b","i","sup","sub","blockquote"); // Finds text with basic styles - should be extended for linked styles
$key=key($array[$i]);
if(in_array($key,$basic_tags)&&preg_match('/('.$searchTerm.')/i',$array[$i]->{$key})){

$para=new paragraph;
$para->returnParagraph($array,$itemNumber,'/('.$searchTerm.')/i');
// Tap on the hyperlink to go to place in text - adapt so that paragraph can be tapped.

$i=count($array); // Once the word has been found once in the sentence, force the end of the search for this paragraph 
}

}
//else processObject($array,$itemNumber); // check sense of this
$i++;

}
}

function bounceObject($object) {
global $itemNumber;
processObject($object);
$itemNumber++;
}

function searchStyledString ($object,$paragraph){

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
