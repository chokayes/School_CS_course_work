import webapp2
#this is for sessions
from webapp2_extras import sessions

#http://jsonapi.org/
import json

import base
import cgitb

from datetime import datetime
from google.appengine.ext import ndb

#use to turn string to dictionaty
import ast

from google.appengine.api import images

from google.appengine.ext import blobstore

# for help with setting up database
#https://docs.google.com/document/d/1AefylbadN456_Z7BZOpZEXDq8cR8LYu7QgI7bt5V0Iw/edit

#database
class User(ndb.Model):
	uname = ndb.StringProperty(required= True)
	email = ndb.StringProperty(required= True)
	password = ndb.StringProperty(required= True)
	startTotal = ndb.FloatProperty(required= True)
	currentTotal = ndb.FloatProperty(required= True)
	broker = ndb.KeyProperty(kind='Broker', required= True)
	picture = ndb.BlobProperty(default=None)

class Broker(ndb.Model):
	fee = ndb.FloatProperty(required= True)
	firm = ndb.StringProperty(required= True)

class stock(ndb.Model):
	symbol = ndb.StringProperty(required= True)
	description = ndb.StringProperty(required= True)
	currentprice = ndb.FloatProperty(required= True)
	yesterdaysclose = ndb.FloatProperty(required= True)

class Ownedstock(ndb.Model):
	stock= ndb.KeyProperty(kind='stock', required= True)
	owner = ndb.KeyProperty(kind='User', required= True)
	qty = ndb.IntegerProperty(required= True)
	buydate = ndb.StringProperty(required = True)
	buyprice = ndb.FloatProperty(required= True)
#end of database

#will hold all messages that will be posted due to errors/successes/information i was to display
template_var = {}

#this is the object of the trader, made to reduce reads in datastore
class Trader:
	def __init__(self, uname, usekey, theemail, thestartTotal, thecurrentTotal, thebrokersfee, usericon = None):
		self.name = uname
		self.uk = usekey
		self.email = theemail
		self.startTotal= thestartTotal
		self.currentTotal = thecurrentTotal
		self.brokerfee = thebrokersfee
		self.icon = usericon

#this used to display all stock holding for a user. show total gain and cash avalible
def showholding(self, template_var):
	x= self.session.get('online')
	stockBuyer = x['uk']
	#self.response.write("this is the stock owner {}".format(stockBuyer))


	template_var['userinfo']= {'name':x['name'], 'startTotal': x['startTotal'], 'currentTotal':x['currentTotal']}
	#show holdings

	#get all stocks
	stocks= [{'key':x.key.urlsafe(),'symbol': x.symbol, 'description':x.description, 'current':x.currentprice, 'yesterday': x.yesterdaysclose } for x in stock.query( ancestor= ndb.Key(stock, self.app.config.get('stockholder'))).fetch()]
	#error check
	#self.response.write("below are all stocks ")
	#self.response.write(stocks)

	#get all owned
	owned= [ {'stock':x.stock.urlsafe(), 'qty':x.qty, 'owner':x.owner.urlsafe(), 'buyprice': x.buyprice } for x in Ownedstock.query(ancestor= ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))).fetch()]
	#error check
	#self.response.write("below are all owned stocks by this user")
	#self.response.write(owned)

	template_var['info'] = []
	for b in stocks:
		for a in owned:
			if b['key'] == a['stock'] and a['owner'] == stockBuyer:
				it = {'name': b['symbol'] , 'description': b['description'] , 'amount': a['qty'] ,'start': a['buyprice'] , 'current':b['current'], 'yesterday': b['yesterday']}
				template_var['info'].append(it)
	return self, template_var

