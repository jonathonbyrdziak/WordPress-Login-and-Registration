<script type="application/javascript">
var FacebookConnect;
;;(function(j){
	var fbapi;
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
 * Facebook Connect from Red Rokk
 * By Jonathon http://redrokk.com
 * MIT LICENSED
 *
 */
FacebookConnect = Class.extend({
	defaults : {
		container	: null,
		appID		: 0,
		scope		: '<?php echo $this->scope ?>'
	},
	
	// Initializing
	init: function(options)
	{
		fbapi = this;
		this.o = this.setOptions(options);
		
		this.addListeners();
	},
	
	addListeners : function()
	{
		fbapi.o.container.bind('click', this.onclick.bind(this));
	},
	
	onclick : function( evt ) 
	{
		evt.stopPropagation();
		FB.login(function(response) {
			if (response.session) {
				if (response.perms) {
					// user is logged in and granted some permissions.
					// perms is a comma separated list of granted permissions
					fbapi.ajax_login();
				} else {
					// user is logged in, but did not grant any permissions
					fbapi.ajax_login();
				}
			} else {
				// user is not logged in
				fbapi.ajax_login();
				//j('#facebookloader').css('display', 'none');
			}
		}, {scope:fbapi.o.scope});
		return false;
	},

	ajax_login : function()
	{
		j('#facebookloader').css('display', 'inline');
		
		j.ajax({
			url : '<?php echo admin_url('/admin-ajax.php') ?>',
			dataType : 'html',
			cache : false,
			data : {
				action 		: 'redrokk_facebook_connect'
			},
			success : this.onLoginSuccess.bind(this)
		});
	},
	
	onLoginSuccess : function( results ) 
	{
		this.o.container.append(results);
		j('body').addClass('loggedin');
	}
});

// Creating the jQuery plugin
j.fn.FacebookConnect = function(o){
	// initializing
	var args = arguments;
	var o = o || {'container':''};
	
	return this.each(function(){
		// load the saved object
		var api = j.data(this, 'FacebookConnect');
		
		// create and save the object if it does not exist
		if (!api) {
			o.container = j(this);
			api = new FacebookConnect(o);
			j.data(this, 'FacebookConnect', api);
		}
		
		// if this is a method call on an existing object, apply it
		if (typeof api[o] == 'function')
			api[o].apply( api, args );
	});
};
})(jQuery);

// Loading Facebook API
window.fbAsyncInit = function() {
	FB.init({
		"appId"		: "<?php echo $this->_appid ?>",
		"channelUrl": "<?php echo add_query_arg('facebook_channel', '', site_url()) ?>",
		"status"	: true,
		"cookie"	: true,
		"xfbml"		: true,
		"oauth"		: true
	});
};

// Loading the Facebook sdk library
(function(d){
	var js, id = 'facebook-jssdk'; if (d.getElementById(id)) {return;}
	js = d.createElement('script'); js.id = id; js.async = true;
	js.src = "//connect.facebook.net/en_US/all.js";
	d.getElementsByTagName('head')[0].appendChild(js);
}(document));
</script>