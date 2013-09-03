<?php

// Basic text parser to convert XHTML to json-book format
// Currently handles headings (h1-h6), blockquotes, italics, everything else is treated as plain text paragraphs

// Loads xml file, runs regex to find reference citations (will later include notes) and returns $xml_revised
include('citation.php');


// $paragraphs array holds entire document
global $paragraphs;
$paragraphs=array();

// $paragraph_single retains a single paragraph at a time
global $paragraph_single;
$paragraph_single=array();


// load the XML
$xmlDoc = new DOMDocument();
// TODO: Make xml file selection dynamic
$xmlDoc->loadXML($xml_revised);

$x = $xmlDoc->documentElement;

// Cycle through the root's child nodes, i.e. the headings, paragraphs, blockquotes
foreach ($x->childNodes AS $item)
  {

 
  if ($item->hasChildNodes()) {
    global $styledText;
$styledTextFlag=0;
  foreach ($item->childNodes as $c) {

    $paragraph_single=array(); // Flush out array for current paragraph
    // currently headings are duplicated
if ($c->nodeName=="h1") {   $styledText= array("h1"=>$c->nodeValue);
array_push($paragraphs,$styledText);
}
else if ($c->nodeName=="h2") {   $styledText= array("h2"=>$c->nodeValue);
array_push($paragraphs,$styledText);
}
else if ($c->nodeName=="h3") {   $styledText= array("h3"=>$c->nodeValue);
array_push($paragraphs,$styledText);
}
else if ($c->nodeName=="h4") {   $styledText= array("h4"=>$c->nodeValue);
array_push($paragraphs,$styledText);
}
else if ($c->nodeName=="h5") {   $styledText= array("h5"=>$c->nodeValue);
array_push($paragraphs,$styledText);
}

else if ($c->nodeName=="blockquote") { 
// All blockquotes must be styled <blockquote><p></p></blockquote> [with one or more <p></p> tags]
  if ($c->hasChildNodes())   { 
  foreach ($c->childNodes as $d) {  
$styledText=array("blockquote"=>innerText($d));
}

}

array_push($paragraphs,$styledText);


}

else innerText($c);

}

}

}

// Handles all text that is within paragraphs (italics, citations, will also include notes and hyperlinks) 
function innerText($c) {

global $paragraph_single;
$paragraph_single_too=array();
global $paragraphs;
if ($c->hasChildNodes()) {
  foreach ($c->childNodes as $a) {

//   $styledText = array("i"=>$a->nodeValue);

if ($a->nodeName=="i") {
$styledText= array("i"=>$a->nodeValue);
array_push($paragraph_single,$styledText);
}
// citation
else if ($a->nodeName=="citation") {

$pattern='/\(name\)([A-Za-z, ]{1,})\(date\)([0-9]{1,})\(pages\)([0-9\-]{1,})/';
$replacement='$1;$2;$3';
$subject=$a->nodeValue;
$citation=preg_replace($pattern, $replacement,$subject);


$citation_array= explode(";", $citation);
// explode names in array
$names = explode(", ", $citation_array[0]);
// $initials, this is a placeholder (inclusion of initials in JSON makes it more reliable to match citations to references)
$initials = array();
// include date
$date = $citation_array[1];
// page numbers
$pages = $citation_array[2];
// reinstate en dashes in page ranges if required
$pattern='/([0-9]{1,})(\-)([0-9]{1,})/';
$replacement='$1&ndash;$3';
if (preg_match($pattern, $pages)) {
$pages=preg_replace($pattern, $replacement,$pages);
}



$styledText= array("ref"=>array($names,$initials,$date,$pages));
array_push($paragraph_single,$styledText);
}

else {

array_push($paragraph_single,$a->nodeValue);
}

}
 

if($c->nodeName!='blockquote'&&$c->parentNode->nodeName!='blockquote') {
array_push($paragraphs,$paragraph_single);}
else return $paragraph_single;
  }
}  
  
// TODO: make chapter number dynamic
$chapter = array('Chapter 2'=>$paragraphs);
$json=json_encode($chapter);

echo($json);
 // var_dump($paragraphs);

?>
