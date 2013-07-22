//
//  SimpleParagraphViewController.m
//  attributedString
//
//  Created by Anthony Levings on 21/07/2013.
//  Copyright (c) 2013 Gylphi. All rights reserved.
//

#import "SimpleParagraphController.h"
#import <CoreText/CoreText.h>

@interface SimpleParagraphViewController ()
@property (strong, nonatomic) IBOutlet UITextView *textView;

@end

@implementation SimpleParagraphViewController
@synthesize textView;
NSDictionary *i, *b, *sup, *sub, *basic_tags;
NSArray *paragraph;
NSMutableAttributedString *text;



- (void)viewDidLoad
{
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    [self loadJSON];
    text = [[NSMutableAttributedString alloc] init];
    [self loadBasicStyles];
    [self returnParagraph:paragraph];
    
    textView.attributedText = text;
    textView.editable=NO;
    
    
}

- (void)loadJSON {
    // Load JSON into Objective-C objects
    // At the moment this isn't JSON we're loading, we're just experimenting with NSArray, NSDictionary and NSString but the JSON will be translated into these object classes and so very similar.
    paragraph = @[@"hello ",@{@"i":@"world"}, @". ", @{@"b":@"This is groovy"}, @{@"sup":@"This is groovy"}, @{@"sub":@"This is groovy"}];
}
- (void)loadBasicStyles {
    i = @{
                        NSFontAttributeName: [UIFont fontWithName:@"HoeflerText-Italic" size:12]
          };
    
    b = @{
                        NSFontAttributeName: [UIFont fontWithName:@"HoeflerText-Black" size:12]
        };
    sup = @{
            (id < NSCopying >)kCTSuperscriptAttributeName:[NSNumber numberWithInt:1]
            };
    sub = @{
            (id < NSCopying >)kCTSuperscriptAttributeName:[NSNumber numberWithInt:-1]
            };

    basic_tags =@{@"i":i,@"b":b,@"sup":sup,@"sub":sub};
    
  
}

-(void)returnParagraph:(NSArray*)paragraph
{

        // build paragraph from an array of strings and objects
    int i=0;
    while (i<paragraph.count) {
        if ([[paragraph objectAtIndex:i] respondsToSelector:@selector(characterAtIndex:)])
        {
            
            NSAttributedString *newString = [[NSAttributedString alloc] initWithString:(NSString *)[paragraph objectAtIndex:i]];
            
       [text appendAttributedString: newString];
           }
        else if ([[paragraph objectAtIndex:i] respondsToSelector:@selector(allKeys)]) {
            
            [self applyCharacterStyle:[paragraph objectAtIndex:i]];
            
        }
        i++;
    }
    
    
}

-(void)applyCharacterStyle:(NSDictionary*)characters
{
    // function assumes that where character styles have been applied it has been done by creating an object
    for (NSString *formattedText in (NSDictionary *)characters) {
        
        if ([basic_tags valueForKey:formattedText])
        {
       NSAttributedString *newString = [[NSAttributedString alloc] initWithString:[characters valueForKey:formattedText] attributes:[basic_tags valueForKey:formattedText]];
        [text appendAttributedString: newString];
        }
        
    }
}

-(void)bounceCharacterStyle:(NSDictionary*)$content
{
    // character object was nested one inside the other and so was sent here
}

-(void)arrayWithinCharacterStyle:(NSArray*)paragraph
{
    // Handles arrays, e.g this might be because there is a mix of italic and normal text in superscript or subscript or bold within italic, or where hyperlinked text is inside an italic passage.
}

- (void)didReceiveMemoryWarning
{
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
