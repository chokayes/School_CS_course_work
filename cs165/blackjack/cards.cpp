 //
//  cards.cpp
//  blackjack
//
//  Created by Ali Payne on 6/8/14.
//  Copyright (c) 2014 engr.oregonstate. All rights reserved.
//

#include "cards.h"
#include <string>



card::card()
{
    //cout << " you have realed a card with no value " << std::endl;
    this->number = " ";
    this->suit= " ";
}
card::~card(){
    //left blank
}
card::card( std::string thesuit, std::string thenumber)
{
   // cout << " you have created a real card" << std::endl;
    this-> number= thenumber;
    this-> suit= thesuit;
}
std::string card::get_number()
{
    return number;
}

std::string card::get_suit()
{
    return suit;
}

void card::thecard()
{
    
    std::cout <<    get_number() + " of " + get_suit() << std::endl;
    
}


deck::deck( )
{
   // cout << " the deck has been created"<< std::endl;
    
    // all of the suits
    std:: string suit []= {"clubs", "spades", "dimonds", "hearts"};
    
    // all of the numbers
    std:: string number[]= {"two", "three", "four", "five", "six", "seven", "eight", "nine", "ten" ,"jack" , "queen", "king","ace"};
    
    // cpointsto the objecto of cards and we have created a deck of cards 52
    theplayingdeck = new card [cardsinadeck];
    
    // top of the deck of cards
    topcard=0;
    
    
    int add = 0;
    for (int x = 0; x < cardsinadeck; x++)
    {
        if (x < 13)
        {
            // creates clubs
            theplayingdeck[x] =  card ( suit[x/13] , number[add++]);
        }
        else if (x<26)
        {
            // creates spades
            theplayingdeck[x] =  card ( suit[x/13] , number[add++]);
        }
        else if (x< 39)
        {
            // creates dimonds
            theplayingdeck[x] =  card ( suit[x/13] , number[add++]);
        }
        else if ( x < 52)
        {
            // creates hearts
            theplayingdeck[x] =  card ( suit[x/13] , number[add++]);
        }
        if (add ==13)
        {
            add = 0;
        }
    }
}

// makes the order of the deck random
void deck::shuffle()
{
    
  //  cout<< " I see you decided to shuffle the deck" <<std::endl;
        topcard=0;
        for (int x = 0; x < cardsinadeck; x++)
        {
            
            // random number generator
            int hold = (rand() +time (0)) % cardsinadeck;
            card temp = theplayingdeck[x];
            theplayingdeck[x] = theplayingdeck[hold];
            theplayingdeck[hold]= temp;
        }
}

card deck::dealcard()
    {
      //  cout << " we are dealing cards" << std::endl;
        if(topcard < cardsinadeck)
        {
            // this is what allows me to go throw deck so op card  pointing to and array location and when i add to it it points to the next array location if deck of cards
            return theplayingdeck[topcard++];
        }
        else
        {
            // lets user know we have used all cards in this deck
            std::cout <<" we must resheffule this deck" << std::endl;
            shuffle();
            // if we shuffle the deck i need the array to go back to [0] the top card
             return theplayingdeck[topcard];
        }
    }
    


