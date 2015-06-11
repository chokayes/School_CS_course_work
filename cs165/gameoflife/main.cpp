//
//  main.cpp
//  gameoflife
//
//  Created by Ali Payne on 5/26/14.
//  Copyright (c) 2014 engr.oregonstate. All rights reserved.
//

#include <iostream>
#include <string>
#include <cstdlib>      //exit
#include <stdlib.h>     /* srand, rand */
#include <time.h>        //time


using std:: cin;
using std:: endl;
using std:: cout;

const int height =22;
const int length =22;
char board[height][length];
    
void create()
{
    //building of the array of arryays
    for (int x = 0; x < height  ; x++){
        for (int y = 0; y < length; y++ ){
            if (x == 0 || x == (height-1)){
                board[x][y] = '-'; // creates top and bottom of playing field
            }
            else if ( y == 0 || y ==(length-1) ){
                board[x][y] = '|'; // creats sides of glaning field
            }
            else{
                board[x][y] = ' ';
            }
        }
    }
}

void printgameboard()
{
    // this prints out the array
    for (int x = 0; x < height  ; x++){
        for (int y = 0; y < length; y++ ){
            cout << board [x][y];
        }
        cout << endl;
    }
}

void introduction();
// post condition the rules of the game are displayed

void howmanylifes  ( int &life, const int height,  const int length);
// post condition the amout of lives the user wants to statrt with is entered

void  fill_board_with_lives ( const int life );
//post condition The game board is filled with the random amout of lives

void loopholewinner (int life);
// if usere enetered certain amout of lives they always win

void menu();
//as of right now this only displays the menue

void life();
// checks all cells for life aka *

void death();
// checks  all cells for death aka " "

void checksurroundingsfirst( int &pointer, int &pointery );
// looks at 8 surrouning cells for * and kills cells

void checksurroundingssecond( int &x, int &y);
//looks at 8 surrounding cells and brings them to life via rule 4

void check( int &loop);
// checks if you actually beat the game
int main(int argc, const char * argv[])
{
    int life;
    char playagaing;
    
    introduction ();
    
    do{
        
        int loop = 1; // once loop isnt 1 you beat the game
        
        // finds out how many lifes user wants to have
        howmanylifes (life, height, length);
    
        // makes 2 d arary of gamefield
        create();
    
        // this fills in the array of arry with the # of lives requested randomly
        fill_board_with_lives (life);
    
        //prints gameboard
        printgameboard();
        
        //checks to see if we have a winner
        check( loop);
    
        while ( loop ==1)
        {
            // displays the menu
            menu();
            // print current board
            printgameboard();
            check(loop);
        }
        
        cout << " Would you like to play again? (Y/N)" <<endl;
        cin >> playagaing;
        
    }while  (playagaing == 'y' || playagaing == 'Y');
    
    cout << " Have a great day!" <<endl;
    
    return 0;
}

void introduction()
{
    cout << " Welcome to Ali Payne's Version of 'CONWAYS'S GAME OF LIFE'" << endl;
    cout << "Instructions: "<<endl;
    cout <<" you will be prompted to entry the amonnt of lives you wish to strart with" <<endl;
    cout << " thoes lives with be randomly placed on the field of play" <<endl;
    cout << " If you can some how fill the entire bored with *'s you will have beat the game" <<endl;
    cout << "Conways Rules"<<endl;
    cout<< "1.) Any live cell with fewer than two live neighbours dies, as if caused by under-population. " <<endl;
    cout<< "2.) Any live cell with two or three live neighbours lives on to the next generation. " <<endl;
    cout<< "3.) Any live cell with more than three live neighbours dies, as if by overcrowding. " <<endl;
    cout<< "4.) Any dead cell with exactly three live neighbours becomes a live cell, as if by reproduction." << endl;
    cout << " ...............";
    cout << "Goodluck!!!" <<endl;
    cout << " " <<  endl;
}

void howmanylifes  ( int &life, const int height,  const int length)
{
    char toomuch = 'n';
    do{
        cout << "How many many lifes do you wish to start with: " << endl;
        cin >>life;
        if (life >= ((height-2)*(length-2) +1))
            {
            cout<<  " this is not a vaild entry thats more, lives than spaces avalible" <<endl;
            cout << "you entrered "<<  life << " try again with a lower number" << endl;
            toomuch= 'y';
            }
        } while (toomuch == 'y');
    
    cout << " You entered " << life << " for your amount of lives" << endl;
}

void  fill_board_with_lives ( const int life )
{
    int astrick= 0;
    
    for (int userlives = 0; userlives < life  ; userlives++){
        int hold1 =rand()%(height-2)+1;
        int hold2 =rand()%(length-2)+1;
        
        int y= 0;
        
        //if random number was already  we will look for anoter location that way user gets same amount of lives requested
        do{
            
            if ( board[hold1][hold2] == ' '){
                board [hold1][hold2] = '*';
                 astrick = astrick+1;
                y = 1;
            }
            else{
                hold1= rand()%(height-2)+1;
                hold2 = rand()%(height-2)+1;
            }
        }while (y == 0);
        // I was using this to print all values  that were randomly assigned
        //cout <<" hold1 = "  << hold1 << " hold2 =" << hold2 <<endl;
    }
    // wanted to see how many * were printed
    cout << " this is how many *'s there is "<< astrick <<endl;
}

