//
//  XMLParser.swift
//  SaveFile
//
//  Created by Anthony Levings on 31/03/2015.
//

import Foundation

class XMLParser:NSObject, NSXMLParserDelegate {

    // where the dictionaries for each tag are created
    var elementArray = [JSONDictionary]()
    var contentArray = [JSONArray]()
    // final document array where last dictionary
    var document = JSONDictionary()
   
    
    
    init(xml:NSData) {
        var xml2json = NSXMLParser(data: xml)
        super.init()
        xml2json.delegate = self
        xml2json.parse()

    }
    func parserDidStartDocument(parser: NSXMLParser) {
    
    }
    
    func parser(parser: NSXMLParser, didStartElement elementName: String, namespaceURI: String?, qualifiedName qName: String?, attributes attributeDict: [NSObject : AnyObject]) {

       
        // current dictionary is the newly opened tag
        elementArray.append(JSONDictionary(dict: [elementName:"", "attributes":attributeDict], restrictTypeChanges: false))

        
        // every new tag has an array added to the holdingArr
        contentArray.append(JSONArray(restrictTypeChanges: false))

    }
    
    func parser(parser: NSXMLParser, foundCharacters string: String?) {
        if let str = string {

                // current array is always the last item in holding, add string to array
                contentArray[contentArray.count-1].append(str)
        

            
        }
    }
    
    func parser(parser: NSXMLParser, didEndElement elementName: String, namespaceURI: String?, qualifiedName qName: String?) {

        println("holding array count at didEnd: \(contentArray.last?.count)")
        // current array, which might be one string or a nested set of elements is added to the current dictionary
        if contentArray.count > 0 {
            for (k,v) in elementArray.last! {
                if k != "attributes" {
                    elementArray[elementArray.count-1][k] = contentArray.last
                    if let ar = elementArray[elementArray.count-1][k]?.jsonArr {
                      
                    }
                }


            }
            
        }
        
        // add the current dictionary to the array before if there is one
        if contentArray.count > 1 {
         
            // add the dictionary into the previous JSONArray of the holdingArray
            contentArray[contentArray.count-2].append(elementArray[elementArray.count-1])
            
         
            
         // remove the dictionary
            if elementArray.count > 0 {
                // remove the array of the current dictionary that has already been assigned
                elementArray.removeLast()
            }
            if contentArray.count > 0 {
                contentArray.removeLast()
            }

        }
        
      

 
    }
    
    func parserDidEndDocument(parser: NSXMLParser) {
        document = elementArray.last!
       //         println(dictionaryArray.count)
      println(document.dictionary)
        let j = document.jsonData(options: NSJSONWritingOptions.PrettyPrinted, error: nil)
    FileSave.saveData(j!, directory: NSSearchPathDirectory.DocumentDirectory, path: "parsed.json", subdirectory: nil)
        
    
    }
    
}
