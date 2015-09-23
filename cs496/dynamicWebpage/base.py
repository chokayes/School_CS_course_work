import webapp2
import os
import jinja2

#this is for sessions
from webapp2_extras import sessions

#https://snipt.net/raw/169cc0d798f763eae3d947a81cd65358/?nice
#this is what I used to help me establish sessions
# im

#doc on Jinja @ http://jinja.pocoo.org/docs/dev/api/
class BaseHandler(webapp2.RequestHandler):


	@webapp2.cached_property
	def session_store(self):
		return sessions.get_store(request = self.request)

	@webapp2.cached_property
	def session(self):
        # Returns a session using the default cookie key.
        # Here is where the problem was - the `return` was missing
		return self.session_store.get_session()

	def dispatch(self):
		try:
			super(BaseHandler, self).dispatch()
		finally:
            # Save all sessions.
			self.session_store.save_sessions(self.response)

	@webapp2.cached_property
	def jinja2(self):
		return jinja2.Environment(
		loader= jinja2.FileSystemLoader(os.path.dirname(__file__) + '/templates'),
		extensions=['jinja2.ext.autoescape'],
		autoescape=True
		)

	def render(self, template, template_variables={}):
		#self.response.headers['Content-Type'] = 'test/plain'
		template = self.jinja2.get_template(template)
		self.response.write(template.render(template_variables))


#class PostHandler(webapp2.RequestHandler):
 #   def dispatch(self):
  #  	self.response.headers['Access-Control-Allow-Origin'] = 'http://web.engr.oregonstate.edu'
   # 	self.response.headers['Access-Control-Allow-Headers'] = 'application/json'
    #	self.response.headers['Access-Control-Allow-Methods'] = 'POST, OPTIONS'
    #	self.response.headers['Content-Type'] = '*'
    #	super(PostHandler, self).dispatch()


