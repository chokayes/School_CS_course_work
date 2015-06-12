<?php 
ini_set('display_errors', 'On');

require_once './simpletest/autorun.php';
require_once './simpletest/web_tester.php';

echo "Write test first makes things easier <br>";

echo "This portion is based on an api so the results changed on glass dorrs behalf then of course test wont pass"; 
echo" reason being all test cases presented below are based on expected test results returned from api call"; 


class EmployerStatsForm extends WebTestCase 
{
    // I would say that all of theses are unit test.. I think 
    
    //test to see if page exist    
    function testdoesEmployerStatsformExist() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200); 
    }
  
    //test to see if you can enter invaild job title
    function testInvalidTitle() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);

        $this->setField("role", " ");
        $this->setField("location", " ");
      
        $this->clickSubmit("submit");

        $this->assertResponse(200);
        $this->assertText("TITLE MUST BE ENTERED");
    }
   
    //test to see if there are no entries of that job title that user is informed that 
    // no results found
  function testIfsearchreturnsNoResults() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
         $this->assertResponse(200);

        $this->setField("role", "hatey");
       
        $this->clickSubmit("submit");

        $this->assertResponse(200);
        $this->assertText("No Results Found");
    }
    

    //test to see if a valid submission works ex "sales"
  function testValidTitleOneWord() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);

        $this->setField("role", "sales");
        $this->setField("location", " ");
      
        $this->clickSubmit("submit");

        $this->assertResponse(200);
        $this->assertText("1.) Macy's");
    }
    
    //test to see if vaild submission works with space ex "sales manager"
      function testValidTitleWithSpaces() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);

        $this->setField("role", "sales manager");
        $this->setField("location", "oregon");
      
        $this->clickSubmit("submit");

        $this->assertResponse(200);
        $this->assertText("1.) hibu");
    }
    
    //test above helped find the break in program is two words entered java developer 
    //curl function or other function that was usable could not work because 
    //java developer was passed to url rather than java%20developer 
    
    
    //test to see if Link was displayed of companty website
    function testIfLinkisValid() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);

        $this->setField("role", "target");
        $this->setField("location", " ");
      
        $this->clickSubmit("submit");

        $this->assertResponse(200);
        $this->assertLink("Visit Target website");
    }
  
    //basically the reason im iffy is : 
    // ex i type in luch for role 
    //its only returns 9 entries 
    // thats only one page of results 
    // do we need to test for when the next button should show up or not? 
    
    //test to see that if no more than 10 results then next button doesnt display
    function testifOnlyOnepgOfResults() 
    {
        //go to website 
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);
        
        //enter lunch as search 
        $this->setField("role", "lunch");
        
        //hit search 
        $this->clickSubmit("submit");
         $this->assertResponse(200);
         
         // hit the next button even though it doenst exist
        $this->clickSubmit("next");
         $this->assertText(" ");
        
        
        // might have found a quick fix 
        // what if we just hit next even if it doesnt exist we will get an error but we can check to make sure that error prints...
        
        
        //job title "lunch"
        //location not ented
        //returns 9 entries
        
        //idk if this is worth testing becauuse
        //would have to check that next is not avalible 
    }
  
  
   //test to see if next button s present and works
    function testIfNextbuttonexist() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);

        $this->setField("role", "teacher");
        $this->setField("location", " ");
      
        $this->clickSubmit("submit");
    
        $this->assertResponse(200);
        $this->assertText("1.) New York City Department of Education");
        
        $this->clickSubmit("next");
         
        $this->assertResponse(200);
        $this->assertText("11.) Charlotte-Mecklenburg Schools");
        
    }
   
   //test to see if previous buttion is present and works
    function testIfPrevbuttonexist() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);

        $this->setField("role", "teacher");
        $this->setField("location", " ");
      
        $this->clickSubmit("submit");
    
        $this->assertResponse(200);
        $this->assertText("1.) New York City Department of Education");
        
        $this->clickSubmit("next");
         
        $this->assertResponse(200);
        $this->assertText("11.) Charlotte-Mecklenburg Schools");
    }
   
   // test to see that job count is correct with api 
   function testifjobcountcorrect() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);

        $this->setField("role", "basketball");
       
        $this->clickSubmit("submit");
    
        $this->assertResponse(200);
        $this->assertText("1.) NBA ");
        
        $this->clickSubmit("next");
         
        $this->assertResponse(200);
        $this->assertText("11.) One on One Basketball");
        
        $this->clickSubmit("next");
         
        $this->assertResponse(200);
        $this->assertText("21.) NYC Basketball League");
        
        $this->clickSubmit("next");
        $this->assertResponse(200);
        $this->assertText("31.) Basketball Hall of Fame");
        
        $this->clickSubmit("next");
        $this->assertResponse(200);
        $this->assertText("41.) Peninsula Basketball Officials Association");
        
        $this->clickSubmit("previous");
        $this->assertResponse(200);
        $this->assertText("31.) Basketball Hall of Fame");
    }
    
     //test above helped find the error in program on number list of similar position 
    //api returns 10 entries per call but if on last page and only 7 entries left need to be calculate dfor
    
  
  //test to see that if no former employee write up is avalible that user is informed that is the case
   function testNoFomerEmployeeReview() 
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);

        //job title "lunch"
        //location not ented
        //returns 9 entries
        $this->setField("role", "lunch");

        //7th entry states the followin: 
        //Below is a former employees prespective : 
        //No former employee perspective avalible 
        $this->clickSubmit("submit");
        $this->assertResponse(200);
        $this->assertText("No former employee perspective avalible");
    }
   
   //test to see that if no upper management write up is avalible that user is informed that is the case
  function testNoCeoInfo()  
    {
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);
        
         //job title "lunch"
        //location not ented
        //returns 9 entries
        $this->setField("role", "lunch");
        
        //3rd entry states the followin: 
        //Below is upper management information: 
        //No upper managemnet information avalible 
        $this->clickSubmit("submit");
        $this->assertResponse(200);
        $this->assertText("No upper managemnet information avalible");
    }
   
   
   //______________________2nd iteration test_______________________________ 
   
   //code has not been changed but once it is the below test case will also need to be changed as well
   
   //test to see that 0.0 is not displayed - the clients request
   // need to update code prior to completing this
    function testToSeeIfZerosAreDisplayed() 
    {
        //go to this portion of the project
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);
     
        //search the following criteria 
         // c++ developer returns first entry returns 
        $this->setField("role", "c++ developer"); 
         
        //hit submit
        $this->clickSubmit("submit");
        $this->assertResponse(200);
        
        // make sure that 0.0 is not shown and instead NA is 
        //for the following search of c++ developer 
        //Culture and Values: 0.0 
        
        // need to make sure N/A shown besides 0.0 
         $this->assertText("Culture and Values: N/A");
       
       // i think that shoudl do it ... worked now 
       
    }
 
 //below is an attempt at 6 and 7 not too sure if its correct or not
 
 //_________________________#6 and #7 ______________________________________
 //#6 = implement your assigned user story 
 
 // user story for employer statistics: 
// When a particular role is chosen, users view ratings and statistics for the role and company. 
//Comparable’s are generated and displayed.

// for the purpose of an example
// particular role = sports reporter 

//#7 = Preform acceptance testing
 
 //test to see that if no upper management write up is avalible that user is informed that is the case
  function testUserStoryExample()  
    {
        //go to this portion of the project
        $this->get("https://cs361-project-b-technicallyrice.c9.io/employer_statistics.php");
        $this->assertResponse(200);
        
         //job title  to be entered "sports reporter"
        //location not ented
        $this->setField("role", "sports reporter");
         $this->clickSubmit("submit");
         
        //case study requires that users view ratings and statistcs for role and company
        //and that comparables are generated and displayed
        
        // this will check ratings is dispayed 
         $this->assertText("Overall Rating: 4 Satisfied");
        
        //this will check statistics are displayed 
         $this->assertText("Culture and Values: N/A");
         
         //this will check role is displayed for company 
          $this->assertText("Job Title: Sports Reporter");
         
         //this will check comparables are generated and displayed
          $this->assertText("2.) KCBD Television");
        
    }
}
?>