#used to see if username exist
def apiOldUser(self, email, password):
	#fist get all users
	establishedUsers = [{'email':x.email, 'pass': x.password} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
	#see if this is a vail  email in db 
	usernameExist = False
	#see if this is valid username and password 
	signIn = False
	# check to see if vaild
	for x in establishedUsers: 
		if x['email'] == email: 
			usernameExist= True
			if x['pass'] == password: 
				signIn = True


	if usernameExist == True and signIn == True: 
		return "yes"
	elif usernameExist == True and signIn == False: 
		return "Invalid password"
	else:  
		return "Invalid username and password"

#used to input brokerage or verify 
#returns brokers name, price, and key 
def getBrokerNamePriceKey(self, broker):
	brokercost = None#broker holder
	if broker == "T" or broker == "O":
		brokercost= 4.95
	elif broker == "S":
		brokercost = 7.00
	elif broker =="E" or broker == "A":
		brokercost = 9.99
	elif broker =="U":
		brokercost = 8.95
	else: 
		brokercost = None



	# get the actual name of the brokerage
	brokername = None#broker holder
	if broker == "T":
		brokername = "Tradeking"
	elif broker == "O":
		brokername = "Optionshouse"
	elif broker == "S":
		brokername = "Scottrade"
	elif broker =="E":
		 brokername = "Etrade"
	elif broker == "A":
		brokername ="Ameritrade"
	elif broker =="U":
		brokername ="USAA"
	else: 
		brokername = None

	#get all brokerages that are in the database
	#all brokeragers name and key are placed in this dictionary
	known_brokerage = [{'name':x.firm, 'key':x.key.urlsafe()} for x in Broker.query(ancestor= ndb.Key(Broker, self.app.config.get('thebrokers'))).fetch()]

	#needed to access dictionary, starts at -1 because 1st element of dictionary is 0
	index= -1
	#are we adding a breokerage, as of right now
	addbrokerage = "yes"

	# this is used for the user to connect them to the broker that they use
	brokers_Key  =  None
	
	# brokerage is either already in the databse or we jsut put it in the databse

	#things are set up this way becase we eventually want to  unhard code this value so 
	#users can add brokerages that arent already in db with price

	# this is to find out which is the case
	for x in known_brokerage:
		index += 1 #increment each time to next stock held in dictionary
		#check to see if smbol in dictiionary matches  the symbol user wants to sell
		if '{}'.format(known_brokerage[index]['name']) == brokername:
			addbrokerage = "no"
			break

	#this broker is not currently in the databse, so needs to be added
	if addbrokerage == "yes":
		#put broker in databse
		#creats key to  the brokers database
		k = ndb.Key(Broker, self.app.config.get('thebrokers'))
		brokerage = Broker(parent= k)
		#saves all things the database holds
		brokerage.fee = float(brokercost)
		brokerage.firm= brokername

		#sense we just added a broker we need to hold the key for the user
		# as KeyProperty
		brokers_Key= brokerage.put()#save to database

	else:
		brokers_Key = ndb.Key(urlsafe=known_brokerage[index]['key'])

	#error check 
	#self.response.write("treturning the following cost: {}, name: {}, key: {}".format(brokercost, brokername, brokers_Key))		

	return(brokercost, brokername, brokers_Key)

#see if email is already in use
def isEmailinDB(self, email): 
	loop = -1
	emailInUse = "no"
	establishedUsers = [{'email':x.email} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
	for x in establishedUsers:
		loop+= 1
		if email == establishedUsers[loop]['email']:
			emailInUse = 'yes'

	# if email is already in database then user already has account
	if emailInUse == "yes":
		return True
	else: 
		return False

#adds user or tells you issue with adding user
def createAUser(self, username, usericon, email, password, broker, cash): 
	#check the broker submission
	bcost, bname, bKey =  getBrokerNamePriceKey(self, broker)
	if bcost == None or bname == None: 
		return "bad broker cost or name"

	#check if this email is already in db
	inDB = isEmailinDB(self, email)

	if inDB == True:
		#already established user
		return "eamil has account"

	else: 
		#continue to creat new user
		k = ndb.Key(User, self.app.config.get('userholder'))
		theuser = User(parent= k)
		#saves all things the database holds
		theuser.uname = username
		theuser.email = email
		#should hash the password or something
		#once you want to do something serious with this
		theuser.password = password
		theuser.startTotal = float(cash)
		theuser.currentTotal = float(cash)

		#check to see if value exist for user icon
		if usericon: 
			theuser.picture = usericon

		#put brokers key in user
		theuser.broker =  bKey
		#save to database, and hold users key
		Users_Key = theuser.put()
		return "success"
		

#if exiting user then verify, if new user then create -API
#curl --data "oldornew=create" -H  "Accept: appn/json" http://localhost:8080/homework
class homework(base.BaseHandler): 
	def post(self):
		#self.response.headers['Access-Control-Allow-Origin'] = 'http://web.engr.oregonstate.edu'
		#self.response.headers['Access-Control-Allow-Headers'] = '*'
		#self.response.headers['Access-Control-Allow-Methods'] = 'POST, OPTIONS'
		#self.response.headers['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8'
		if 'application/json' not in self.request.accept:
			self.response.status = 406
			self.response.status_message = "API only supports application/json MIME type "
			#application/json: Official MIME type for json
			return
		else: 
			#grab all post data
			test = self.request.POST.items()
			#this is to take away outside makes 		
			test= test[0]	
			test = test[0]
			#time to convert this to a dictionary
			test = ast.literal_eval(test)

			#error check
			#self.response.write("this the pasword; {}".format(test['password']))			
			oldornew = test['oldornew']
			if oldornew: 
				if oldornew == "old": 
					#collect all the varibles needed to verify
					password = test['password']
					email = test['email']
					if email and password: 
						answer = apiOldUser(self, email, password)

						if answer == "yes":
							self.response.status = 200
							self.response.write({"success":"valid user"})
						elif answer == "Invalid password" or answer == "Invalid username and password": 
							self.response.status = 406
							self.response.write({"error":answer})	
					else: 
						self.response.status = 200
						self.response.write({"error":"missing password or email"})						
						return
				#create a new user
				elif oldornew == "new":
					test = self.request.POST.items()
					#this is to take away outside makes 		
					test= test[0]	
					test = test[0]
					#time to convert this to a dictionary
					test = ast.literal_eval(test)

					#username, usericon, email, password, broker, cash): 
					username = test['username']
					icon = test['icon']
					email = test['email']
					password = test['password']
					broker = test['broker']
					cash =test['cash']

					#they are missing something we will know
					#maybe make icon required later but not for now
					if username and email and password and broker and cash:
						answer = createAUser(self, username, icon, email, password, broker, cash) 

						if answer == "bad broker cost or name": 
							self.response.status = 406
							self.response.write({"error":answer})
							return
						elif answer == "eamil has account": 
							self.response.status = 406
							self.response.write({"error":answer})
							return
						elif answer == "success": 
							self.response.status = 200
							self.response.write({"success":"new user created"})
							return
						else: 	
							#should never get here
							self.response.status = 406
							self.response.write({"error":"real issue on our hands"})
							return
					else: 
						self.response.status = 406
						self.response.write({"error": "missing username, email, password, broker, or cash"})

				else:  
					self.response.status = 406
					self.response.write({"error": " old or new was decided but not valid"})
			else:
				self.response.status = 406
				self.response.write("error= need to state if old or new")
				return

	#returns all of users information 
	#http://localhost:8080/homework?email=(useremail)
	def get(self):
		if 'application/json' not in self.request.accept:
			self.response.status = 406
			self.response.status_message = "API only supports application/json MIME type "
			#application/json: Official MIME type for json
			return
		else:
			email= self.request.get('email', default_value = None)
			if email: 
				#first get all users basic information
				#collect all of the users
				theusers = [{ 'username':x.uname,'email':x.email, 'startTotal':x.startTotal,
				 'currentTotal':x.currentTotal, 'brokerkey':x.broker.urlsafe(),
				 'usersKey':x.key.urlsafe(), 'pic': x.picture} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
				#needed to access dictionary, starts at -1 because 1st element of dictionary is 0
				index= -1
				#determin if user is in database or not
				validuser= False
				for x in theusers:
					#loop through all of dictionary
					index += 1 #increment each time to next stock held in dictionary
					if theusers[index]['email'] == email:
						validuser = True
						#this is the info associated with this email
						theTrader = {'username': theusers[index]['username'], 'usersKey': theusers[index]['usersKey'], 'startTotal': theusers[index]['startTotal'], 'currentTotal': theusers[index]['currentTotal'], 'brokersKey': theusers[index]['brokerkey']  }
						
					
						
				if validuser  == False:
					self.response.write({"error": "email is not a valid user"})
			else: 
				elf.response.status = 200
				self.response.write({"error":"missing email to gather information"})	

			# now its time to collect all this users owned stocks
			#get all stocks
			stocks= [{'key':x.key.urlsafe(),'symbol': x.symbol, 'description':x.description, 'current':x.currentprice, 'yesterday': x.yesterdaysclose } for x in stock.query( ancestor= ndb.Key(stock, self.app.config.get('stockholder'))).fetch()]
			#error check
			#self.response.write("below are all stocks ")
			#self.response.write(stocks)

			#get all owned
			owned= [ {'stock':x.stock.urlsafe(), 'qty':x.qty, 'owner':x.owner.urlsafe(), 'buyprice': x.buyprice } for x in Ownedstock.query(ancestor= ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))).fetch()]
			#error check
			#self.response.write("below are all owned stocks by this user")
			#self.response.write(owned)

			theTrader['info'] = []
			for b in stocks:
				for a in owned:
					if b['key'] == a['stock'] and a['owner'] == theTrader['usersKey']:
						it = {'name': b['symbol'] , 'description': b['description'] , 'amount': a['qty'] ,'start': a['buyprice'] , 'current':b['current'], 'yesterday': b['yesterday']}
						theTrader['info'].append(it)
			
			self.response.write(json.dumps(theTrader))

	#put a stock in the position of a user on a buy
	#curl -X PUT --data "email=" -d "symb=" -d"desc="" -d"price=" -d"qty=  -H "Accept: application/json" http://localhost:8080/homework
	def put(self): 
		if 'application/json' not in self.request.accept:
			self.response.status = 406
			self.response.status_message = "API only supports application/json MIME type "
			#application/json: Official MIME type for json
			return
		else:
			test = self.request.POST.items()
			test = test[0][0]
			test = ast.literal_eval(test)
			#self.response.write(test)
			
			email=  test['email']
			symb=  test['symb']
			desc = test['desc'] 
			price = test['price']
			qty = test['qty']
			if email and symb and desc and price and qty:
				theTrader= None
				#get this users information
				theusers = [{ 'username':x.uname,'email':x.email, 'startTotal':x.startTotal,
					'currentTotal':x.currentTotal, 'brokerkey':x.broker.urlsafe(), 'realuserkey': x.key, 
					 'usersKey':x.key.urlsafe()} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
				#needed to access dictionary, starts at -1 because 1st element of dictionary is 0
				index= -1
				#determin if user is in database or not
				validuser= False
				thesolution=None
				for x in theusers:
					#loop through all of dictionary
					index += 1 #increment each time to next stock held in dictionary
					if theusers[index]['email'] == email:
						validuser = True
						theofficialkey = theusers[index]['realuserkey']
						#this is the info associated with this email
						theTrader = {'username': theusers[index]['username'], 'usersKey': theusers[index]['usersKey'], 'startTotal': theusers[index]['startTotal'], 'currentTotal': theusers[index]['currentTotal'], 'brokersKey': theusers[index]['brokerkey']  }
				if False:
					self.response.write({"error": "email is not a valid user tester"})
					return

				#get the fee 
				fee = None
				known_brokerage = [{'fee':x.fee, 'key':x.key.urlsafe()} for x in Broker.query(ancestor= ndb.Key(Broker, self.app.config.get('thebrokers'))).fetch()]
				for x in known_brokerage: 
					if x['key'] == theTrader['brokersKey']:
						fee = x['fee']

				#make sure the buyer can affored this purchase
				userscash = theTrader['currentTotal']

				if userscash >= (float(qty) * float(price)) + float(fee):
					#they can make this purchase
					#lower the amount of cash they have
					userscash -= ((float(qty) * float(price)) + float(fee))
					theTrader['currentTotal'] = userscash

					#connect to the database on user,
					#update the total amount of current total cash associated with user
					User_key = ndb.Key(urlsafe=theTrader['usersKey'])

					#key = thetrader['uk'].parent()
					thisUser = User_key.get()
					#change the amount of cash in current total
					thisUser.currentTotal = theTrader['currentTotal'] 
					#save this change
					thisUser.put()
				else:
					elf.response.status = 200
					self.response.write({"error":"user doesnt have enough cash for this buy"})

				#check to see if this symbol exist
				#used to hold the stock bought and the owned stock key
				astock_key= None
				astockprice= None

				ostock_Key= None

				stocksInDatabase = [{'symbol':x.symbol, 'description':x.description, 'currentPrice':x.currentprice, 'yesterdaysPrice':x.yesterdaysclose, 'thekey':x.key, 'key':x.key.urlsafe()} for x in stock.query(ancestor= ndb.Key(stock, self.app.config.get('stockholder'))).fetch()]
				isStockInDatabase= False
				isStockOwnedByThisUser= False

				for x in stocksInDatabase: 
					if x['symbol'] == symb: 
						isStockInDatabase = True
						astock_key = x['key']
						trythis = x['thekey']
						astockprice= x['currentPrice']
				
				if isStockInDatabase == False: 
						self.response.write(" this stock is not in the database")
						# we are creating a new stock
						k = ndb.Key(stock, self.app.config.get('stockholder'))
						astock = stock(parent= k)
						#saves all things the database holds
						astock.symbol = symb
						astock.description = desc
						astock.currentprice = float(price)
						astock.yesterdaysclose  = float(price)
						#save to database
						astock_key = astock.put()

						#now that the stock is created or found , we can give ownership
						#to this buy
						k = ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))
						ostock = Ownedstock(parent= k)
						#saves all things the database holds
						ostock.stock = astock_key
						ostock.owner = User_key
						ostock.qty = int(qty)
						ostock.buydate = datetime.now().strftime("%Y-%m-%d")
						ostock.buyprice = float(price)
						ostock_Key= ostock.put()
						
						self.response.status = 400
						self.response.write({"success":"user has purchased new stock"})
						return
				else: 
					#adjusting the old stock
					
					#we know its in the database but now we need to find out if this user ownes 
					# an instance of this stock or not
					#get all owned stock
					owned= [{ 'stock':x.stock.urlsafe(), 'qty':x.qty, 'owner':x.owner.urlsafe(),
					'buyprice': x.buyprice, 'key':x.key.urlsafe(), 'realkey':x.key } for x in Ownedstock.query(ancestor= ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))).fetch()]

					#check to see if this user owns that stock
					alreadyOwnedByUser= False
					oldQty= None
					oldBuyPrice=None
					ownedstockKey=None
					for x in owned: 
						if x['owner'] == theTrader['usersKey'] and x['stock'] == astock_key: 
							alreadyOwnedByUser = True
							oldQty = x['qty']
							oldBuyPrice = x['buyprice']
							ownedstockKey= x['key']
						
					if alreadyOwnedByUser == True: 
						#we know we are adjusting this stock
						#self.response.write("old qty = {}, oldbuyprice = {}, owned stock key = {}".format(oldQty, oldBuyPrice, ownedstockKey)) 

						thisownedstock = ndb.Key(urlsafe=ownedstockKey)
						#increase qty
						thisownedstock = thisownedstock.get()
						thisownedstock.qty= oldQty + int(qty)
						#changebuydate
						thisownedstock.buydate = datetime.now().strftime("%Y-%m-%d")
						#also we will change the buy price
						#to change buy price (old qty * buyprice) + (new buy qty  * new buy price)/old qty + new qty
						#we do this to get the average buy price as oppose to storing each buy in a list
						#I might change it later but This is geared towards swing trading so Idk if its really
						#i wouldnt buy something I already own I would just ride the swing
						thisownedstock.buyprice = ((float(oldBuyPrice) * int(oldQty)) + (int(qty) * float(price)) )/ (int(qty) + int(oldQty))
						#save the changes
						thisownedstock.put()
						self.response.status = 400
						self.response.write({"success":"user has purchased more of a stock they already owned"})
						return
					else: 

						#self.response.write("somehow you got here")
						
						#we know that we are adding just to new owner
						#if its not continue here
						#now that the stock is created or found , we can give ownership
						#to this buy
						k = ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))
						ostock = Ownedstock(parent= k)
						#saves all things the database holds
						ostock.stock = trythis
						ostock.owner = theofficialkey
						ostock.qty = int(qty)
						ostock.buydate = datetime.now().strftime("%Y-%m-%d")
						ostock.buyprice = float(price)
						ostock_Key= ostock.put()
						self.response.write({"success":"user has made purchase"})
						return
			else:
				self.response.status = 200
				self.response.write({"error":"missing information need to make buy"})		

	#deletes a stock that the user has
	#curl -X DELETE -H "Accept: application/json" http://localhost:8080/api?email={users email address}&symb={symb}&qty={}
	def delete(self):
		if 'application/json' not in self.request.accept:
			self.response.status = 406
			self.response.status_message = "API only supports application/json MIME type "
			#application/json: Official MIME type for json
			return
		else:
			email= self.request.get('email', default_value = None)
			symb= self.request.get('symb', default_value = None)
			qty = self.request.get('qty', default_value= None)
			if email and symb and qty: 
				#check if there is an owned stock with this user

				#get this users information
				theusers = [{ 'username':x.uname,'email':x.email, 'startTotal':x.startTotal,
					 'currentTotal':x.currentTotal, 'brokerkey':x.broker.urlsafe(),
					 'usersKey':x.key.urlsafe()} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
				
				validEmail= False
				uCurrentTotal = None
				uBrokerKey  = None
				uKey = None
				for a in theusers: 
					
					if a['email'] == email: 
						# get the stock key of said stock they wish to sell
						validEmail = True
						uCurrentTotal = a['currentTotal']
						uBrokerKey = a['brokerkey']
						uKey = a['usersKey']


				if validEmail !=  True: 
					self.response.status = 200
					self.response.write({"error":"Not a valid email"})	
				else: 

					stocks= [{'key':x.key.urlsafe(),'symbol': x.symbol, 'description':x.description, 'current':x.currentprice, 'yesterday': x.yesterdaysclose } for x in stock.query( ancestor= ndb.Key(stock, self.app.config.get('stockholder'))).fetch()]
					
					stocksPrice = None 
					stocksKey = None
					validStock = False

					for b in stocks:
						if b['symbol'] == symb:
							#we have person key so lets check owned stocks
							stocksPrice = b['current']
							stocksKey = b['key']
							validStock = True

					if validStock == False: 
						self.response.status = 200
						self.response.write({"error":"Not a valid stock to sell, not owned"})	
					else: 
						owned = [{ 'stock':x.stock.urlsafe(), 'qty':x.qty, 'owner':x.owner.urlsafe(),
						'buyprice': x.buyprice, 'key':x.key.urlsafe() } for x in Ownedstock.query(ancestor= ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))).fetch()]

						userOwns= False
						someoneElseOwns = False
						oldQty = None
						ownedKey = None
								
						for c in owned: 
							if uKey == c['owner'] and c['stock'] == stocksKey: 
								userOwns= True
								oldQty= c['qty']
								ownedKey = c['key']
							# know we know that this is a stock owner by this use that can be altered
							elif uKey != c['owner'] and c['stock'] == b['key']:
								someoneElseOwns = True
					

						if userOwns == False:
							self.response.status = 200
							self.response.write({"error":"user does not own this stock to sell"})
							return
						else: 
							#get users trader fee 
							fee = None
							#get all brokerages that are in the database
							#all brokeragers name and key are placed in this dictionary
							known_brokerage = [{'fee':x.fee, 'key':x.key.urlsafe()} for x in Broker.query(ancestor= ndb.Key(Broker, self.app.config.get('thebrokers'))).fetch()]
							for x in known_brokerage: 
								if x['key'] == uBrokerKey:
									fee = x['fee']
	
							if fee == None: 
								self.response.status = 200
								self.response.write({"error":"user with UNKOWN BROKER KEY???(NOT POSSIBLE)"})
								return

							#lets check to see if they are trying to sell more than they have
							if int(qty) > int(oldQty):
								self.response.status = 200
								self.response.write({"error":"user cant sell more than they own"})	
								self.response.write("own = {}, but selling {}".format(oldQty, qty))	
								return
							else:  
								# want to sell all of stock or some
										
								#create varible that will hold the amount they make in chash with this sell
								cashdeposit= None

								# find the cash of the stocks that they wish to sell
								# put that amount in the eralier created varible
								# subtrast the fee because its a sell
								cashdeposit = float(uCurrentTotal) + ((float(qty) * float(stocksPrice) ) - float(fee))
								
								#self.response.write(cashdeposit)
								 	
								#get the User and update the mone 
								getUser = ndb.Key(urlsafe=uKey)
								getUser= getUser.get()
								#change cashty <= c['qty']
								getUser.currentTotal = cashdeposit
								#getUser.put()

								#user is deleting all of owned stock
								if int(qty) == int(oldQty):
									# deleted this owned stock
									tobedeleted = ndb.Key(urlsafe=ownedKey)
									tobedeleted= tobedeleted.get()
									tobedeleted.key.delete()
									# if no one else owns the stock we can delete it from database
									if someoneElseOwns == False:
										#if soemone else owns same stock then just delete
										tobedeleted= ndb.Key(urlsafe=stocksKey)
										tobedeleted = tobedeleted.get() 
										tobedeleted.key.delete()
									self.response.status = 200
									self.response.write({"success":"sold all"})	
									return
								else:
									#user is selling just a portion of his stock
									tobesold = ndb.Key(urlsafe= ownedKey)
									tobesold = tobesold.get()
									tobesold.qty= int(oldQty) - int(qty)
									tobesold.put()
									self.response.write({"success":"sold sum"})	
									return
			else: 
				self.response.status = 200
				self.response.write({"error":"missing information need to make buy"})

		
