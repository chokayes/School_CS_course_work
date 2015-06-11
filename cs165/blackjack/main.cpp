
//
//  main.cpp
//  blackjack
//
//  Created by Ali Payne on 6/7/14.
//  Copyright (c) 2014 engr.oregonstate. All rights reserved.
//

#include <iostream>
#include <string>
# include "cards.h"

#include <ctime> 
#include <cstdlib>
#include <vector>

using std::cout;
using std::cin;
using std::endl;
using std::string;

// introduction of the game and history
void intro ();

//displays "BLACK JACK
void intropic();

// takes values of cards recieved by user
int amout ( string, string);

//converts string to int
int checker( std::string );

// hit or stand
int hitorstand ( int,card &, deck &);

void dealersturn ( int, int, card &, deck&, string &);

// decides which cards dealer picks
int dchecker( std::string card , int total);

//it is now the dealers turn
void dealerVSplayer( int ptotal, int dtotal, card& , deck& , string &);

// tthe game  itself
void gamplay ( card& , deck&, int& ptotal, int& dtotal);


// function overloading - they have the same name yet two different functions based on input
void overload ( int, int){
    
   // cout << " you inputted two values in here" << endl;
}

void overload( int ){
    
   // cout << " you inputed one value in here" <<endl;
}

// collects a strct of the player
void fbi( theinfo::player&);


std::vector<string>v;
// prints out vector of all of useres hits
void  seevector();


int main(int argc, const char * argv[])
{
    // command line arguement
    if (argc != 1){
        cout << "Error" <<endl;
        return -1;
    }
    
    deck blackjack; // creates  the deck of cards
    card gamecard; // creates a way so that you can access the cards
    blackjack.shuffle(); // shuffles the deck so that the cards are not n order
    
    char playagain;
    
    // introduces the game
    intro();
        do
        {
            // the players card total and the dealars card total
            int ptotal, dtotal;
        
            // the actua; game
            gamplay ( gamecard , blackjack, ptotal, dtotal);
            
            cout <<"would you like to play again? (y/n)" <<endl <<">>";
            std::cin >> playagain;
    
        } while (playagain == 'y'|| playagain == 'Y');
    
    
    // function overload
    int t,y;
    overload(t);
    overload(t,y);
    
    //collects info on players
    theinfo::player personalinformation;// struct of person playing
    fbi(personalinformation);
    theinfo::player *pointer;
    
    // pointer to struct
    pointer= &personalinformation;
    
    // vector is in thhis class
    seevector();
    
    //outputs pointer to struct
    cout << "have a Great Day " << pointer->name;
    
    return 0;
}

void intro ( )
{
    double number = 21.8;
    
     int gametitle= static_cast<int>(number);
    
    
    cout << "Welcome to Ali's "<< gametitle << " Black Jack!" <<endl <<endl;
    
    // picture using an array of "BLACKJACK"
    intropic();
    
    cout << endl << "Game history: " <<endl;
    cout << "Blackjack is a popular American casino game, now found throughout the world. It is a banking game in which the aim of the player is to achieve a hand whose points total nearer to 21 than the banker's hand, but without exceeding 21." <<endl <<endl;
    cout << " Rules: " << endl;
    cout << "At the start of a blackjack game, the players and the dealer receive two cards each. The players' cards are normally dealt face up, while the dealer has one face down (called the hole card) and one face up" << endl <<  " * this game allowes card counting " << endl <<endl;
    cout << " The best possible blackjack hand is an opening deal of an ace with any ten-point card. This is called a  *BLACKJACK* , or a natural 21, and the player holding this automatically wins unless the dealer also has a blackjack. If a player and the dealer each have a blackjack, the result is a push for that player. If the dealer has a blackjack, the dealer not holding a blackjack lose. "<< endl <<endl;
    cout << "Instructions"<< endl;
    cout << " Player will recieve 2 cards and then will be informed one of the dealer cards. Player one will  be able to hit (get another card) or stand try to get 21 or as close to 21 as possible without going over";
    cout << endl;
}

