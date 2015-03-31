//
//  EPUB.swift
//  SaveFile
//
//  Created by Anthony Levings on 30/03/2015.
//  Copyright (c) 2015 Gylphi. All rights reserved.
//

import Foundation


struct EPUBChapter {
//    let file_template:JSONDictionary
    let header:JSONArray
    let body:JSONDictionary
    let footer:String
    init? (template:JSONDictionary) {
        
        if let chapter_template = template["chapter"]?.jsonDict,
            head = chapter_template["header"]?.jsonArr,
            bod = chapter_template["body"]?.jsonDict,
            foot = chapter_template["footer"]?.str
        {
            header = head
            body = bod
            footer = foot
        }
        else {
            return nil
        }
    }
    
    func constructHeader(json:JSONDictionary) -> String {
        // Working correctly
        var headHTML = ""
        for h in header {
            if let str = h.str {
                headHTML += str
            }
            if let dict = h.jsonDict,
            key = dict["$0"]?.str,
            text = json[key]?.str {
                headHTML += text
            }
        }
        return headHTML
    }
    
    func constructBody(json:JSONArray) -> String {
        var bodyHTML = ""
        for b in json {
            // if it's a string we simply add the string to the bodyHTML string
            if let str = b.str {
                bodyHTML += str
                // This bit works
            }
            // TODO: Not currently working properly, doesn't capture nested values
                
            // if it's a dictionary we know it has a tag key
            else if let dict = b.jsonDict
            {
                var elementHTML = ""
                // work through the keys and values of the dictionary
                for (k,v) in dict {
                    // if it matches one in the list of tags in the body template work through that element's array to build the relevant HTML
                    if let element = body[k]?.jsonArr {
                        for e in element {
                            // if the template contains text then add it to the elementHTML substring
                            if let text = e.str {
                                elementHTML += text
                            }
                            // if the value is a dictionary then there is text to insert
                            if let value = e.jsonDict,
                            key = value["$0"]?.str {
                                // the value of the element key is the main text
                                if let text = dict[key]?.str {
                                elementHTML += text
                                }
                                // if the value of the element key is an array then it is a collection of elements
                                else if let json = dict[key]?.jsonArr {
                                 // cycle back through
                                    elementHTML += constructBody(json)
                                }
                            }
                        }
                    }
                    
                }
                bodyHTML += elementHTML

            }
        }
    
      return bodyHTML
    }
    
}
struct EPUB {
    let subdirectories:[String]
    let files:[JSONDictionary]
    let file_locations:JSONDictionary
    let file_templates:JSONDictionary
    let chapter:EPUBChapter
    var name:String
    
    var staticFiles:[(text:String,path:String,subdirectory:String)]? {
        var a:[(text:String,path:String,subdirectory:String)]?
        for file in files {
            for (k,v) in file {
                if let str = v?.str {
                    if str != "$0" {
                        if a != nil {
                            a!.append(text:str,path:k,subdirectory:folderPath(file_locations[k]?.str))
                        }
                        else {
                            a = [(text:str,path:k,subdirectory:folderPath(file_locations[k]?.str))]
                        }
                    }
                }
            }
            
        }
        return a
    }
    
    // provides folder path inside a parent with the name of the EPUB, might be better to use ID so that name is changeable
    func folderPath(subdirectory:String?) -> String {
        
        if let sub = subdirectory {
            return name + "/" + sub
        }
        return name + "/"
    }
    
    init?(name n:String, dict:JSONDictionary) {
        name = n
        if let sub = dict["subdirectories"]?.jsonArr,
            subArr = sub.stringArray(),
            fileArr = dict["files"]?.jsonArr,
            fileList = fileArr.dictionaryArray(),
            fileLocs = dict["file-locations"]?.jsonDict,
            fileTem = dict["file-templates"]?.jsonDict,
            chapTem = fileTem["[chapters]"]?.str {
                
                subdirectories = subArr
                files = fileList
                file_locations = fileLocs
                file_templates = fileTem
                
                // remove file extension
                var pathComponents = chapTem.pathComponents
                pathComponents.removeLast()
                var path = ""
                for p in pathComponents {
                    path += p
                }
                if let url = NSBundle.mainBundle().pathForResource(path, ofType: chapTem.pathExtension),
                d = NSData(contentsOfFile: url),
                jDict = JSONParser.parseDictionary(d),
                chap = EPUBChapter(template:jDict)
                     {                        println("success")
                    chapter = chap

                }
                else { return nil}
                
        }
        else {
            return nil
        }
    }
    
    func createSubdirectories() {
        for folder in subdirectories {
            FileSave.createSubdirectory(NSSearchPathDirectory.DocumentDirectory,subdirectory:folderPath(folder))
        }
    }
    
    func saveStaticFiles() {
        if let sF = staticFiles {
            for file in sF {
                FileSave.saveString(file.text, directory: NSSearchPathDirectory.DocumentDirectory, path: file.path, subdirectory: file.subdirectory, encoding: NSUTF8StringEncoding)
            }
        }
    }
    
}
