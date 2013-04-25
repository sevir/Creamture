/**
 * Channels permits the communication between windows, frames and local
 * pages related in DOM, now between different domains windows too (using flash connector)

The MIT License

Copyright (c) 2011 DIGIO S.L.N.E.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
 */

/*
 * Use:
 *
 *
 * $.channels.bind('channel', 'method' or ['method1',..], function(window, params){});
 *
 * The elements listening one channel event/method has to bind to this methods with the
 * handle function, this handler receives as a first param the local window, and the second
 * param, the data passes to each method.
 *
 * Channel and method has to be opened and published previously or an error is triggered
 *
 * $.channels.unbind('channel','method');
 *
 * The element stops listening to the method/event in the channel
 *
 * $.channels.trigger('channel', 'method', param_object);
 *
 * The provider trigger a method in one channel, passing param_object as params for the
 * handlers
 *
 * NOTE: the functions can be concatenate,
 * ex: $.channels.open('ch').publish('ch','test').bind('ch','test', function(){ alert('hello'); });
 */

(function($){
  	$.channels =  function(){
	 	var openedChannels = new Array();
	 	var publishedMethods = {};
	 	var listeners = {};
	 	var manager;
	 	var self;
	 	var useEvents = true;

	 	var _findManager = function(){
	 		try{
	 			if( top.window.opener && !top.window.opener.closed){
					manager = top.window.opener;
				}else{
					manager = top;
				}
				self = manager.$.channels;

				return manager;
	 		}catch(e){
	 			return false;
	 		}
	 	}

	 	Array.prototype.unique = function() {
		    var a = this.concat();
		    for(var i=0; i<a.length; ++i) {
		        for(var j=i+1; j<a.length; ++j) {
		            if(a[i] === a[j])
		                a.splice(j, 1);
		        }
		    }
		    return a;
		};

		// implement JSON.stringify serialization
		var JSON = JSON || {};

		JSON.stringify = JSON.stringify || function (obj) {
			var t = typeof (obj);
			if (t != "object" || obj === null) {
				if (t == "string") obj = '"'+obj+'"';
				return String(obj);
			}
			else {
				var n, v, json = [], arr = (obj && obj.constructor == Array);
				for (n in obj) {
					v = obj[n]; t = typeof(v);
					if (t == "string") v = '"'+v+'"';
					else if (t == "object" && v !== null) v = JSON.stringify(v);
					json.push((arr ? "" : '"' + n + '":') + String(v));
				}
				return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
			}
		};

		/* custom errors */
	 	var err = function(msg,p){
			var e = new Error(msg);
			if (p)
				$.extend(e,p);
			if (!e.message)
				err.message = msg;
			e.name = 'Channels Error';
			return e;
	 	}
	 	err.prototype = Error.prototype;

		/* custom log */
	 	var l = function(o,t){
	 		t = (t)?t:'log';
			if (console && console[t]) console[t](o);
			else alert(JSON.stringify(o));
	 	}

	 	var le = function (o){
	 		l(o, 'error');
	 	}

	 	var li = function (o){
	 		l(o, 'info');
	 	}

	 	return {
			_open: function(name, w){
				if (! w) w = window;

				_findManager();
				if(manager != window) return self.open(name,w);

				openedChannels = openedChannels.concat(new Array(name)).unique();
				if(w == window)
					return this;
				else
					return w.$.channels;

			},
			_publish: function(channel, method, w){
				if (! w) w = window;
				
				if(manager != window) return self.publish(channel, method, w);

				try{
					if (openedChannels.indexOf(channel)<0)
						throw err('No channel named "'+channel+'" is opened');

					if (typeof (method) != "string"){
						for (var i=0; i<method.length; i++){
							self._publish(channel, method[i]);
						}
					}else{
						if (publishedMethods[channel] && publishedMethods[channel][method] )
							throw err('Channel "'+channel+'" has a method "'+method+'" previous');

						if (!publishedMethods[channel]){
							publishedMethods[channel] = {};
							publishedMethods[channel][method] = [];
						}else{
							if(!publishedMethods[channel][method])
								publishedMethods[channel][method] = [];
						}
					}

					if(w == window)
						return this;
					else
						return w.$.channels;
				}catch(e){
					le(e.message);
					return;
				}				
			},
			bind: function(channel, method, func, w){
				if (! w) w = window;
				_findManager();

				if(manager != window) return self.bind(channel, method, func, w);
				w.$.channels._addlistener(channel, method, func);

				try{
					if (openedChannels.indexOf(channel)<0)
							self._open(channel, w);

					if (typeof (method) != "string"){
						for (var i=0; i<method.length; i++){
							self.bind(channel, method[i], func, w);
						}
					}else{
						if ( ! publishedMethods[channel] || ! publishedMethods[channel][method])
							self._publish(channel, method, w);

						publishedMethods[channel][method].push(w);
						publishedMethods[channel][method] = publishedMethods[channel][method].unique();
					}

					if(w == window)
						return this;
					else
						return w.$.channels;
				}catch(e){
					le(e.message);
				}				
			},
			unbind: function(channel, method, w){
				if (! w) w = window;
				_findManager();

				if(w == window){
					if(!useEvents){
						if (listeners[channel] & listeners[channel][method])
							listeners[channel][method] = [];
					}else
						w.$(w).unbind(channel+'_ch_'+method);
				}

				if(manager != window) return self.unbind(channel, method, w);
				try{
					if (openedChannels.indexOf(channel)<0)
						throw err('No channel named "'+channel+'" is opened');
					if (publishedMethods[channel] && publishedMethods[channel][method])
						publishedMethods[channel][method].splice(publishedMethods[channel][method].indexOf(w),1);

					if(w == window)
						return this;
					else
						return w.$.channels;
				}catch(e){
					le(e.message);
				}

			},
			trigger: function(channel, method, params, w){
				if (! w) w = window;
				_findManager();

				if(manager != window) return self.trigger(channel, method, params, w);

				if (publishedMethods[channel] && publishedMethods[channel][method]){
					for (var i=0; i< publishedMethods[channel][method].length; i++){
						if(useEvents)
							publishedMethods[channel][method][i].$(publishedMethods[channel][method][i]).trigger(channel+'_ch_'+method, params);
						else
							publishedMethods[channel][method][i].$.channels._execute(channel, method, params);
					}
				}
				if(w == window)
					return this;
				else
					return w.$.channels;

			},
			_addlistener: function(channel, method, func){
				if (typeof (method) != "string"){
					for (var i=0; i<method.length; i++){
						return this._addlistener(channel, method[i], func);
					}
				}else{
					if (useEvents){
						$(window).bind(channel+'_ch_'+method, func);
					}else{
						if (listeners[channel]){
							if (! listeners[channel][method]){
								listeners[channel][method] = new Array();
							}
						}else{
							listeners[channel] = {};
							listeners[channel][method] = new Array();
						}
						listeners[channel][method].push(func);
						listeners[channel][method] = listeners[channel][method].unique();
					}
				}
				return this;
			},
			_execute: function(channel, method, params){
				try{
					if (listeners[channel] && listeners[channel][method])
					for (var i=0; i<listeners[channel][method].length; i++){
						if (! listeners[channel][method][i](window, params) ) break;
					}

					return this;
				}catch(e){
					le(e.message);
				}
			}
	 	}
	 }();
})(jQuery);
