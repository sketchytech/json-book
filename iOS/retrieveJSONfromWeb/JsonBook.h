//
//  Gylphi.h
//  nsurl
//
//  Created by Anthony Levings on 21/09/2013.
//  Copyright (c) 2013 Gylphi. All rights reserved.
//

#import <Foundation/Foundation.h>


@interface JsonBook : NSObject<NSURLConnectionDelegate>


{

    NSMutableData *_responseData;
}

// Create an instance of this class in your ViewController file at the earliest possible moment and send the URL location of the JSON as a string

-(void)collectJsonFromURL:(NSString *)url;

// When you are ready to pull the NSDictionary into your view controller access it from this property
@property(nonatomic, strong) NSDictionary* jsonBook;

@end
