
<!DOCTYPE html>
<html>
  <head>
    <title>Page Title</title>
    <!-- Include meta tag to ensure proper rendering and touch zooming -->
<!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
      <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    <!-- Include jQuery Mobile stylesheets -->
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

    <!-- Include the jQuery library -->
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>

    <!-- Include the jQuery Mobile library -->
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>

    <script>
        function noBack() { window.history.forward(); }

        var stockData; 
        var username; 
        var starttotal;  
        var currenttotal; 
        
        $("#stocktable").html(stockData); 
      
        $("#infousername").text("Welcome back " + username);
        $("#infostartTotal").text("start total = $" + starttotal);
        $("#infocurrentTotal").text("current total = $" +currenttotal);
                        

        
      $(function(){
        //Initially returning user signin form wil be hidden.
        $('#returnsignin').hide();
				//Initially new user user signin form wil be hidden.
        $('#newsignin').hide();

        //if new user button hit show new user login and
        //hid old user log in
				$("#newuser").click(function(){
					$("#newsignin").show();
					$("#returnsignin").hide();
					});

        //if old user button hit show old user login and
        //hid new user log in
				$("#olduser").click(function(){
					$("#returnsignin").show();
					$("#newsignin").hide();
					});
          
           $(".buttontobuysell").click(function(){  
                var buyorsell =  $("input:radio[name=radio-choice]:checked").val(); 
                var stock = $("#textSymbol").val(); 
               var stockShares = $("#stockQtyAmount").val(); 
               var stockPrice = $('#stockprice').val(); 
                var stockDesc = $('#symbolDesc').val(); 
               if( stockShares) {
                    stockShares = parseInt(stockShares);  
               }; 
               
               alert('decision = '+ buyorsell + ' stock =' +stock+ ' stockshares =' +stockShares + ' stock price  = ' + stockPrice + ' stock description = ' + stockDesc); 
               
               
               
            
            
            var hold = {move: buyorsell, email:username, symb: stock , desc: stockDesc, price: stockPrice, qty: stockShares}; 
                    //we are buying this stock or selling stock
               
              
               
           $.post(
                    'test.php',
                    hold,
                    function (data) {
                      alert("Data loaded: " + data);

                    //turn string to object
                    data = eval("(" + data + ")");

                    if (data.success || data.indexOf('success')){
                        
                        alert("in here"); 
                        
                        //clear out buy/sell things
                        
                        //reload page with redirect 
                        var url = "function.php?email=" + username;

                      $.get(url, function(data){
                        data = eval("(" + data + ")");
                        
                          //console.log(data.info[0]);
                          var info =  data.info;
                          var tableToShow = "<thead><tr> <th>sym</th> <th data-priority='3'>desc</th> <th>Qty:</th> <th data-priority='1'>Buy Price</th> <th data-priority='2'>yesterdays close</th> <th>current price</th> </tr><thead><tbody>"; 
                                                
                          $.each(info, function(index, value) {
                              //alert(index + ": " + value.name);
                              tableToShow += "<tr><td>"+value.name+"</td><td>"+ value.description+"</td><td>"+value.amount+"</td><td>"+value.start+"</td><td>"+ value.yesterday+"</td><td>"+ value.current+"</td></tr>";
                          }); 
                          
                          tableToShow += "</tbody>";  
                          
                         
                          
                          $("#stocktable").html(tableToShow).trigger( "enhance" ); 
                        
                          //$('#pagetwo').page('refresh', true);
                         
                        stockData = tableToShow
                         username = data.username;
                        starttotal = data.startTotal;
                        currenttotal = data.currentTotal;

                        $("#infousername").text("Welcome back " + username);
                        $("#infostartTotal").text("start total = $" + starttotal);
                        $("#infocurrentTotal").text("current total = $" +currenttotal);
                        
                      });
                        
                        
                         $.mobile.changePage("#pagetwo");
                        $('#main').listview('refresh');
                        

                    }
                    else {

                        alert("error with buy/sell"); 
                    }


                }).fail(function(jqXHR, exception){
                alert("jqXHR = " + JSON.stringify(jqXHR) + "ex = " + exception);
                });
               
         }); 
          
          $(".usersignout").click(function(){
              window.location.replace("http://web.engr.oregonstate.edu/~payneal/cs496/assignment4/");
          }); 

// log story shport not possible with javascript because of cors must do php
        //this waits for old/new  user Sign in to be pressed
        $(".usersignin").click(function(){

          if (this.name == "newsignin"){
            var submit = {
              oldornew: "new",
              username: $('#newusername').val(),
              icon: $('#cameraInput').val(),
              email: $('#newemail').val(),
              password: $('#newpassword').val(),
              broker: $('#newbroker').val(),
              cash: $('#newcash').val(),
            }

            

            if (!isNaN(submit.cash) == false){
                alert('cash amount enterd is not a real dollar amount');
                return;
            }

          //  alert("button pushed should be creating new user\n vars =" + JSON.stringify(submit));

            $.post(
              'function.php',
              submit,
              function (data) {
              //  alert("Data loaded: " + data);

                //turn string to object
                data = eval("(" + data + ")");

                if (data.success == "new user created"){
                
                   $.mobile.changePage("#pagetwo");

                }
                else {

                    $("#noticenew").text("this email already has an account");
                }


              })
              .fail(function(jqXHR, exception){
                alert("jqXHR = " + JSON.stringify(jqXHR) + "ex = " + exception);
              });

            }
            else if (this.name == "oldsignin") {
              var hold = { oldornew: "old", email: $('#oldusername').val(), password: $('#oldpassword').val()}
              //alert("button pushed should be creating old user\n vars =" + JSON.stringify(hold));
                $.post(
                  'function.php',
                  { oldornew: "old", email: $('#oldusername').val(), password: $('#oldpassword').val(),},
                    
                  function (data) {

                      //ERROR check
                      //alert("Data loaded: " + data);

                      //turn string to object
                      data = eval("(" + data + ")");

                      //error check
                      //alert("Data loaded: " + data.success);

                    //if this lets us know this is a valid user
                    if (data.success == "valid user"){

                      
                        
                      // now i need to display data from website
                      var email = $('#oldusername').val();
                      //show get url
                       //alert("http://papertrader2-1007.appspot.com/homework?email=" + email  );
                      var url = "function.php?email=" + email;

                      $.get(url, function(data){
                        data = eval("(" + data + ")");
                        
                          //console.log(data.info[0]);
                          var info =  data.info;
                          var tableToShow = "<thead><tr> <th>sym</th> <th data-priority='3'>desc</th> <th>Qty:</th> <th data-priority='1'>Buy Price</th> <th data-priority='2'>yesterdays close</th> <th>current price</th> </tr><thead><tbody>"; 
                                                
                          $.each(info, function(index, value) {
                              //alert(index + ": " + value.name);
                              tableToShow += "<tr><td>"+value.name+"</td><td>"+ value.description+"</td><td>"+value.amount+"</td><td>"+value.start+"</td><td>"+ value.yesterday+"</td><td>"+ value.current+"</td></tr>";
                          }); 
                          
                          tableToShow += "</tbody>";  
                          
                         
                          
                          $("#stocktable").html(tableToShow).trigger( "enhance" ); 
                        
                          //$('#pagetwo').page('refresh', true);
                         
                        stockData = tableToShow
                         username = data.username;
                        starttotal = data.startTotal;
                        currenttotal = data.currentTotal;

                        $("#infousername").text("Welcome back " + username);
                        $("#infostartTotal").text("start total = $" + starttotal);
                        $("#infocurrentTotal").text("current total = $" +currenttotal);
                        
                      });
                        
                        
                         $.mobile.changePage("#pagetwo");
                        $('#main').listview('refresh');
                    }
                    else {
                        $("#noticeold").text("incorrect login information");
                    }
                  })
                  .fail(function(jqXHR, exception){
                    alert("jqXHR = " + JSON.stringify(jqXHR) + "ex = " + exception);
                  });
            };
        });
      });

        
        
                                
        
    </script>

  </head>
  <body>
    <!-- page one of the moble app which is login screen -->
    <div data-role="page" id="pageone">

      <!-- header of the log in screen -->
      <div data-role="header">
        <h1>The Paper Trade</h1>
      </div>

      <!-- start of body section of login-->
      <div data-role="main" class="ui-content">
        <p>
          Paper trading (sometimes also called "virtual stock trading") is
           a simulated trading process in which would-be investors can
            'practice' investing without committing real money.
        </p>

        <a  id="newuser" data-role="button" style="background: blue; color: white;">New user</a>
        <a  id="olduser" data-role="button" style="background: blue; color: white;">Existing User</a>

        <!-- this shows new log in-->
        <div id="newsignin">
          <h3>Create User</h3>
          <p id="noticenew"></p>

  			  <div class="form-group">
  			    <label for="text">Username:</label>
  		 		  <input type="text" class="form-control" id= "newusername" name="username" placeholder="Enter username" required>
  			  	<label for="text">Usericon:</label>
            <input type="file" capture="camera" enctype="multipart/form-data" accept="image/*" id="cameraInput" name="cameraInput">
        	  <label for="text">Email:</label>
  		 		  <input type="text" class="form-control" id= "newemail" name="email" placeholder="Enter email" required>
  		 		  <label for="text">Password:</label>
  	 			  <input type="password" class="form-control"  id= "newpassword" name="password" placeholder="Enter password" required>
  			  </div>
			    <h4>Pick a Brokerage</h4>
    			<p>
    				A brokerage firm, or simply brokerage, is a financial institution that facilitates
    				the buying and selling of financial securities between a buyer and a seller. Brokerage
    				firms serve a clientele of investors who trade public stocks and other securities, usually
    				 through the firm's agent stockbrokers.
    			</p>
  				<select class="form-control" id= "newbroker" name="broker" required >
						<option disabled value="none">--Select--</option>
						<option  value="T">TradeKing - No Min Balance and $4.95 Per trade </option>
    				<option  value="S">Scottrade - Min Ballence $2,500 and $7.00 Per trade </option>
    				<option  value="E">Etrade - Min Balance $500 and $9.99 Per trade</option>
						<option  value="A">Ameritrade - Min Balance $500 and $9.99 Per trade</option>
						<option value="U">USAA - No Min Balance and $8.95 Per trade</option>
						<option value="O">Optionshouse - No Min Balance and $4.95 Per trade</option>
					</select>
  				<h4>Total Cash</h4>
  				<p>Amount of money you wish to open your account with</P>
  				<label class="sr-only" for="exampleInputAmount">Amount (in dollars)</label>
      		<div class="input-group">
        		<div class="input-group-addon">$</div>
        		<input type="text" class="form-control"  id= "newcash" name="cash" placeholder="Amount" required>
        		<div class="input-group-addon">.00</div>
      		</div>
				  <br>
				  <br>
				<a class="usersignin" name="newsignin" data-role="button" style="background: blue; color: white;">Sign In</a>
        </div>
        <!-- this shows existing login-->
        <div id="returnsignin">
          <h2>Existing User</h2>
          <p id="noticeold"> Grading  username: admin, password:admin </p>

          <div class="form-group">
            <label for="text">Email:</label>
            <input id = "oldusername" type="text" class="form-control" name="email" placeholder="Enter email" required>

            <label for="text">Password:</label>
            <input id = "oldpassword" type="password" class="form-control" name="password" placeholder="Enter password" required>
            <!--<a href="#pagetwo"> Signin </a> -->
            <a class="usersignin" name="oldsignin" data-role="button" style="background: blue; color: white;">Sign In</a>
          </div>
        </div>

      </div> <!-- end of main page-->

      <!-- footer of log in screen -->
      <div data-role="footer">
        <h1>&#169; 20<?php echo date("y"); ?> The Paper Trade  </h1>
      </div>

    </div>
    <!-- end of page one -->

    <!-- page two which is main component of mobile web app-->
    <div data-role="page" id="pagetwo">

      <!-- header of main page of web app-->
      <div data-role="header">
        <h1>Welcome real home after login</h1>
      </div>

      <!-- header of main page of web app-->
      <div data-role="main" class="ui-content">
        <p id=infousername></p>
        <p id=infostartTotal></p>
        <p id=infocurrentTotal></p>
        
    
                <table  data-role="table" data-mode="columntoggle" class="ui-responsive" id="stocktable"></table>  
       
           
          
          <div class="BuySell">
            <fieldset data-role="controlgroup">
	           <legend>Buy Or Sell Stock:</legend>
                <input type="radio" name="radio-choice" id="radio-choice-1" value="buy" checked="checked" />
                <label for="radio-choice-1">Buy</label>
                
     	      <input type="radio" name="radio-choice" id="radio-choice-2" value="sell"  />
                <label for="radio-choice-2">Sell</label>
            </fieldset>
              
              
              <label for="text">Symbol:</label>
            <input id = "textSymbol" type="text" class="form-control" name="textsymbol" placeholder="Enter symbol" required>
              <label for="text">description:</label>
              <input id = "symbolDesc" type="text" class="form-control" name="textsymbol" placeholder="Enter stock description" >
              

            <label for="number"># of Shares:</label>
            <input id = "stockQtyAmount" type="number" class="form-control" name="moveQty" placeholder="how many shares" required>
               <label for="number">Price of Stock:</label>
              <input id = "stockprice" type="number" step="0.01" class="form-control" name="stockPrice" placeholder="Price of Stock"
                     >
              
            <!--<a href="#pagetwo"> Signin </a> -->
          </div>

          <a class="buttontobuysell" name="buysell" data-role="button" style="background: green; color: white;">Buy Or Sell</a>
                  
        <a class="usersignout" name="signout" data-role="button" style="background: blue; color: white;">Sign Out</a>  
          
      </div>

      <!-- footer of 2nd page-->
      <div data-role="footer">
        <h1>&#169; 20<?php echo date("y"); ?> The Paper Trade  </h1>
      </div>

    </div>
    <!-- end of page2 of mobile web app -->



  </body>

</html>
