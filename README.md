json-book
========

**If you want to learn more about the nuts and bolts of json-book visit the [Wiki](https://github.com/sketchytech/json-book/wiki/_pages)**

json-book allows an XML or JSON file to be transformed into a dynamic book that can be displayed and output in any number of ways. Delivery can be anything from HTML on the web to JSON via an API. Book data can be downloaded and parsed inside mobile apps or can be used to build EPUBs, KF8s and PDFs for delivery. The possibilities for outputting current and future formats is endlessly extensible.

**Watch an early video of what's possible:** http://youtu.be/orS8hD2JJLQ

**It's called json-book because its power comes from json manipulation but it is being developed to work with XML/XHTML input.**

This is a project to develop an organized data structure for JSON books, journals and magazines. The approach being taken is to develop the structure for the JSON alongside a set of parsers in order to establish an organic logic.

Programmers will find it easy to adapt the parsers for their own needs and to innovate, making stylish designs using JavaScript, PHP, iOS, Android, Ruby, Python and any other programming or scripting language you can name.

The tech is being developed by a real-life editor and publisher who understands the needs, the constraints, and the current limits of interactivity using other technologies (while maintaining time efficiency). And sees that they are insufficient for future needs, and wants to get back to simply producing books in a format that will be permanent for the foreseable future.

WANT TO SEE HOW THIS WORKS: Most up to date version contained in: **php/indexing_in_progress/** folder. The **chapter-parsing.php** file displays chapter, the **index-parsing.php** file is for display of paragraphs that contain text from indexed term (Download entire folder to see functionality). For less rough and ready code - with less placeholders and hard coded tests - see the **chapter-parsing.php** file in **php** folder 

Updates and revisions are happening frequently, and a more structured and organised presentation will be available once a complete implementation is ready containing an entire book. The format and parsers will then be extended from books to journals (enabling inclusion of abstract, keywords, etc.). 

Even if you don't want to implement the structures and parsers as they are, the methodology and simplicity behind them will hopefully inspire.


Aims to incorporate:

- automated note numbering and dynamic ordering for flexible placement of notes within a document's structure
- reference linking (see reference-structure-outline.txt)
- built-in support for multi-lingual books
- support for editions (i.e. past versions of the book) and transfer of notes between old and new
- indexing by hashtags - see http://sketchytech.blogspot.co.uk/2013/02/hashtags-not-hyperlinks-index-of-future.html
- note taking and note export
- save to EPUB
- save to PDF
  
Next things to add and amend:

- continue extending and implementing class/object structure to PHP
- add parsing function for hyperlinks
- extend parsing function for notes, to include conversion of notes in XML without requiring data to be altered from typical paired (HTML) structures like \<sup\>1\</sup\> (in text) and \<ol\>\<li\>\</li\>\</ol\> at end of document
- parsing of (XML) reference list to be developed for conversion into json-book form from typical structures, e.g. author, initials (date) Book Title. Place: Publisher.

After the "next things":

- handle blockquotes better, start provision for images, tables (CSV structure?)

Note: The initial parser is written in PHP for convenience but will be adapted to other languages and scripts (including JavaScript and Objective-C for iOS)

- iOS parser now in progress - see iOS folder