void intropic( )
{
// SIZE OF ARRAY
 char card [8][40];
    
    // BUILDING ARRAY BLANK
    for (int x = 0; x < 8; x++){
        for (int y = 0; y < 40; y ++){
            card[x][y] = ' ';}
    }

    
    for (int x = 0; x < 8; x++){
        for (int y = 0; y < 40; y ++){
        if ( x == 0){
            // THE TOP OF THE ARRAY
                card[x][y] = '-';
            }
        else if ( x == 7){
            //THE BOTTOM OF THE ARRAY
                card[x][y] = '^';
            }
        else if ( y == 0 || y ==39 ){
            //THE LEFT AND RIGHT OF THE ARRAY
            card[x][y] = '|';
            }
        else if( x == 6){
            card[x][y] = ' ';
        }
            // THE LETTER STRUCTURES OF THE ARRAY
        else if ( y == 2 || y== 4 || y== 6 || y==8 || y ==10 || y==12 ||y== 16||y==23|| y == 26 || y==28 || y==30|| y==34 ){
            card[x][y] = '$';
            }
        }
    }
    
    // THE REST OF THE BUILD OF THE ARRAY
    for (int x = 0; x < 8; x++){
        for (int y = 0; y < 40; y ++){
            if (x== 1){
                if ( y==3|| y==9 || y==13 || y==14 || y==19 || y==21 || y ==22 || y==24 || y ==26 || y ==27 || y ==31 || y==32 || y==37 ){
                    card[x][y]='$';
                }
            }
            else if (x== 2){
                if ( y==18|| y==36){
                    card[x][y]='$';
                }
            }
            else if (x== 3){
                if ( y==3|| y==9 || y==17 || y==27 || y==35){
                    card[x][y]='$';
                }
            }
            else if (x== 4){
                if ( y==18|| y==36){
                    card[x][y]='$';
                }
            }
            else if (x== 5){
                if ( y==3|| y==13 || y==14 || y==19 || y==19 || y==21 || y ==22 || y==31 || y ==32 || y == 37 ){
                    card[x][y]='$';
                }
            }
        }
    }
    // PRONTING OUT THE ARRAY
    for (int x = 0; x < 8; x++){
        for (int y = 0; y < 40; y ++){
            cout << card[x][y];
        }
        cout <<endl;
    }
}

// CALCULATES THE TOTAL TWO CARDS OF THE PLAYER
int amout ( std::string cardone,std::string cardtwo)
{
    int card1, card2;
    
    //FUNCTION BELOW
    card1= checker(cardone);
    
    //FUNCTION BELOW
    card2 = checker(cardtwo);
    
    return card1 +card2;
}

int checker( std::string cardone)
{
    //GIVES K,Q,J THE VALUE OF 10
    if ( cardone == "king" || cardone == "queen"|| cardone =="jack")
    {
        return 10;
    }
    //DETERMINS THE VALUE OF ACE
    else if ( cardone == "ace")
    {
        // MADE ACE A STRING SO THAT I WOULDNT HAVE TO CHECK FOR CIN FAIL
        string ace;
        int loop = 1;
        // FOREVER LOOP MUST PICK 11 OR 1
        do
        {
                //PLAYER GETS TO DECIDE VALUE OF ACE
                cout << "would you like to make your ace = 1 or 11?" <<endl;
                cin >> ace;
                if ( ace =="11" || ace == "1")
                {
                    if (ace == "1")
                    {
                        return 1;
                    }
                    else return 11;
                }
            // IF PLAYER TRYS TO GET AN ERROR OR HITS OTHER BUTTON THAN 1 OR 11
            cout << ace << " is not an option, try again.." <<endl;
            
        } while (loop == 1);
        
    }
    else if( cardone == "two")
    {
     
        
        return 2;
    }
    else if( cardone == "three")
    {
        return 3;
    }
    else if( cardone == "four")
    {
        return 4;
    }
    else if( cardone == "five")
    {
        return 5;
    }
    else if( cardone == "six")
    {
        return 6;
    }
    else if( cardone == "seven")
    {
        return 7;
    }
    else if( cardone == "eight")
    {
        return 8;
    }
    else if( cardone == "nine")
    {
        return 9;
    }
    else if( cardone == "ten")
    {
        return 10;
    }
    return 0;
    cout << "Error" ;

}

//  Black gets to pick either hit or stand ( hit new card adds to value
int  hitorstand ( int ptotal, card &gamecard, deck & blackjack )
{
    string hitorstand;
    string one;
    string suit;
    string loop= "yes";
    
    do
    {
        cout<< "would you like to hit or stand? (H/S)"<<endl << ">";
        cin >> hitorstand;
        
        
        if (hitorstand[0] =='h'|| hitorstand[0] == 'H')
        {
        gamecard = blackjack.dealcard();
        gamecard.thecard();
        one = gamecard.get_number();
            suit = gamecard.get_suit();
        // vector holds all cards you ever hit to get
            v.push_back (one + " of "+ suit);
            
        ptotal= ptotal + checker (one);
        if (ptotal >=21)
            {
                return ptotal;
            }
            cout << "your new total is " << ptotal <<endl;
        }
        //cout << " this is hit or stand " << hitorstand << endl;
        else{
                        return ptotal;
        }
        // forever loop only can return if greater than 21 or stands
    }while ( loop == "yes");
    
    return ptotal;
}

