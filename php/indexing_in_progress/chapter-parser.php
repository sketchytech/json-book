<?php

include_once('readjson.php');
$itemNumber=0;
$linkedItemNumber=6;
global $notes;
$notes=array();


// Chapter
$chapter_json = file_get_contents("burn.json");
$chapter = json_decode($chapter_json);

processObject($chapter,$itemNumber,$linkedItemNumber);
printNotes();  // After chapter has been processed, notes are printed at end


function processObject($chapter,$itemNumber,$linkedItemNumber) {


if(is_object($chapter)){

foreach($chapter as $key=>$value)
{
if($itemNumber==$linkedItemNumber) echo "<a id='".$itemNumber."'></a>";
$title_tags = array("h1","h2","h3","h4","h5");
if(in_array($key, $title_tags)){ 
echo "<".$key.">".$value."</".$key.">";
echo "<a id='".$itemNumber."'>ANCHORPOINT ".$itemNumber."</a>";
$itemNumber++;
}
if($key=="blockquote") {
echo "<".$key.">";

}
// Confirm that chapter is array
if (is_array($value)) {

$numberOfItems=count($value);
$i=0;
while ($i<$numberOfItems){
$itemNumber++;
//arrayProcess($value);
if(is_string($value[$i])) {
echo $value[$i];
}// Part of the error testing 
elseif(is_array($value[$i])) arrayProcess($value[$i],$itemNumber,$linkedItemNumber);
elseif(is_object($value[$i])) bounceObject($value[$i],$itemNumber,$linkedItemNumber);

$i++;
}
}
if($key=="blockquote") {echo "</".$key.">"; // this handles blockquotes that come between paragraphs but blockquotes within paragraphs must also be handled, because those are correct
echo "<a id='".$itemNumber."'>ANCHORPOINT ".$itemNumber."</a>";
$itemNumber++;}
}
}
}
function arrayProcess($array,$itemNumber,$linkedItemNumber){

if(is_string($array[0])){
global $notes;
$para=new paragraph;
$para->returnParagraph($array);
$new_notes=$para->returnNotes();
if($new_notes) $notes=array_merge($notes,$new_notes);
}

else processObject($array,$itemNumber,$linkedItemNumber);
echo "<a id='".$itemNumber."'>ANCHORPOINT ".$itemNumber."</a>";
$itemNumber++;
}

function bounceObject($object,$itemNumber,$linkedItemNumber) {
processObject($object,$itemNumber,$linkedItemNumber);
$itemNumber++;
}
function printNotes(){
global $notes;
if (count($notes)>0){

if (count($notes)==1) echo "<h2>Note</h2>";
else echo "<h2>Notes</h2>";
echo "<ol>";
$i=0;
while($i<count($notes)){
echo "<li>".$notes[$i]."</li>";
$i++;
}
echo "</ol>";}
}


?>
<script type="text/javascript">
window.location.hash="20";
</script>