#when you get more time to do this use this resource to further understand RESTful API
# http://www.restapitutorial.com/lessons/httpmethods.html
class api(base.BaseHandler):
	#curl -H  "Accept: application/json" http://localhost:8080/api
	#to put vars in get you put then in url

	#GET = Read
			# show the stock that the user has in possesion
	def get(self):
		#ex of get varibles
		#http://localhost:8080/api?this=x&how=abc

		#oldOrNew = self.request.get("this")
		#oldOrNew2 = self.request.get("how")
		#self.response.write("<h1> GET </h1>")
		#self.response.write("this equals the following {}".format(oldOrNew))
		#self.response.write("how equals the following {}".format(oldOrNew2))

		if 'application/json' not in self.request.accept:
			self.response.status = 406
			self.response.status_message = "API only supports application/json MIME type "
			#application/json: Official MIME type for json
			return
		else:
			getthis = self.request.get('whatYouWant')
			if getthis == 'allUserNamesinfo':
				#get all of the users information besides the password
				qry1 = [{ 'username':x.uname,'email':x.email, 'startTotal':x.startTotal,
				 'currentTotal':x.currentTotal, 'brokerkey':x.broker.urlsafe(),
				 'usersKey':x.key.urlsafe()} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
				 #print out jason string
				self.response.write(json.dumps(qry1))

			elif getthis == 'oneUsernamesinfo':
				#ex.
					#http://localhost:8080/api?whatYouWant=oneUsernamesinfo&email=a
				#emails are one of a kind unlike usernames
				email= self.request.get('email', default_value = None)
				if email:
					#collect all of the users
					theusers = [{ 'username':x.uname,'email':x.email, 'startTotal':x.startTotal,
					 'currentTotal':x.currentTotal, 'brokerkey':x.broker.urlsafe(),
					 'usersKey':x.key.urlsafe()} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
					#needed to access dictionary, starts at -1 because 1st element of dictionary is 0
					index= -1
					#determin if user is in database or not
					validuser= False

					for x in theusers:
						#loop through all of dictionary
						index += 1 #increment each time to next stock held in dictionary
						if theusers[index]['email'] == email:
							validuser = True
							#this is the info associated with this email
							theTrader = {'username': theusers[index]['username'], 'usersKey': theusers[index]['usersKey'], 'startTotal': theusers[index]['startTotal'], 'currentTotal': theusers[index]['currentTotal'], 'brokersKey': theusers[index]['brokerkey']  }
							#make into json object
							self.response.write(json.dumps(theTrader))
							break
					if validuser  == False:
						self.response.write({"error": "email is not a valid user"})
				else:
					self.response.write({"error": "email is required to get a certain users info"})

			elif getthis == 'allStocksinDatabase':
				#load all stocks in databse in this varible
				stocksInDatabase = [{'symbol':x.symbol, 'description':x.description, 'currentPrice':x.currentprice, 'yesterdaysPrice':x.yesterdaysclose, 'key':x.key.urlsafe()} for x in stock.query(ancestor= ndb.Key(stock, self.app.config.get('stockholder'))).fetch()]
				self.response.write(json.dumps(stocksInDatabase))
			elif getthis == 'stoksOnwed':
				#isnt stocks owned by one user just all stocks owned shows user key
				owned= [{ 'stock':x.stock.urlsafe(), 'qty':x.qty, 'owner':x.owner.urlsafe(),
				'buyprice': x.buyprice, 'key':x.key.urlsafe() } for x in Ownedstock.query(ancestor= ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))).fetch()]

				self.response.write(json.dumps(owned))
			else:
				self.response.write({"error": "right now api only provides: all users info, one usernames info, all stocks in databse, or stock owned by certain users"})
		return

	#min 15 of  http://eecs.oregonstate.edu/ecampus-video/player/player.php?id=99
	#curl --data "createorbuy=create" -H  "Accept: application/json" http://localhost:8080/api
	#to put vars in post you put then in --data

	#POST = create
			# creat a User, and or buy a stock
	def post(self):
		#fist get all users
		establishedUsers = [{'email':x.email, 'key': x.key.urlsafe()} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]

		if 'application/json' not in self.request.accept:
			self.response.status = 406
			self.response.status_message = "API only supports application/json MIME type "
			#application/json: Official MIME type for json
			return
		else:
			#does the user want to buy a stock or  create a user
			decision = self.request.get('createorbuy')
			if decision == 'create':
				#eroor check make sure entered buy
				#self.response.write(decision)

				#since they are trying to creat a new user they need to enter
					#need to enter username, email, password, a broker, and a start cash amount
				username = self.request.get('username', default_value = None )
				email= self.request.get('email', default_value = None)
				password= self.request.get('password', default_value = None)
				broker= self.request.get('broker', default_value = None)
				cash = self.request.get('cash', default_value = None)

				#verify all were entered
				if username and email and password and broker and cash:
				 #and email and password and broker and cash:
				 	#good ex.
					 	#curl --data "createorbuy=create" -d "username=ali" -d "email=ali@aol.com" -d "password" -H Accept: application/json" http://localhost:8080/api
					#self.response.write("You enetered: {} {} {} {} {}".format(username, email, password,broker,cash) )

					#now that we have that info lets first collect the broker cost
					brokercost = None#broker holder
					if broker == "T" or broker == "O":
						brokercost= 4.95
					elif broker == "S":
						brokercost = 7.00
					elif broker =="E" or broker == "A":
						brokercost = 9.99
					elif broker =="U":
						brokercost = 8.95

					# get the actual name of the brokerage
					brokername = None#broker holder
					if broker == "T":
						brokername = "Tradeking"
					elif broker == "O":
						brokername = "Optionshouse"
					elif broker == "S":
						brokername = "Scottrade"
					elif broker =="E":
						 brokername = "Etrade"
					elif broker == "A":
						brokername ="Ameritrade"
					elif broker =="U":
						brokername ="USAA"

					#get all brokerages that are in the database
					#all brokeragers name and key are placed in this dictionary
					known_brokerage = [{'name':x.firm, 'key':x.key.urlsafe()} for x in Broker.query(ancestor= ndb.Key(Broker, self.app.config.get('thebrokers'))).fetch()]

					#needed to access dictionary, starts at -1 because 1st element of dictionary is 0
					index= -1
					#are we adding a breokerage, as of right now
					addbrokerage = "yes"

					# this is used for the user to connect them to the broker that they use
					brokers_Key  =  None
					Users_Key =None

					# brokerage is either already in the databse or we jsut put it in the databse
					# this is to find out which is the case
					for x in known_brokerage:
						index += 1 #increment each time to next stock held in dictionary
						#check to see if smbol in dictiionary matches  the symbol user wants to sell
						if '{}'.format(known_brokerage[index]['name']) == brokername:
							addbrokerage = "no"
							break

					#this broker is not currently in the databse, so needs to be added
					if addbrokerage == "yes":
						#put broker in databse
						#creats key to  the brokers database
						k = ndb.Key(Broker, self.app.config.get('thebrokers'))
						brokerage = Broker(parent= k)
						#saves all things the database holds
						brokerage.fee = float(brokercost)
						brokerage.firm= brokername

						#sense we just added a broker we need to hold the key for the user
						# as KeyProperty
						brokers_Key= brokerage.put()#save to database

					else:
						brokers_Key = ndb.Key(urlsafe=known_brokerage[index]['key'])

					#at this point regardless if this is new brokkerage or not we have  key
					#we need the brokers ky to create a user so thats the next step

					# check is there is already a user with this email
					# prolly important that we acreate something later on that verifies email before saved in db
					loop = -1
					emailInUse = "no"
					establishedUsers = [{'email':x.email} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
					for x in establishedUsers:
						loop+= 1
						if email == establishedUsers[loop]['email']:
							emailInUse = 'yes'

					# if email is already in database then user already has account
					if emailInUse == "yes":
						# since user already has email  we will just informthem
						# the this email already has account
						self.response.write({"there is already an account associated with  the email {}".format(email)})
						return
					else:
						#now we can enter the New users information
						#put user in database
						#creats key to database

						k = ndb.Key(User, self.app.config.get('userholder'))
						theuser = User(parent= k)
						#saves all things the database holds
						theuser.uname = username
						theuser.email = email
						#should hash the password or something
						#once you want to do something serious with this
						theuser.password = password
						theuser.startTotal = float(cash)
						theuser.currentTotal = float(cash)

						#put brokers key in user
						theuser.broker =  brokers_Key
						#save to database, and hold users key
						Users_Key = theuser.put()

						#this is the traders information, had to save with urlsafe because couldnt store key in dictionary
						theTrader = Trader(username, Users_Key.urlsafe(), email, float(cash), float(cash), float(brokercost))

						#turn object to dictionary
						holder = theTrader.__dict__

						#make into json object
						self.response.write(json.dumps(holder))
				else:
					self.response.write({"error": "Missiong username, email, password, broker, or cash"})
					return
			elif decision == 'buy':
				#easy to implement from the code already written but not
					#completely satisfied with buy function,
					# still has issues with buying stock someone else owns
					# (logic shoudl be write just error with key or loop or if statment I think)
					#and issue with buying function when is already owned by user
				self.response.write("This api is not complete for buy function")
			else:
				self.response.write({"error": "missing create or buy"})
				return
			return
			#else:




			#self.response.write({"travel":{"bike":"fast","car":"can be fast","feet":"slow"}})

	#curl -X PUT -H "Accept: application/json" http://localhost:8080/api
	#Put = Update
			# change the price of a stock
	def put(self):
		#ex.
			#curl -X PUT --data "stockkey=ahBkZXZ-cGFwZXItdHJhZGVyciYLEgVzdG9jayIJc3RvY2tpbmZvDAsSBXN0b2NrGICAgICAgIALDA" -d "price=13" -H "Accept: application/json" http://localhost:8080/api

		if 'application/json' not in self.request.accept:
			self.response.status = 406
			self.response.status_message = "API only supports application/json MIME type "
			#application/json: Official MIME type for json
			return
		else:
			#grab the stock key
			stock_key = self.request.get('stockkey', default_value = None)
			#make sure that the stock key exits
			if stock_key:
				#error check to make sure we got stock key
				#self.response.write(stock_key)


				#load all stocks in databse in this varible
				stocksInDatabase= [{ 'key':x.key.urlsafe(), 'sym':x.symbol, 'description':x.description,
				'currentprice': x.currentprice, 'yesterdaysclose':x.yesterdaysclose } for x in stock.query(ancestor= ndb.Key(stock, self.app.config.get('stockholder'))).fetch()]

				symbol = None
				description= None


				#verify that this is a actual stock key
				isStockInDatabase='no'
				for x in stocksInDatabase:
					#self.response.write(x)
					#self.response.write("<br><br>")
					if x['key'] == stock_key:
						isStockInDatabase='yes'
						#hold varibles
						symbol = x['sym']
						description = x['description']

				#error check to know the answer
				self.response.write("is stock in database: {}".format(isStockInDatabase))

				#if this truely is a key then
				if isStockInDatabase == 'yes':
					#check to see if a new price was given
					price = self.request.get('price', default_value = None)
					if price:
						#make price a float
						price = float(price)
						#put the new price in the database
						stock_key = ndb.Key(urlsafe= stock_key)
						update = stock_key.get()
						update.currentprice = price
						update.put()
						self.response.write({"success": "{}:{} price was updated".format(symbol, description)})

					else:
						#price is required so error
						self.response.write({"error": "need updated price to change stock value"})
				else:
					self.response.write({"error": "not a valid stock key"})
			else:
				self.response.write({"error": "missing stock key"})
		return


	#curl -X DELETE -H "Accept: application/json" http://localhost:8080/api
	def delete(self):
		#ex.
			#curl -X DELETE -H "Accept: application/json" http://localhost:8080/api?email=admin2



		#grab user to deletes email
		email = self.request.get('email', default_value = None)

		#makes sure this is a vaild user
		validuser= 'no'
		#need to get key
		holdvaliduserkey= None

		#make sure the email exist
		if email:
			#fist get all users
			establishedUsers = [{'email':x.email, 'key': x.key.urlsafe()} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]

			#self.response.write(establishedUsers)


			#loop through all users
			for x in establishedUsers:
				#we find a match if this happens
				if x['email'] == email:
					#change the varible
					validuser = 'yes'
					#self.response.write("found user looking for")
					holdvaliduserkey = x['key']



		else:
			self.response.write({"error": "email is required to delete a user"})


		if validuser == 'yes':
			self.response.write("yes")

			# grab all owned stock
			owned= [{ 'stock':x.stock.urlsafe(), 'qty':x.qty, 'owner':x.owner.urlsafe(),
			'buyprice': x.buyprice, 'key':x.key.urlsafe() } for x in Ownedstock.query(ancestor= ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))).fetch()]

			#go through all owned stocks and delete any where the key matches
			for x in owned:
				if x['owner'] == holdvaliduserkey:

					#self.response.write(" found a match to ownere and held key")

					#first delete all of there owned stocks
					stock_key = ndb.Key(urlsafe= x['key'])
					stock = stock_key.get()
					stock.key.delete()

			# then delete the user itself
			user_key = ndb.Key(urlsafe= holdvaliduserkey)
			userToDelete = user_key.get()
			#delete the entitie
			userToDelete.key.delete()

			self.response.write({"success": "{} was deleted from the database".format(email)})
		else:
			# not a valid user
			self.response.write({"error": "{} is not vaild user email".format(email)})
		return

