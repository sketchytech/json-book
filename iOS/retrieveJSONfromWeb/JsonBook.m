//
//  Gylphi.m
//  nsurl
//
//  Created by Anthony Levings on 21/09/2013.
//  Copyright (c) 2013 Gylphi. All rights reserved.
//

#import "JsonBook.h"

@implementation JsonBook : NSObject
@synthesize jsonBook;



#pragma mark NSURLConnection Delegate Methods
- (void)connection:(NSURLConnection *)connection didReceiveResponse:(NSURLResponse *)
response {
    // A response has been received, this is where we initialize the instance var you created
    // so that we can append data to it in the didReceiveData method
    // Furthermore, this method is called each time there is a redirect so reinitializing it
    // also serves to clear it
    _responseData = [[NSMutableData alloc] init];
}
- (void)connection:(NSURLConnection *)connection didReceiveData:(NSData *)data {
    // Append the new data to the instance variable you declared
    [_responseData appendData:data];
}
- (NSCachedURLResponse *)connection:(NSURLConnection *)connection
                  willCacheResponse:(NSCachedURLResponse*)cachedResponse {
    // Return nil to indicate not necessary to store a cached response for this connection
    return nil;
}
- (void)connectionDidFinishLoading:(NSURLConnection *)connection {
    // The request is complete and data has been received
        // You can parse the stuff in your instance variable now
    NSError * error = nil;
jsonBook = [NSJSONSerialization
                     JSONObjectWithData:_responseData
                     // Creates an Objective-C NSData object from JSON Data
                     options:NSJSONReadingAllowFragments
                     error:&error];
    
    

}
- (void)connection:(NSURLConnection *)connection didFailWithError:(NSError *)error {
    // The request has failed for some reason!
    // Check the error var
}

-(void)collectJsonFromURL:(NSString *)url {
    
    // Create the request.
    NSURLRequest *request = [NSURLRequest requestWithURL:[NSURL URLWithString:url] cachePolicy:NSURLCacheStorageNotAllowed
                                         timeoutInterval:20.0];
// Create url connection and fire request
[NSURLConnection connectionWithRequest:request delegate:self];
    
}
@end
