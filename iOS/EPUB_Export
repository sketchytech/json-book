struct EPUB {
    let subdirectories:[String]
    let files:[JSONDictionary]
    let file_locations:JSONDictionary
    let file_templates:JSONDictionary
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
        fileTem = dict["file-templates"]?.jsonDict{
            
        subdirectories = subArr
        files = fileList
        file_locations = fileLocs
        file_templates = fileTem
   
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
