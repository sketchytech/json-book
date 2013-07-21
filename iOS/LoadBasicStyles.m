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
