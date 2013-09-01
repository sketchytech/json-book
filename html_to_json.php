<?php

$chapter_title="Chapter 1";

// Todo: replace special characters [{" with entity codes before manipulation

// string for manipulation
$c = '<p>This is the first paragraph.</p><p>nine Me (1996)<i>with itals</i><i>with itals</i></p><h6 class="ten">Title <em>with itals</em><i>italics</i></h6>"';

// Find italics and bold first
$pattern = '/(<)([ib]|em)([^>]*)(>)([^<]*)(<\/)([ib]|em)(>)/i';
$replacement = '{"$7":"$5"}';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$c);

// Find italics and bold without a comma before them and place it there
$pattern = '/([^,:\[])({\"[ib]\":\")([^\"]*)(\"})/i';
$replacement = '$1,$2$3$4';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Where value before a comma is not ]}" then add quote marks - doesn't work it is an object in array!
$pattern = '/([^}\]\"])(,)/i';
$replacement = '$1"$2';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Find citations
$pattern = '/(<)(citation|footnote|h[1-6])([^>]*)(>)([^<]*)(<\/)(citation|footnote|h[1-6])(>)/i';
$replacement = '{"$7":"$5"}';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Identify arrays and place opening square bracket where it should be at start - doesn't work if first item is an object, or an array (like a chapter being an array of arrays)!
$pattern = '/({)([^:]*)(:)("|{|\[)([^"]*)(","|",{|",\[|},{|\],\[)/i';
$replacement = '$1$2$3[$4$5$6';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Square bracket at beginning of heading array (doesn't work is string!)
$pattern = '/(h[1-6]\":)([A-Za-z {}\"\"\'\'\:]*)/i';
$replacement = '$1[$2';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);


// Perform clean up
$pattern = '/([\"]*)( )([{])/i';
$replacement = '$1",$3';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);



// Perform clean up
$pattern = '/(}\"})/i';
$replacement = '}]}';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Perform clean up
$pattern = '/(<p>)([^<]*)(<\/p>)/i';
$replacement = '["$2"]';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Perform clean up
$pattern = '/([A-Za-z ])({)/i';
$replacement = '$1",$2';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Perform clean up
$pattern = '/(}\"])/i';
$replacement = '}]';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Perform clean up
$pattern = '/(}]{)/i';
$replacement = '}],{';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Perform clean up
$pattern = array('/(\])(\[)/i','/(})({)/i');
$replacement = '$1,$2';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Perform clean up
$pattern = '/(\])(\[)/i';
$replacement = '$1,$2';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);

// Perform clean up
$pattern = '/}\"/i';
$replacement = '}';
// Carry out replacement
$json=preg_replace($pattern,$replacement,$json);


$output_json='{"'.$chapter_title.'":['.$json.']}';
//$output_json=substr($output_json, 0,);
//echo $output_json;
//var_dump(json_decode($output_json));

//$obj = json_decode($json);
//print $obj->b;

$chapter = json_decode($output_json);

echo $chapter->{'Chapter 1'}[0][0];

?>
