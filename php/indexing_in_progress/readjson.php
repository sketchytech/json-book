<?php

// This file processes the paragraph level content

class paragraph {
// a list of styles that require nothing but replication in HTML
public $basic_tags = array("b","i","sup","sub","blockquote");
// a list of styles that require special handling
public $linking_tags = array("a","note","ref");
// an array of the notes contained in the entire chapter
public $notes=array();
// an array of the notes contained in the current paragraph being parsed
public $paragraph_notes=array();
// an array of the reference citations contained in the current paragraph being parsed
public $paragraph_citations=array();

// This function parses whole paragraphs sent to it by chapter-parser.php and index-parser.php
function returnParagraph($paragraph,$itemNumber,$found=NULL)
{
// empty the $paragraph_notes array so that we start afresh for new para
$this->paragraph_notes=array();
// empty the $paragraph_citations array so that we start afresh for new para
$this->paragraph_citations=array();
// open paragraph with a <p> tag and give it an id tag
echo "<p id='".$itemNumber."'>";
// $note_flag to be set if a paragraph contains notes
$note_flag=0;
// $citation_flag to be set if a paragraph contains citations
$citation_flag=0;
$i=0;
// formats paragraph
while ($i<count($paragraph))
{
// If $found text has been sent it is highlighted and hyperlinked to anchor point in text (chapter-parser.php)
if	(is_string($paragraph[$i])&&isset($found)) echo preg_replace($found,"<a href='chapter-parser.php#".$itemNumber."'  style='background-color:yellow;'>$0</a>",$paragraph[$i]);
else if(is_string($paragraph[$i])) echo $paragraph[$i];
else if(is_object($paragraph[$i])) 
{
// checks to see if there is a list of reference citations and if the citations are at their beginning or end (for purposes of inserting parentheses) 
if (key($paragraph[$i])=="ref") {
if($i>=1&&is_object($paragraph[$i-1])) {
if (key($paragraph[$i-1])=="ref") {
}
}
// Opening parenthesis for citations
else {echo "(";}
}
$this->applyCharacterStyle($paragraph[$i],$itemNumber,$found); // any text with a character style, which is denoted in the JSON by an object is passed to the applyCharacterStyle() method
// Set the $note_flag so that 
if (key($paragraph[$i])=="note") $note_flag=1;
// Handle the closing off of reference citations and the setting of the flag
if (key($paragraph[$i])=="ref") {
// Set $citation_flag to indicate there are citations within the paragraph
$citation_flag=1;
// Semi-colon between references
if(is_object($paragraph[$i+1])&&key($paragraph[$i+1])=="ref") echo "; ";
// Closing parenthesis for citations
else echo ")";
}


}
$i++;
}
if ($i==count($paragraph)) { echo "</p>"; // when we reach the end of the array we close the paragraph
if ($note_flag==1) { echo "<p>";
$this->printNotes();
echo "</p>";}
if ($citation_flag==1) { echo "<p>";
$this->printReferences();
echo "</p>";}
}
}

function applyCharacterStyle($characters,$itemNumber,$found=NULL)
{
// function assumes that where character styles have been applied it has been done by creating an object

$style=key($characters);
$content=$characters->{$style};
if (in_array($style, $this->linking_tags)) $this->processLinkedStyles($characters);

// For simple styles open with tag
else if (in_array($style, $this->basic_tags)) {echo "<".$style.">";
// Process styles like notes and hyperlinks
// else processLinkedStyles($characters);
// For single strings return content to be styled
if	(is_string($content)&&isset($found)) echo preg_replace($found,"<a href='chapter-parser.php#".$itemNumber."'  style='background-color:yellow;'>$0</a>",$content);
else if (is_string($content)) echo $content;
else if (is_object($content)) $this->bounceCharacterStyle($content); // accounts for {"i":{"b":"italic bold"}}
else if (is_array($content)) $this->arrayWithinCharacterStyle($content); // handles content that is placed in an array, normally because there is more than one style, e.g. a hyperlink within an italic passage

echo "</".$style.">"; // we access the object key and use this as the character style, but if we wanted to use style definitions other than the HTML ones we could extend the code to handle this
}
}

function bounceCharacterStyle ($content)
{
// character object was nested one inside the other and so was sent here
$this->applyCharacterStyle($content);
}

function arrayWithinCharacterStyle($paragraph)
{
// Handles arrays, e.g where there is italic in superscript or subscript or bold within italic, or where hyperlinked text is inside an italic passage.
$i=0;
// this simple example handles paragraphs where there are basic character formats such as bold, italics and superscript or subscript

while ($i<count($paragraph))
{
if(is_string($paragraph[$i])) echo $paragraph[$i];
else if(is_object($paragraph[$i])) $this->applyCharacterStyle($paragraph[$i]); // any text with a character style, which is denoted in the JSON by an object is passed to the applyCharacterStyle() method
$i++;
}

}


function processLinkedStyles($characters){
// This is for filtering styles such as notes, citations and hyperlinks, and sends them off to specific functions - each should be processed in a way calpable of dealing with inner character styles

switch (key($characters))
{
case "a": $this->processHyperlink($characters);
break;

case "note": $this->processNotes($characters);
break;

case "ref": $this->processCitation($characters);
break;

default: echo "<b>LINKED STYLE</b>";
break;
}


}
function processHyperlink($hyperlink){
// Must be able to handle character styling and URL, it therefore may be an array within an array
// Plainly written URLs will not be sent to this function, they can instead be found in the final output using a server or client side scripting language
echo " there's a hyperlink you need to process buddy!";
}

function processNotes ($note) {
// keep running total of number of processed notes
// return a note referent number
// add note text to note array that can be used to construct a note list or deliver in a hover over the note referent - note text should be treated as a paragraph (array)
$style=key($note);
$content=$note->{$style};
array_push($this->notes,$content);
array_push($this->paragraph_notes,$content);
$note_number=count($this->notes);
echo "<sup id='ref".$note_number."'><a href='#note".$note_number."'>".$note_number."</a></sup> <span  style='display:none;background-color:yellow'>".$note_number.".  ".$content."</span>";

}

function returnNotes(){
return $this->notes;


}

function processCitation ($note) {

$style=key($note);
$content=$note->{$style};

// Only prevents duplicates in $this->paragraph_citations if they are identical page numbers as well as dates - needs refinement but might be better to impose this control when actual references are retrieved from the reference list
if(!in_array($content,$this->paragraph_citations)) array_push($this->paragraph_citations,$content);

if (is_array($content[0]))
{$i=0;
while ($i<count($content[0])) {
echo $content[0][$i];
if ($i==count($content[0])-2) echo " and ";
if ($i<count($content[0])-2) echo ", "; 
$i++;
}
}
else echo $content[0];
echo ", ".$content[2].": ".$content[3];

}
function printNotes(){

$note_number=count($this->notes)-count($this->paragraph_notes)+1;

echo "<ol start='".$note_number."' style='color:charcol; box-shadow:inset 0px 3px 8px lightgray; margin-top:0px; margin-bottom:0px; margin-left:-10px; width:100%; padding:20px; background-color:beige;display:none;' class='notehidden'>";
$i=0;
while($i<count($this->paragraph_notes)){

echo "<li style='margin-left:40px;' id='note".$note_number."'>".$this->paragraph_notes[$i]."</li>";
$i++;
}
echo "</ol>";
}

function printReferences(){ 
echo "<ul style='color:charcol; box-shadow:inset 0px 3px 8px lightgray; margin-top:0px; margin-left:-10px; width:100%; padding:20px; background-color:lightgreen; list-style-type: none;display:none;' class='refhidden'>";
$a=0;

while($a<count($this->paragraph_citations)){
$content=$this->paragraph_citations[$a];
// This is a placeholder, code needs revising so that it searches references and displays full reference (or writes 'no further details after')
echo "<li style='margin-left:20px;'>"."(";
if (is_array($content[0]))
{$i=0;
while ($i<count($content[0])) {
echo $content[0][$i];
if ($i==count($content[0])-2) echo " and ";
if ($i<count($content[0])-2) echo ", "; 
$i++;
}
}
else echo $content[0];
echo ", ".$content[2].": ".$content[3].")"."</li>";
$a++;
}
echo "</ul>";
}
}
?>

