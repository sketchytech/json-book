<?php
$paragraph_json = file_get_contents("paragraph.json");
$paragraph = json_decode($paragraph_json);

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
echo "<".key($characters).">".$characters->{key($characters)}."</".key($characters).">"; // we access the object key and use this as the character style, but if we wanted to use style definitions other than the HTML ones we could extend the code to handle this
}

returnParagraph($paragraph);
?>

