<?php

// Builds a reference list from a json file
$reference_json = file_get_contents("reference.json");
$reference = json_decode($reference_json);
/*global $flag_array_depth;
$flag_array_depth=0;*/
global $flag_references_processed;
$flag_references_processed=0;
global $number_of_references;
$number_of_references=0;
global $reference_array;

processObject($reference);
// After chapter has been processed create note list and print


function processObject($reference) {
global $reference_array;

if(key($reference)=='references'&&is_array($reference->{'references'})){
$reference_array=$reference->{'references'};
global $number_of_references;
$number_of_references=count($reference->{'references'});
echo "Number of references: ".$number_of_references."<br />";
echo "<h2>References</h2><ul style='list-style-type: none;'>";
extractReference($reference->{'references'}); // Send complete reference list as array

}
}

function extractReference($array){
global $reference_array;
global $flag_references_processed;
global $number_of_references;
if($flag_references_processed<$number_of_references) processReference($array[$flag_references_processed]);
else echo "</ul>";
}



function processReference ($array) {
global $reference_array;
global $flag_references_processed;
echo "<li>";
// 0 = Surname, 1 = initials, 2 = date, 3 = edited (or text if not edited), 4 = text if it is edited

// Process names and initials first
$i=0;
while ($i<count($array[0])) {
echo $array[0][$i].", ".$array[1][$i];
if ($i==count($array[0])-2) echo " and ";
if ($i<count($array[0])-2) echo ", "; 
$i++;
}
// process rest of content based on whether it is an edited book or authored work
if ($array[3]=='edited'&&count($array[0])>1) echo " (eds) (".$array[2].") ".$array[4];
else if ($array[3]=='edited'&&count($array[0])==1) echo " (ed.) (".$array[2].") ".$array[4];
else echo " (".$array[2].") ".$array[3];
echo "</li>";
$flag_references_processed++;
extractReference($reference_array);
}

// Next step is feed these references to location in the text by searching (i.e. preg_match) this will be done and displayed in similar way to notes

?>