void menu()
{
    char option, redo= 'n';
    do{
        cout << " N or Q" << endl;
        cout << " N = Next Life Cycle" <<endl;
        cout << " Q = Quit" <<endl;
        cout << "> ";
        cin >> option;
    
        if (option == 'N')
            {
               redo = 'n';
                life();
                death();
            }
        else if (option ==  'Q'){
                cout << " this game is really, a game of luck Im glad you have realized you cant win this time, try again" <<endl;
                exit(0);
            }
        else if (cin.fail()){
                cout << "something went wrong, try again" <<endl;
                redo = 'y';
            }
        else if ( option != 'Q' || option != 'N')
        {
            cout << option << " is not an avalible option, try again";
            redo = 'y';
        }
        
        }while ( redo == 'y');
}

void life()
{
    int *pointerx;
    int *pointery;
    
    for (int x = 0; x < height  ; x++){
        for (int y = 0; y < length; y++){
            if (board [x][y] == '*')
                {
                    pointerx = &x;
                pointery = &y;
                    checksurroundingsfirst(*pointerx,*pointery);
                }
        }
    }
}

void checksurroundingsfirst( int &x, int &y)
{
    bool check[8]; // checks 8 values for *
    int whattodo = 0; // adds true values
    
   // cout << " you make it this far, there is a life is at this point " << x << " and " << y  << endl;
    
    //top left check
    if (board[x-1][y-1] == '*'){
        check[0] = true;
    }
    else{
        check[0] = false;
    }
    
    //top middle  check
    if (board[x-1][y] == '*'){
        check[1] = true;
    }
    else{
        check[1] = false;
    }
    
    //top right check
    if (board[x-1][y+1] == '*'){
        check[2] = true;
    }
    else{
        check[2] = false;
    }
    
    //left middle check
    if (board[x][y-1] == '*'){
        check[3] = true;
    }
    else{
        check[3] = false;
    }
    
    //right middle check
    if (board[x][y+1] == '*'){
        check[4] = true;
    }
    else{
        check[4] = false;
    }
    
    //bottom left check
    if (board[x+1][y-1] == '*'){
        check[5] = true;
    }
    else{
        check[5] = false;
    }
    
    //bottom middle check
    if (board[x+1][y] == '*'){
        check[6] = true;
    }
    else{
        check[6] = false;
    }
    
    //bottom right check
    if (board[x+1][y+1] == '*'){
        check[7] = true;
    }
    else{
        check[7] = false;
    }
    
 for (int c = 0; c < 8; c++){
    if (check[c] == true )
    {
        whattodo= whattodo+1;
    }
 }
    if (whattodo <= 1 || whattodo >=4){
        board[x][y] = ' ';
    }
}

void death()
{
    int *pointerx;
    int *pointery;
    
    for (int x = 0; x < height  ; x++){
        for (int y = 0; y < length; y++){
            if (board [x][y] == ' ')
            {
                pointerx = &x;
                pointery = &y;
                checksurroundingssecond(*pointerx,*pointery);
            }
        }
    }
}

void checksurroundingssecond( int &x, int &y)
{

    bool check[8]; // checks 8 values for *
    int whattodo = 0; // adds true values
    
    // cout << " you make it this far, there is a death at this point " << x << " and " << y  << endl;
    
    //top left check
    if (board[x-1][y-1] == '*'){
        check[0] = true;
    }
    else{
        check[0] = false;
    }
    
    //top middle  check
    if (board[x-1][y] == '*'){
        check[1] = true;
    }
    else{
        check[1] = false;
    }
    
    //top right check
    if (board[x-1][y+1] == '*'){
        check[2] = true;
    }
    else{
        check[2] = false;
    }
    
    //left middle check
    if (board[x][y-1] == '*'){
        check[3] = true;
    }
    else{
        check[3] = false;
    }
    
    //right middle check
    if (board[x][y+1] == '*'){
        check[4] = true;
    }
    else{
        check[4] = false;
    }
    
    //bottom left check
    if (board[x+1][y-1] == '*'){
        check[5] = true;
    }
    else{
        check[5] = false;
    }
    
    //bottom middle check
    if (board[x+1][y] == '*'){
        check[6] = true;
    }
    else{
        check[6] = false;
    }
    
    //bottom right check
    if (board[x+1][y+1] == '*'){
        check[7] = true;
    }
    else{
        check[7] = false;
    }
    
    for (int c = 0; c < 8; c++){
        if (check[c] == true )
        {
            whattodo= whattodo+1;
        }
    }
    
    if (whattodo == 3 ){
        board[x][y] = '*';
    }
}

void check (int &loop)
{
    
    int winner = 0;
    
    for (int x = 0; x < height  ; x++){
        for (int y = 0; y < length; y++ ){
            if (board [x][y] == '*')
            {
                winner= winner +1;
            }
        }
    }
    if ( winner == (height-2) * (length-2))
    {
        cout<<  " it looks like we have a winner!!!!" <<endl;
        loop = 2;
    }
}


