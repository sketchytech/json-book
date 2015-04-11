class PagingScrollView:UIScrollView, UIScrollViewDelegate  {
    var pageNumber = 0
    let tViews:[UITextView]
    
    init(frame:CGRect, textViews tViews:[UITextView]) {
        self.tViews = tViews
        super.init(frame: frame)
        self.delegate = self
        self.contentSize = CGSizeMake(self.frame.size.width * CGFloat(tViews.count),
            self.frame.size.height)
        self.pagingEnabled=true
        // generate and add initial text views to scroll view (no more than three)
        for i in 0..<3 {
            if tViews.count > i {
                self.addSubview(tViews[i])
            }
        }
    }
    
    func scrollViewDidScroll(scrollView: UIScrollView) {
        let maxPagesAhead = 1
        let maxPagesBehind = 2
        
        // moving forwards
        if Int(ceil(scrollView.contentOffset.x/scrollView.frame.width)) > pageNumber  {
            
            if tViews.count > pageNumber + maxPagesAhead {
                scrollView.addSubview(tViews[pageNumber+maxPagesAhead])
                
            }
            
            pageNumber = Int(ceil(scrollView.contentOffset.x/scrollView.frame.width))
            removeSubviewsBeyond(CGFloat(maxPagesAhead))
            
        }
        else if Int(ceil(scrollView.contentOffset.x/scrollView.frame.width)) < pageNumber {
            
            if pageNumber-maxPagesBehind >= 0 {
                scrollView.addSubview(tViews[pageNumber-maxPagesBehind])
            }
            
            pageNumber = Int(ceil(scrollView.contentOffset.x/scrollView.frame.width))
            removeSubviewsBeyond(CGFloat(maxPagesAhead))
            
        }
    }
    
    // makes sure there aren't too many subviews ahead or behind
    func removeSubviewsBeyond (pages:CGFloat) {
        
        // remove foremost pages from scroll view
        for v in self.subviews {
            if v.frame.minX < CGFloat(pageNumber) * self.frame.width - self.frame.width * pages  {
                if let tv = v as? UITextView {
                    tv.removeFromSuperview()
                }
                
            }
            
            // remove farmost pages from scroll view
            if v.frame.minX > CGFloat(pageNumber) * self.frame.width {
                if let tv = v as? UITextView {
                    tv.removeFromSuperview()
                }
                
            }
        }
    }

    
    required init(coder aDecoder: NSCoder) {
        self.tViews = [UITextView()]
        super.init(coder:aDecoder)
    }
    
    
}
