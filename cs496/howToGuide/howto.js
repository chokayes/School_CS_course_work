
// this is the ember app
howto = Ember.Application.create(); 

howto.Router.map(function(){
	this.resource("whyEmber"); 
	this.resource("setupEmber");
	this.resource("setupIndex");  
	this.resource("createFirstRoute");
	this.resource("dynamicContent");   
}); 




//routes - this must be captial (weird)
howto.SetupEmberRoute = Ember.Route.extend({
	model: function() {
			return setupEmber; 
	}
}); 





var setupEmber = [{
	id: '1.)',
	what: "create a folder include the following: index.htmt, knowto.js, (folder)libs" 
}, {
	id: '2.)', 
	what: "go to <a href='http://emberjs.com/'>Visit Ember</a> <br> go to top of page and click on builds<br>click download and put code in libs<br> Since jquery in necessary to have, download it and also put it in the libs <br>repeat this cycle for all the depenets which can be found at <a href='http://emberjs.com/'>Ember dependents'</a><br>can also instal via command line just click vistember above" 
}, {
	id: '3.)',
	what: "now that everthing is downloaded we will creat an app <br> its easy just go to the file knowto.js<br>write the following line in it: knowto= Ember.Application.create() <br> reload the website open the developer console"
	
}, {
	id: '4.)', 
	what: "if everything is correctly installed you  will see the following <br>DEBUG: ------------------------------- <br>DEBUG: Ember: 1.8.1 <br>DEBUG: Handlebars : 1.3.0<br>DEBUG: jQuery     : 1.11.3<br>DEBUG: -------------------------------<br>DEBUG: For more advanced debugging, install the Ember Inspector from https://chrome.google.com/webstore/detail/ember-inspector/bmdblncegkenkacieihfhpjfppoconhi<br>"
},{
	id: '5.)', 
	what: "you are now ready to start developing an Ember App"
}]; 


var showdown = new showdown.Converter();
Ember.Handlebars.helper('format-markdown', function(input) {
	return new Handlebars.SafeString(showdown.makeHtml(input)); 
})


