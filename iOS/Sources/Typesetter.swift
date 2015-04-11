struct Typesetter {
    let textStorage:NSTextStorage
    // an instance is created with an NSAttributedString
    init (attrStr:NSAttributedString) {
        textStorage = NSTextStorage(attributedString: attrStr)
    }
    // if views are for a (paging) scroll view then text views will given x position that places them one after the other
    func textViews(frame:CGRect, prepareForScrollView:Bool = true)->[UITextView] {
        // array for text views that will be returned
        var textViews = [UITextView]()
        // create the layout manager
        let layoutManager = NSLayoutManager()
        // have an inset to be on the safe side
        let inset:CGFloat = 10
        
        // add the layout manager to the textStorage
        textStorage.addLayoutManager(layoutManager)

        // calculate the number of required views
        let numberOfViews = Int(ceil(textStorage.boundingRectWithSize(CGSize(width: frame.width, height: frame.height-inset), options: .UsesDeviceMetrics | .UsesFontLeading | .UsesLineFragmentOrigin, context: nil).height / frame.height))
        // see http://stackoverflow.com/questions/14409897/how-to-calculate-the-height-of-an-nsattributedstring-with-given-width-in-ios-6
        // there will sometimes be a blank final page but better than missing text, might be a better delegate method that could be used but didn't find one to judge when all text in textStorage has been placed into views
        
        // time to create the required views
        for i in 0...numberOfViews {
            // create text container, make slightly smaller so it's inset from the provided frame size, same as the UITextView
            let textContainer = NSTextContainer(size: CGSize(width: frame.width, height: frame.height-inset))
            // add text container to layout manager
            layoutManager.addTextContainer(textContainer)
            // set x-position
            let xposition:CGFloat = prepareForScrollView == true ? frame.width*CGFloat(i) : 0
            // create text view
            let textView = UITextView(frame: CGRect(x: xposition, y: 0, width: frame.width, height: frame.height-inset), textContainer: layoutManager.textContainers[i] as? NSTextContainer)
            // prevent crashing when attempt to scroll UITextView inside UIScroll view by disabling interaction
            textView.userInteractionEnabled = false
            // add view to array
            textViews.append(textView)
        }
        // return the array
        return textViews
    }
}
