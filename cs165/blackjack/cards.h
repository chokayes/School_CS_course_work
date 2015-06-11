
//  cards.h
//  blackjack
//
//  Created by Ali Payne on 6/8/14.
//  Copyright (c) 2014 engr.oregonstate. All rights reserved.
//

#ifndef __blackjack__cards__
#define __blackjack__cards__

#include <iostream>
#include <string>

namespace theinfo
{

// users info
struct player
{
    std::string name;
    std:: string age;
    std::string height;
    std::string email;
    std::string phonenumber;
};
}

// namespace example



// THIS IS THE ACTUAL CARD
class card
{
public:
    // CONSTRUCTOR
    card();
    //DESTRUCTOR
    ~card();
    // WHAT I WANT CARD TO BE
    card( std::string, std::string);
    
    // RETURN THE NUMBER OF
    std::string get_number();
    
    // RETURNS THE SUIT
    std::string get_suit();
    
    // PRINTS OUR THE CARD
    void thecard();
    
    int amout();
    
    
   // since protected i can access them in the deck class
protected:
    
    // hold the number but cant be used
    std::string number;
    
    // holds the suit but cant be used
    std::string suit;
    
};

// there is 52 cards in a deck
const int cardsinadeck = 52;

class deck
{
    
public:
    // defult constructor
    deck();
    
    // shuffles the deck
    void shuffle();
    
    // deals out one card
    card dealcard();
    
protected:
    // poinyer to an opject cards
    card *theplayingdeck;
    
    //
    int topcard;
};




#endif /* defined(__blackjack__cards__) */
