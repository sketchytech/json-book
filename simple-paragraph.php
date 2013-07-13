<?php
$paragraph_json = file_get_contents("paragraph.json");
$paragraph = json_decode($paragraph_json);

$basic_tags = array("a","b","i","sup","sub","q");

function returnParagraph($paragraph)
{
echo "<p>"; // open paragraph with tag
$i=0;
// this simple example handles paragraphs where there are basic character formats such as bold, italics and superscript or subscript
while ($i<count($paragraph))
{
if(is_string($paragraph[$i])) echo $paragraph[$i];
else if(is_object($paragraph[$i])) applyCharacterStyle($paragraph[$i]); // any text with a character style, which is denoted in the JSON by an object is passed to the applyCharacterStyle() method
$i++;
}
if ($i==count($paragraph)) echo "</p>"; // when we reach the end of the array we close the paragraph
}

function applyCharacterStyle($characters)
{
// function assumes that where character styles have been applied it has been done by creating an object
$basic_tags = array("b","i","sup","sub","q");
$style=key($characters);
$content=$characters->{$style};
// For simple styles open with tag
if (in_array($style, $basic_tags)) echo "<".$style.">";
// Process styles like notes and hyperlinks
else processLinkedStyles($characters);
// For single strings return content to be styled
if (is_string($content)) echo $content;
else if (is_object($content)) bounceCharacterStyle($content); // accounts for {"i":{"b":"italic bold"}}
else if (is_array($content)) arrayWithinCharacterStyle($content); // handles content that is placed in an array, normally because there is more than one style, e.g. a hyperlink within an italic passage

echo "</".$style.">"; // we access the object key and use this as the character style, but if we wanted to use style definitions other than the HTML ones we could extend the code to handle this
}

function bounceCharacterStyle ($content)
{
// character object was nested one inside the other and so was sent here
applyCharacterStyle($content);
}

function arrayWithinCharacterStyle($paragraph)
{
// Handles arrays, e.g this might be because there is a mix of italic and normal text in superscript or subscript or bold within italic, or where hyperlinked text is inside an italic passage.
$i=0;
// this simple example handles paragraphs where there are basic character formats such as bold, italics and superscript or subscript
while ($i<count($paragraph))
{
if(is_string($paragraph[$i])) echo $paragraph[$i];
else if(is_object($paragraph[$i])) applyCharacterStyle($paragraph[$i]); // any text with a character style, which is denoted in the JSON by an object is passed to the applyCharacterStyle() method
$i++;
}

}


function processLinkedStyles($characters){
// This is for filtering styles such as notes, citations and hyperlinks, and sends them off to specific functions - each should be processed in a way calpable of dealing with inner character styles
}
function handleHyperlinks($hyperlink){
// Must be able to handle character styling and URL, it therefore may be an array within an array
// Plainly written URLs will not be sent to this function, they can instead be found in the final output using a server or client side scripting language

}

function processNote ($note) {
// keep running total of number of processed notes
// return a note referent number
// add note text to note array that can be used to construct a note list or deliver in a hover over the note referent - note text should be treated as a paragraph (array)

}


returnParagraph($paragraph);
?>

