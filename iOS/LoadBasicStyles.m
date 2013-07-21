#import "LoadBasicStyles.h"
#import <CoreText/CoreText.h>

@implementation LoadBasicStyles

NSDictionary *i, *b, *sup, *sub;

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

}

// For information on how to apply attributes to a string see https://coderwall.com/p/y5w10w
