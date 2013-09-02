<?php

// Basic text parser to convert XHTML to json-book format
// Currently handles headings (h1-h6), blockquotes, italics, everything else is treated as plain text paragraphs

// Regex will be used to locate citations and references, so that they can be dynamic utilised

// $paragraphs array holds entire document
global $paragraphs;
$paragraphs=array();

// $paragraph_single retains a single paragraph at a time
global $paragraph_single;
$paragraph_single=array();


// load the XML
$xmlDoc = new DOMDocument();
$xmlDoc->load("alchemist.xml");

$x = $xmlDoc->documentElement;

// Cycle through the root's child nodes
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

else if ($a->nodeName=="h1") {   
}
//echo '"{"i":"'.$a->nodeValue.'"},';
else {

array_push($paragraph_single,$a->nodeValue);
}

}
 

if($c->nodeName!='blockquote'&&$c->parentNode->nodeName!='blockquote') {
array_push($paragraphs,$paragraph_single);}
else return $paragraph_single;
  }
}  
  
  
// var_dump($italics);
$json=json_encode($paragraphs);
echo $styledTextFlag;
echo($json);
 // var_dump($paragraphs);

?>
