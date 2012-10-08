var RedRokkLogin;
(function(j){
	var api;
	
/**
 * Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 * 
 * http://ejohn.org/blog/simple-javascript-inheritance/
 * Inspired by base2 and Prototype
 * 
 */
(function(){function h(b){function a(){for(var b=[].slice.call(arguments,0),a=0,g,a=0;c.before[a];a+=1)g=c.before[a],g.apply(this,b);for(var f=d.apply(this,arguments),a=0;c.after[a];a++)g=c.after[a],g.apply(this,b);return f}var d=b,c={before:[],after:[]};a.addHook=function(a,b){if(c[a]instanceof Array)c[a].push(b);else throw function(){var b=Error("Invalid hook type");b.expected=Object.keys(c);b.got=a;return b}();};return a}var f=!1,i=/xyz/.test(function(){xyz})?/\b_super\b/:/.*/;this.Class=function(){this.loadjs=
function(b,a){var d=document.createElement("script");d.type="text/javascript";d.readyState?d.onreadystatechange=function(){if("loaded"==d.readyState||"complete"==d.readyState)d.onreadystatechange=null,a&&a()}:d.onload=function(){a&&a()};d.src=b;document.getElementsByTagName("head")[0].appendChild(d)};this.setOptions=function(b){return this.bind(this.defaults,b)};this.bind=function(b,a){var d={},c;for(c in b)d[c]=b[c];for(c in a)d[c]=a[c];return d}};Class.extend=function(b){function a(){!f&&this.init&&
this.init.apply(this,arguments)}var d=this.prototype;f=!0;var c=new this;f=!1;for(var e in b)c[e]="function"==typeof b[e]&&"function"==typeof d[e]&&i.test(b[e])?function(a,b){return function(){var c=this._super;this._super=d[a];var e=b.apply(this,arguments);this._super=c;return e}}(e,b[e]):"function"==typeof b[e]?h(b[e]):b[e];a.prototype=c;a.constructor=a;a.extend=arguments.callee;return a}})();

/**
 * Red Rokk Login Popup
 * BY Jonathon http://redrokk.com
 * MIT Licensed.
 * 
 */
	RedRokkLogin = Class.extend({
	defaults : {
		container 	: null,
		redirect_to : '/',
		ajaxurl		: '/wp-admin/admin-ajax.php',
		linkClass	: '.page-item-login, .red_login_popup',
		dialog		: {
			dialogClass   	: 'red_white_popup',	
			minWidth		: '520',
			minheight		: '500',
			modal		 	: true,
			autoOpen	  	: false, 
			closeOnEscape 	: true,	  
			draggable		: true,
			position		: 'top',
			buttons	   	: {
				Close : function() {
					j(this).dialog('close');
					show_area('#prompt_sign_in_main');
				}
			},
			open : function(evt, ui) {
				if (j('body').hasClass('loggedin')) return false;

				if (evt && evt.stopPropagation)
					evt.stopPropagation();
				
				var id = j(this).attr('id');
				j(this).removeClass('ui-dialog-content');
				j(this).removeClass('ui-widget-content');
				
				j(this).parent().removeClass('ui-dialog');
				j(this).parent().removeClass('ui-widget'); 
				j(this).parent().removeClass('ui-widget-content');
				j(this).parent().removeClass('ui-corner-all');
				j(this).parent().removeClass('ui-draggable');
				j(this).parent().removeClass('ui-resizable');
				j(this).prev().removeClass('ui-widget-header');
				
				j('#'+id+' .ui-icon-closethick').removeClass('ui-icon-closethick');
				j('#'+id+' .ui-icon').removeClass('ui-icon');
			}
		}
	},
	
	// Initializing
	init: function(options) {
		options.dialog = j.extend({}, this.defaults.dialog, options.dialog);
		this.o = this.setOptions(options);
		api = this;
		
		this.addListeners();
	},

	addListeners: function() {
		this.o.container.dialog( this.o.dialog );
		j(this.o.linkClass).click(this.open.bind(this));
		
		j('#loginform').submit(this.loginSubmit.bind(this));
		j('#registerform').submit(this.registerSubmit.bind(this));
		j('#lostpasswordform').submit(this.lostpasswordSubmit.bind(this));
	},
	
	// open the popup form
	open : function(evt) {
		if (j('body').hasClass('loggedin')) return false;
		
		if (evt && evt.stopPropagation)
			evt.stopPropagation();
		
		window.RedRokkPopupClicked = j(evt.currentTarget);
		
		this.o.container.dialog('open');
		return false;
	},
	
	close : function() {
		this.o.container.dialog('close');
	},
	
	// ajax registration submission
	registerSubmit : function(evt) {
		evt.stopPropagation();
		j('#registerform .signuperror').hide();
		j('#registerloader').show();
		
		j.ajax({
			url : this.o.ajaxurl,
			dataType : 'json',
			type :'post',
			cache : false,
			data : {
				user_login : j('#registerform #user_login').val(),
				user_email : j('#registerform #user_email').val(),
				redirect_to : false,
				action : 'registration'
			},
			success : function(r) {
				j('#registerloader').hide();
				
				if (r.userid) {
					show_area('#prompt_registration_confirm');
				}
				else {
					j('#registerform .signuperror').html( r.error ).show();
				}
			},
			error : function(r) {
				console.log('error', r);
			}
		});
		return false;
	},
	
	// login
	loginSubmit : function(evt) {
		evt.stopPropagation();
		j('#loginform .signuperror').hide();
		j('#loginformloader').show();
		
		j.ajax({
			url : this.o.ajaxurl,
			dataType : 'json',
			type :'post',
			cache : false,
			data : {
				log : j('#loginform #user_login').val(),
				pwd : j('#loginform #user_pass').val(),
				redirect_to : false,
				action : 'login_ajax'
			},
			success : function(r) {
				//console.log(r);
				j('#loginformloader').hide();

				if (r.action && r.action == 'success') {
					if (r.javascript)eval(r.javascript);
					api.onLoggin.apply( api, r );
					
					j('body').addClass('loggedin');
				}
				else if (r.error) {
					j('#loginform .signuperror').html( r.error );
					if (j('#loginform .signuperror a').text() == 'Lost your password') {
						j('#loginform .signuperror a').click(function(evt){
							evt.stopPropagation();
							show_area('#prompt_sign_in_reset');
							return false;
						});
					}
					j('#loginform .signuperror').show();
					
				} else {
					j('#loginform .signuperror').html( 'Bad response from the server.' ).show();
				}
			},
			error : function(r) {
				console.log('error',r);
			}
		});
		return false;
	},
	
	// fired on login
	onLoggin : function(r) {
		if (r && r.redirect) window.location = r.redirect;
	},
	
	// ajax forgot password submission
	lostpasswordSubmit : function(evt) {
		evt.stopPropagation();
		j('#lostpasswordform .signuperror').hide();
		j('#lostpasswordloader').show();
		
		j.ajax({
			url : this.o.ajaxurl,
			dataType : 'json',
			type :'post',
			cache : false,
			data : {
				user_login : j('#lostpasswordform #user_login').val(),
				redirect_to : false,
				action : 'lostpassword_ajax'
			},
			success : function(r) {
				j('#lostpasswordloader').hide();
				
				if (r.action && r.action == 'success') {
					show_area('#prompt_registration_confirm');
				}
				else if (r.error) {
					j('#lostpasswordform .signuperror').html( r.error ).show();
					
				} else {
					j('#lostpasswordform .signuperror').html( 'Bad response from the server.' ).show();
				}
			},
			error : function(r) {
				console.log('error',r);
			}
		});
		return false;
	}
	});
	
	// Making it a jQuery plugin
	j.fn.RedRokkLogin = function(o){
		// initializing
		var args = arguments;
		var o = o || {'container':''};
		
		return this.each(function(){
			// load the saved object
			var api = j.data(this, 'RedRokkLogin');
			
			// create and save the object if it does not exist
			if (!api) {
				o.container = j(this);
				api = new RedRokkLogin(o);
				j.data(this, 'RedRokkLogin', api);
			}
			
			// if this is a method call on an existing object, apply it
			if (typeof api[o] == 'function')
				return api[o].apply( api, args );
		});
	};

})(jQuery);

/**
 * Function makes it possible to toggle between login dialog screens
 * 
 * @param id
 */
function show_area(id) {
	jQuery('.loginpages').each(function(k,el){
		if (jQuery(el).css('display')=='block' && id != '#'+jQuery(el).attr('id')) {
			jQuery(el).fadeOut();
		}
	});
	jQuery(id).fadeIn();
}