// cant be 10 is only used if dealer is dealt at start and then doesnt hit 21
void dealersturn ( int dealer , int player , card& card , deck& deck, string& cantbeten )
{
    // holds the string value of the next card in the deck ( top card)
    string holddealersnewcard;
    
    if (dealer < player )
    {
            cout << "the dealer flips over ";
        
            card = deck.dealcard();
            holddealersnewcard = card.get_number();
        
       // cout << "this is the vale of card "<<  holddealersnewcard << endl;
        
        // this was created in case dealer hits ace then not 10 value , because if dealer checks hand and then doesnt have 21 how can dealer then get 21 on the next card fliped
        if (cantbeten == "yes")
        {
            do {
                    card = deck.dealcard();
                    holddealersnewcard = card.get_number();
                
               //  cout << "the new the vale of card "<<  holddealersnewcard << endl;
                
                    // doesnt allow users next card to be a 10
                    if (holddealersnewcard == "king" || holddealersnewcard == "queen" || holddealersnewcard == "jack" || holddealersnewcard == "ten")
                        {
                           // cout << "bad" <<endl;
                        }
                        else
                        {
                           // cout << "good" <<endl;
                            // as soo as cant be is no it is no forever so therefore this isonly ran once and only if dealer first card is ace
                            cantbeten = "no";
                        }
                
                }while (cantbeten == "yes" );
        }
        else
        {
            //  top card is passed out
            card = deck.dealcard();
            
            // only the number value of the top card
            holddealersnewcard = card.get_number();
        }
            // print out of what card is
            card.thecard();
        
            // adds the dealers cards together and decides if dealer show use ace as 11 or 1
            dealer= dealer+ dchecker (holddealersnewcard, dealer);
        
         cout << "the dealers total is " << dealer <<endl;
        //recrusion
         dealersturn( dealer, player , card, deck, cantbeten);
    }
    else
    {
        // dealer gets over 21 the player wins
        if ( dealer > 21)
        {
            cout << "dealer bust and player wins!!!"<<endl;
        }
        else
        {
            //dealer had higher amont than user and was under  or at 21
            cout << "dealer wins" << endl;
        }
    }
}

// checks the vale of the dealers hand determins what to do with ace value
int dchecker( std::string card , int total  )
{
    
    if ( card == "king" || card == "queen"|| card =="jack")
    {
        return 10;
    }
    else if ( card == "ace")
    {
        if (total + 11 == 21)
        {
            cout <<"Black Jack" << endl;
            return 11;
        }
        else if (total + 11 >  21)
        {
            return 2;
        }
        else
        {
            return 11;
        }
    }
    else if( card == "two")
    {
        return 2;
    }
    else if( card == "three")
    {
        return 3;
    }
    else if( card == "four")
    {
        return 4;
    }
    else if( card == "five")
    {
        return 5;
    }
    else if( card == "six")
    {
        return 6;
    }
    else if( card == "seven")
    {
        return 7;
    }
    else if( card == "eight")
    {
        return 8;
    }
    else if( card == "nine")
    {
        return 9;
    }
    else if( card == "ten")
    {
        return 10;
    }
    return 0;
    cout << "Error" ;
    
}

// checks  to see if player is at 21 or over and therefore bust
void dealerVSplayer( int ptotal, int dtotal, card& card , deck& deck, string & cantbeten )
{
    if (ptotal == 21)
    {
        cout << "we have a winner!!!";
        }
        else
        {
            if (ptotal > 21)
            {
                cout << "Bust you have " << ptotal << " dealer wins"<< endl;
            }
            else
            {
                // dealer gets to hit or stand
                dealersturn( dtotal, ptotal , card, deck, cantbeten);
            }
        }
    // cout << " the amout of player is " << ptotal <<endl;
}

