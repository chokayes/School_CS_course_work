import webapp2

config = { 'userholder': 'User', 'thebrokers': 'Broker',
 			'stockholder':'stockinfo',
			'ownedstockholder': 'ownedstocks'}

config['webapp2_extras.sessions'] = {
    'secret_key': 'my-super-secret-key',
}


#once I want to implement sessions: http://www.essentialtech.co.nz/content/using_session_google_app_engine_and_python_27

application = webapp2.WSGIApplication([
	('/account', 'account.Account'),
	('/', 'account.home'),
	('/login', 'account.login'),
    ('/logout', 'account.logout'),
	('/trade', 'account.trade'),
	('/quick', 'account.quick'),
    ('/api', 'account.api'),
    ('/homework', 'account.homework'), 
	('/update', 'account.update'),
	], debug = True,  config= config)