#works as of 7-22-15 @ 4:20am
#this shows the login screen if nit logged in yet and redirects to
	#account page if the user has already logged in, this was done with sessions
class home(base.BaseHandler):
		def get(self):
			#see if the session varrible exist
			if self.session.get('online'):
				#error check pprint out the session
				#self.response.write(self.session.get('online'))

				#sets the temp varible with holding, cash avalible , and gains
				showholding(self, template_var)

				self.render('account.html',template_var)
			else:
				self.render('index.html')

#works as of 7-22-15 @ 4:20am
#this erases the session that was created so that another user can login
class logout(base.BaseHandler):
		def post(self):
			#eroor check to see the session
			#\self.response.write(self.session.get('online'))
			template_var['error'] = "Come back soon to check your swing!"
			#erase the session
			self.session.pop('online')
			self.render('index.html', template_var)

#as of 7-19-2015 this works new users and old users
	#oauth not yet incorporated, but I am using webapp2 sessions rather than GAE
class login(base.BaseHandler):
	def post(self):
		#holds all my junk
		templat_var= {}

		#collects username and passwordcheck and check if new or old user
		oldOrNew = self.request.get("neworolduser")
		email= self.request.get("email")
		password = self.request.get("password")


		#error check to seee if they exist
		#self.response.write(username + "/")
		#self.response.write(password+ "/")
		#self.response.write(oldOrNew+ "/")

		if oldOrNew == "new": #create new user i n database
			#need to verify is email format
			username = self.request.get("username")
			#self.response.write(email + "/")


			#based on selection I dertemin the ammount per trade
			broker = self.request.get("broker")
			#self.response.write(broker + "/")

			#will find out the broker cost
			brokercost = None#broker holder
			if broker == "T" or broker == "O":
				brokercost= 4.95
			elif broker == "S":
				brokercost = 7.00
			elif broker =="E" or broker == "A":
				brokercost = 9.99
			elif broker =="U":
				brokercost = 8.95

			# get the actual name of the brokerage
			brokername = None#broker holder
			if broker == "T":
				brokername = "Tradeking"
			elif broker == "O":
				brokername = "Optionshouse"
			elif broker == "S":
				brokername = "Scottrade"
			elif broker =="E":
				 brokername = "Etrade"
			elif broker == "A":
				brokername ="Ameritrade"
			elif broker =="U":
				brokername ="USAA"

			#collect the start cash amount
			cash = self.request.get("cash")
			#self.response.write(cash + "/")

			#put this infromation into the database

			#get all brokerages that are in the database
			#all brokeragers name and key are placed in this dictionary
			known_brokerage = [{'name':x.firm, 'key':x.key.urlsafe()} for x in Broker.query(ancestor= ndb.Key(Broker, self.app.config.get('thebrokers'))).fetch()]

			#needed to access dictionary, starts at -1 because 1st element of dictionary is 0
			index= -1
			#are we adding a breokerage, as of right now
			addbrokerage = "yes"

			# this is used for the user to connect them to the broker that they use
			brokers_Key  =  None
			Users_Key =None

			# brokerage is either already in the databse or we jsut put it in the databse
			# this is to find out which is the case
			for x in known_brokerage:
				index += 1 #increment each time to next stock held in dictionary
				#check to see if smbol in dictiionary matches  the symbol user wants to sell
				if '{}'.format(known_brokerage[index]['name']) == brokername:
					addbrokerage = "no"
					break

			#this broker is not currently in the databse, so needs to be added
			if addbrokerage == "yes":
				#put broker in databse
				#creats key to  the brokers database
				k = ndb.Key(Broker, self.app.config.get('thebrokers'))
				brokerage = Broker(parent= k)
				#saves all things the database holds
				brokerage.fee = float(brokercost)
				brokerage.firm= brokername

				#sense we just added a broker we need to hold the key for the user
				# as KeyProperty
				brokers_Key= brokerage.put()#save to database

			else:
				brokers_Key = ndb.Key(urlsafe=known_brokerage[index]['key'])


			#at this point regardless if this is new brokkerage or not we have  key
			#we need the brokers ky to create a user so thats the next step

			# check is there is already a user with this email
			# prolly important that we acreate something later on that verifies email before saved in db
			loop = -1
			emailInUse = "no"
			establishedUsers = [{'email':x.email} for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
			for x in establishedUsers:
				loop+= 1
				if email == establishedUsers[loop]['email']:
					emailInUse = 'yes'

			# if email is already in database then user already has account
			if emailInUse == "yes":
				# since user already has email  we will just informthem
				# the this email already has account
				template_var['error'] = "there is already an account associated with {}".format(email)
				return (self.render('index.html', template_var))
			else:

				#now we can enter the New users information
				#put user in database
				#creats key to database

				k = ndb.Key(User, self.app.config.get('userholder'))
				theuser = User(parent= k)
				#saves all things the database holds
				theuser.uname = username
				theuser.email = email
				#should hash the password or something
				#once you want to do something serious with this
				theuser.password = password
				theuser.startTotal = float(cash)
				theuser.currentTotal = float(cash)
				theuser.loggedIn = True

				#put brokers key in user
				theuser.broker =  brokers_Key
				#save to database, and hold users key
				Users_Key = theuser.put()

				#this is the traders information, had to save with urlsafe because couldnt store key in dictionary
				theTrader = Trader(username, Users_Key.urlsafe(), email, float(cash), float(cash), float(brokercost))

				#error check
				#self.response.write("<h1> This is the traders information </h1>")
				#self.response.write(theTrader.uk)

				#now that we have a Trader lets save them in the session
				self.session['online'] = theTrader.__dict__

		else:
			#this is an existing user so I need to see if they are in database

			#collect all of the users
			theusers = [{'email': x.email, 'password':x.password, 'broker':x.broker, 'name': x.uname, 'key':x.key } for x in User.query(ancestor= ndb.Key(User, self.app.config.get('userholder'))).fetch()]
			#needed to access dictionary, starts at -1 because 1st element of dictionary is 0
			index= -1
			#determin if user is in database or not
			validuser= False

			for x in theusers:
				#loop through all of dictionary
				index += 1 #increment each time to next stock held in dictionary
				if theusers[index]['email'] == email and theusers[index]['password'] == password:
					validuser = True
					#error check
					#self.response.write("you tried to log in as {} with password {}".format(email, password))
					#self.response.write("here is the index {}".format(index))
					break

			if validuser  == False:
				template_var['error'] = "Not a valid username/password try again"
				return self.render('index.html', template_var)
			else:

				#get the brokers fee (in a rush but there has to be better way to obtain this information)
				hold= [{'fee':x.fee} for x in Broker.query(Broker.key == theusers[index]['broker']).fetch()]
				#put fee in varible
				fee = hold[0]['fee']
				#error check show that this is correct
				#self.response.write(fee)

				#we need more infor to create the  trader object, we need start total, currentTotal
				cash = [{'start': x.startTotal, 'current':x.currentTotal } for x in User.query(User.key == theusers[index]['key']).fetch()]
				start = cash[0]['start']
				current = cash[0]['current']

				#this is the traders information, had to save with urlsafe because couldnt store key in dictionary
				theTrader = Trader(theusers[index]['name'], theusers[index]['key'].urlsafe(), theusers[index]['email'], float(start), float(current), float(fee))

				#now that we have a Trader lets save them in the session
				self.session['online'] = theTrader.__dict__

		#sets the temp varible with holding, cash avalible , and gains
		showholding(self, template_var)
		self.render('account.html',template_var)

#as of 7-23-2015 this works
#this is the main page of the web app where all action takes place
class Account(base.BaseHandler):
		def get(self):
			if self.session.get('online'):
				#error check pprint out the session
				#self.response.write(self.session.get('online'))

				#sets the temp varible with holding, cash avalible , and gains
				showholding(self, template_var)
			else:
				templat_var= {}
				template_var['error'] = "you must be signed in to acces this page, please sign in"
				return self.render('index.html', template_var)

			self.render('account.html',template_var)

#I am not too sure this works but left here so I can fix it later
	#worked with first submission, but I have changed the database set
	#up since so Ill have to test this out
class quick(base.BaseHandler):
		def get(self):
			selllist = self.request.get("quicksellkey", allow_multiple=True)
			namelist =  self.request.get("quickstockname", allow_multiple=True)

			for x in selllist:
				stock_key = ndb.Key(urlsafe= x)
				stock = stock_key.get()
				#delete the entitie
				stock.key.delete()

			stockstring = ""
			for x in namelist:
				stockstring = stockstring + x + ", "

			template_var= {}
			template_var['info']=[{'name':x.symbol, 'description':x.description, 'amount':x.amount, 'start': x.startprice, 'current':x.currentprice, 'key':x.key.urlsafe()} for x in Purchasedstock.query(ancestor= ndb.Key(Purchasedstock, self.app.config.get('stockholder'))).fetch()]
			display = "you just sold all of your stock in: {}".format(stockstring)
			template_var['displayquick'] = display
			self.render('account.html', template_var)

#I am not too sure this works but left here so I can fix it later
	#worked with first submission, but I have changed the database set
	#up since so Ill have to test this out
class update(base.BaseHandler):
		def get(self):
			updateprice= self.request.get("updateprice")
			updateprice = float(updateprice)
			updatekey= self.request.get("updatekey")

			stock_key = ndb.Key(urlsafe= updatekey)
			stock = stock_key.get()
			stock.currentprice = updateprice
			stock.put()

			template_var= {}
			template_var['info']=[{'name':x.symbol, 'description':x.description, 'amount':x.amount, 'start': x.startprice, 'current':x.currentprice, 'key':x.key.urlsafe()} for x in Purchasedstock.query(ancestor= ndb.Key(Purchasedstock, self.app.config.get('stockholder'))).fetch()]
			self.render('account.html', template_var)

#this allows user to buy, sell positions in the market
	#as of right now you can buy new stocsk, sell all or a portion of your stock
	#ex if you had 50 shares of appl you can sell 25 and still have 25 left will cost you trades fee
	# still need  to correct buying a stock when you already own it
	#also if a stock is already in a databse, the user that created it can use it but no one else
	#I know how to fix it, but its not done as of this submission
class trade(base.BaseHandler):
		def post (self):
			template_var= {}
			thetrader= self.session.get('online')
			stockBuyer = thetrader['uk']
			buy_or_sell =  self.request.get("move")
			symbol = self.request.get("symbol")
			amount =  self.request.get("quantity")
			description= self.request.get("description")
			price = self.request.get("price")
			total = float(price) * int(amount)
			date = datetime.now().strftime("%Y-%m-%d")

			#used to hold the stock bought and the owned stock key
			astock_key= None
			ostock_Key= None

			#get the fee total, not too sure about this by any means
			fee = thetrader['brokerfee']

			#get all owned stock
			owned= [{ 'stock':x.stock.urlsafe(), 'qty':x.qty, 'owner':x.owner,
			'buyprice': x.buyprice, 'key':x.key.urlsafe() } for x in Ownedstock.query(ancestor= ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))).fetch()]


			#ILL HAVE TO EDIT BUY TO INCLUDE $5-25 DOLLAR FEE FOR TRADE
			if buy_or_sell == "bought":

				#make sure the buyer can affored this purchase
				userscash = thetrader['currentTotal']

				if userscash >= (float(amount) * float(price)) + float(fee):
					#they can make this purchase
					#lower the amount of cash they have
					userscash -= ((float(amount) * float(price)) + float(fee))
					#create object
					test=Trader(thetrader['name'],thetrader['uk'],thetrader['email'],thetrader['startTotal'], userscash, float(fee) )
					#erase the current session
					self.session.pop('online')
					#creat a new session
					self.session['online'] = test.__dict__

				else:
					#return self.response.write("trade button was hit yet not enough money")
					template_var['message'] = "You dont have enough cash for this buy"
					#still show holdings
					showholding(self, template_var)
					# thought a retuen should go here but got invlid syntax
					return self.render('account.html', template_var)


				#connect to the database on user,
				#update the total amount of current total cash associated with user
				User_key = ndb.Key(urlsafe=thetrader['uk'])

				#key = thetrader['uk'].parent()
				thisUser = User_key.get()
				#change the amount of cash in current total
				thisUser.currentTotal = userscash
				#save this change
				thisUser.put()

				#load all stocks in databse in this varible
				stocksInDatabase = [{'symbol':x.symbol,'key':x.key.urlsafe()} for x in stock.query(ancestor= ndb.Key(stock, self.app.config.get('stockholder'))).fetch()]

				#CHECK TO SEE IF THIS IS A NEW STOCK IN DATABASE
				index =-1
				isnewstock = 'yes'
				for x in stocksInDatabase:
					index += 1
					if 	symbol == stocksInDatabase[index]['symbol']:
						isnewstock = 'no'
						return

				#we need this to create an owned stock
				astock_key= None

				#this is not a stock currently being held in database
				if isnewstock == 'yes':
				 	# we are creating a new stock
					k = ndb.Key(stock, self.app.config.get('stockholder'))
					astock = stock(parent= k)
					#saves all things the database holds
					astock.symbol = symbol
					astock.description = description
					astock.currentprice = float(price)
					astock.yesterdaysclose  = float(price)
					#save to database
					astock_key = astock.put()

				else:
					#stock is already in the database so just grab the key
					astock_key = stocksInDatabase[index]['key']


				alreadyownedbyuser= "no"
				ownedstockKey=None
				#pull the key of teh stock that matches to see if this stock is already owned by user
				for x in owned:
					if x['owner'] == User_key and x['stock'] == astock_key:
						alreadyownedbyuser= "yes"
						ownedstockKey = x['stock']
						oldqty = x['qty']
						oldbuyprice = x['buyprice']
						return

				#if that is the case (it is owned by user)
				if alreadyownedbyuser == "yes":
					#get the owned stock (increase qty, changeg buy date(recent purchase), )
					thisownedstock = ownedstockKey.get()
					#increase qty
					thisownedstock.qty = int(oldqty) + int(amount)
					#changebuydate
					thisownedstock.buydate = date
					#also we will change the buy price
					#to change buy price (old qty * buyprice) + (new buy qty  * new buy price)/old qty + new qty
					#we do this to get the average buy price as oppose to storing each buy in a list
					#I might change it later but This is geared towards swing trading so Idk if its really
					#i wouldnt buy something I already own I would just ride the swing
					thisownedstock.buyprice = ((float(oldqty) * float(oldbuyprice)) + (float(amount) * float(price))) / (int(oldqty) + int(amount))
					#save the changes
					thisownedstock.put()
					template_var.update({'message':"You just {} {} additional shares of {}: {} at the price of {} for a total of ${} on {}".format(buy_or_sell, amount, symbol, description, price, total, date)})

				else:

					#if its not continue here
					#now that the stock is created or found , we can give ownership
					#to this buy
					k = ndb.Key(Ownedstock, self.app.config.get('ownedstockholder'))
					ostock = Ownedstock(parent= k)
					#saves all things the database holds
					ostock.stock = astock_key
					ostock.owner = User_key
					ostock.qty = int(amount)
					ostock.buydate = date
					ostock.buyprice = float(price)
					ostock_Key= ostock.put()

				#make sure holdings is updated
				showholding(self, template_var)
				return self.render('account.html', template_var)

			else: #they wanted to sell a stock

				#create varible that will hold the amount they make in chash with this sell
				cashdeposit= None

				#get all stocks
				stocks= [{'key':x.key.urlsafe(),'symbol': x.symbol, 'description':x.description, 'current':x.currentprice, 'yesterday': x.yesterdaysclose } for x in stock.query( ancestor= ndb.Key(stock, self.app.config.get('stockholder'))).fetch()]
				#error check
				#self.response.write("below are all stocks ")
				#self.response.write(stocks)

				holder={}
				#see if anyone else ownes the stock and if this user owns the stock
				otherOwners='no'
				stockholderkey=None
				doesUserOwnThisStock= 'no'

				for x in stocks:
					for a in owned:
						if x['key'] == a['stock'] and a['owner'].urlsafe() == stockBuyer and x['symbol'] == symbol:
							#we know we have someone trying to sell a stock in which they own
							doesUserOwnThisStock= 'yes'
							holder = {'name': x['symbol'] , 'description': x['description'] , 'amount': a['qty'] ,'start': a['buyprice'] , 'current':x['current'],
							 'yesterday': x['yesterday'], 'ownwerstockkey':a['key'], 'stockskey':x['key'], 'owner':a['owner']}
							stockholderkey = x['key']
							stockholderamount= holder['amount']
							stockholdercurrent= holder['current']
						elif x['key'] == a['stock'] and a['owner'] != stockBuyer:
							otherOwners = 'yes'

				#error check shows if this stock is owned and the holder varible
				self.response.write(holder)
				#self.response.write('<br>')
				#self.response.write(doesUserOwnThisStock)

				if doesUserOwnThisStock == 'yes':
					#if they want to sell more stocks than they have thats an issue
					if int(amount) > int(holder['amount']):
						#error check to verify they  are selling more shares than they own
						#self.response.write("{} was > than {}".format(amount, stockholderamount))
						#if thats the case we return error
						template_var['message'] = "You are trying to sell more shares than you own of a company"
						#still show holdings
						showholding(self, template_var)
						return self.render('account.html', template_var)
					elif int(amount) <= int(holder['amount']):
						# find the cash of the stocks that they wish to sell
						# put that amount in the eralier created varible
						# subtrast the fee because its a sell
						cashdeposit = (float(holder['amount']) * float(holder['current'])) - float(fee)
						#grab the current total and add cashdeposit to it
						cashdeposit += thetrader['currentTotal']
						#create new trader with that new cash amount
						test=Trader(thetrader['name'],thetrader['uk'],thetrader['email'],thetrader['startTotal'], cashdeposit, float(fee) )
						#erase the current session
						self.session.pop('online')
						#creat a new session
						self.session['online'] = test.__dict__



						#get the User and updat the money change
						User = holder['owner'].get()
						#change cash
						User.currentTotal = cashdeposit
						User.put()

						 #user is deleting all of owned stock
						if int(amount) ==  int(holder['amount']):
							# deleted this owned stock
							tobedeleted = ndb.Key(urlsafe=holder['ownwerstockkey'])
							tobedeleted= tobedeleted.get()
							tobedeleted.key.delete()
							# if no one else owns the stock we can delete it from database
							if otherOwners == 'no':
								#if soemone else owns same stock then just delete
								tobedeleted = ndb.Key(urlsafe=holder['stockskey'])
								tobedeleted= tobedeleted.get()
								tobedeleted.key.delete()
						else:
							#user is selling just a portion of his stock
								updateOwnedStock= ndb.Key(urlsafe=holder['ownwerstockkey'])
								updateOwnedStock = updateOwnedStock.get()
								updateOwnedStock.qty= int(holder['amount']) - int(amount)
								updateOwnedStock.put()

					#this will be displayed since everything went smoothly
					template_var.update({'message':"You just {} {} shares of {}: {} at the price of {} for a total of ${} on {}".format(buy_or_sell, amount, symbol, description, price, total, date)})
				else: # user is trying to sell a stock that they do not own
					#this will be displayed since everything went smoothly
					template_var.update({'message':" You cannot sell {} because you do not own it".format(symbol)})

			#make sure all holdings are updated and displayed along with cash , and total gain
			showholding(self, template_var)
			#go to the account page
			self.render('account.html', template_var)