// the majority of the gameplay algoritum is in here
void gamplay ( card& card , deck& deck , int& ptotal, int& dtotal)
{
    // if player or dealer gets 21
    string gameover = "no";
    
    // if dealer gets ace but not 21
    string cantbeten = "no";
    
    // holds the string returned the number of the card in play
    std::string one,two,three;
    
    cout << endl;
    cout << "Here are your cards: " <<endl;
    
    // picks top card
    card= deck.dealcard();
    
    // couts actual card
    card.thecard();
    
    // gives one the number the card is
   one = card.get_number();
    
    //check black jack for player feature
   // one = "ace";
    cout << "and " <<endl;
    
    //picks top card
    card = deck.dealcard();
    
    // displays card
    card.thecard();
    
    // holds value of the card
    two = card.get_number();
    
    
    // CHECK FOR PLAYER BLACK JACK
    if (one == "ace"){
        if (two == "king"|| two == "jack" || two == "queen" || two == "ten"){
            gameover = "yes";
            cout<< "Black Jack, you win "<<endl;
        }
    }
    
    //CHECK FOR PLAYER BLACK JACK
    if (two == "ace"){
        if (one == "king"|| one == "jack" || one == "queen" || one == "ten"){
            gameover = "yes";
            cout<< "Black Jack, you win "<<endl;
        }
    }
    
    // if game is over this is then skipped
    if (gameover == "no")
    {
        cout << "here are the dealers cards: " <<endl;
    
        // top card
        card = deck.dealcard();
        
        // displays card cout
        card.thecard();
        
        //holds value of card as string
        three= card.get_number();
    
        //debugger to  make it so dealer cant not have bj and then flip over blackjack
        //three = "ace";
        //cout << "ace of I made this up";
    
        // checks for dealer first getting an ace
        if (three == "ace")
        {
            // next card in deck is drawn
            card = deck.dealcard();
        
            //debugger to  make it so dealer cant not have bj and then flip over blackjack
            //three = "four";
            
            // holds value for this next card in deck
            three= card.get_number();
        
            // if dealers first card is ace and next card is 10 then 21 blackjack game over
            if (three == "king"|| three == "jack" || three == "queen" || three == "ten"){
                cout <<  "dealer checks cards...  flips over second card ";
                card.thecard();
                cout << "the dealers total is 21"<<endl;
                cout << "Black jack!, you loose" <<endl;
                gameover = "yes";
            }
            else{
                cantbeten= "yes";
            
                cout << "dealer checks cards .... no black jack"<< endl;
                dtotal = 11;
            }
        }
    }
    
    if (gameover == "no"){
        
        // gets value of cad one and two or the useer/ allows user to pick 1 or 11
        ptotal = amout (one, two);
        
        // gets value of dealers hand but not if dealer drws the first card being an ace
        if (dtotal !=11)
        {
            cout << "and  ?" << endl;
            
            // GETS VALUE OF DEALERS FIRST CARD
            dtotal = checker (three);
        }
        
        cout << "your total is " << ptotal << endl;
        
        // IF DEALER DRAWED A LETS USER KNOW COMPUTER CAN MAKE VALUE ONE FR 11
        if (dtotal == 11)
        {
            cout << "the dealers total is 1 or 11 + ? " << endl;
        }
        else{
        cout << "the dealers total is "  << dtotal << " + ? " << endl;
        }
        
        // player gets topick hit or stand
        ptotal=  hitorstand( ptotal, card, deck);
    
        // it is now the dealers turn to hit or stand
        dealerVSplayer( ptotal, dtotal, card, deck, cantbeten );
    }
}
void fbi ( theinfo::player& pi)
{
    string info, age, name, height, email, phonenumber;
    
    
cout << "we like to keep records of all players who play this game, would you answer some questions? (y/n)" << endl;
    cin >> info;
    
    if ( info[0] == 'y' || info[0] == 'Y')
    {
        cout << "what is your name?" << endl;
        cin.ignore();
        getline ( cin, name);
        cout << "what is you age?" << endl;
         getline ( cin, age);
        cout << "what is your height in inches? " << endl;
        getline( cin, height);
        cout << "what is your email? " << endl;
        getline ( cin, email);
        cout << "what is your phone number" << endl;
        getline( cin, email);
    
        pi.name = name;
        pi.age = age;
        pi.height = height;
        pi.email = email;
        pi.phonenumber= phonenumber;
        
        
       // cout << "this is the name in th efunction" << pi.name <<endl;
        
    }
}

void seevector()
{
    char answer;
    
    cout << " would you like to ssee all the cards that you got on the hit (vector)? (y/n) " << endl;
    
    try {
            cin >> answer;
        
        if (cin.fail())
            {
            throw 6;
            }
        }
    
    catch ( int e )
    {
        cout << " ill take that as a NO" << endl;
        answer = 'n';
        
    }
    
    if  (answer == 'y' ||  answer == 'Y'){
        cout << " here are all the cards you got on the hit" << endl;
        
        for ( unsigned int i = 0; i < v.size(); i++)
            cout << v[i]<< endl;
    }
}