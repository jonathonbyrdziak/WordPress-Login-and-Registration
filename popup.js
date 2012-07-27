/**
 * Simple JavaScript Inheritance
 * By John Resig http://ejohn.org/
 * MIT Licensed.
 * 
 * Extended by Jonathon Byrd to include function hooks
 * http://redrokk.com
 * 
 */
(function(){
  var initializing = false, fnTest = /xyz/.test(function(){xyz;}) ? /\b_super\b/ : /.*/;
  function hookable(fn) {
      var ifn = fn,
          hooks = {
              before : [],
              after : []
          };

      function hookableFunction() {
          var args = [].slice.call(arguments, 0),
              i = 0,
              fn;
          for (i = 0; !!hooks.before[i]; i += 1) {
              fn = hooks.before[i];
              fn.apply(this, args);
          }
          var r = ifn.apply(this, arguments);
          for (i = 0; !!hooks.after[i]; i++) {
              fn = hooks.after[i];
              fn.apply(this, args);
          }
          return r;
      }
      
      hookableFunction.addHook = function (type, fn) {
          if (hooks[type] instanceof Array) {
              hooks[type].push(fn);
          } else {
              throw (function () {
                  var e = new Error("Invalid hook type");
                  e.expected = Object.keys(hooks);
                  e.got = type;
                  return e;
              }());
          }
      };
      
      return hookableFunction;
  }
  
  // The base Class implementation (does nothing)
  this.Class = function(){};

  // Create a new Class that inherits from this class
  Class.extend = function(prop) {
    var _super = this.prototype;
    
    // Instantiate a base class (but only create the instance,
    // don't run the init constructor)
    initializing = true;
    var prototype = new this();
    initializing = false;
    
    // Copy the properties over onto the new prototype
    for (var name in prop) {
      // Check if we're overwriting an existing function
      prototype[name] = 
    	typeof prop[name] == "function" && typeof _super[name] == "function" && fnTest.test(prop[name])
        ? (function(name, fn){
	          return function() {
	            var tmp = this._super;
	            
	            // Add a new ._super() method that is the same method
	            // but on the super-class
	            this._super = _super[name];
	            
	            // The method only need to be bound temporarily, so we
	            // remove it when we're done executing
	            var ret = fn.apply(this, arguments);
	            this._super = tmp;
	            
	            return ret;
	          };
          })(name, prop[name])
        : (typeof prop[name] == 'function' ?hookable(prop[name]) :prop[name]);
    }
    
    // The dummy class constructor
    function Class() {
      // All construction is actually done in the init method
      if ( !initializing && this.init )
        this.init.apply(this, arguments);
    }
    
    // Populate our constructed prototype object
    Class.prototype = prototype;
    
    // Enforce the constructor to be what we expect
    Class.constructor = Class;

    // And make this class extendable
    Class.extend = arguments.callee;
    
    return Class;
  };
})();

/**
 * Simple Login Popup
 * By Jonathon Byrd http://redrokk.com/
 * MIT Licensed.
 * 
 * http://redrokk.com/
 * 
 */
var RedPopupLogin;
var RedPopupLoginClass;
(function(j) {
	RedPopupLoginClass = Class.extend({
		defaults : {
			loginArea 	: '#red_login_popup',
			linkClass	: '.page-item-login',
			dialog		: {
				'dialogClass'   : 'red_login_popup',		   
				'modal'		 	: true,
				'autoOpen'	  	: false, 
				'closeOnEscape' : true,	  
				'buttons'	   	: {
					"Close" : function() {
						j(this).dialog('close');
					}
				},
				'open' : function(e, ui) {
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
		init: function(options)
		{
			this.o = j.extend({}, this.defaults, options);
			this.i = j( this.o.loginArea );
			this.i.dialog( this.o.dialog );
			
			this.addListeners();
		},
		
		addListeners : function()
		{
			j(this.o.linkClass).click(this.open.bind(this));
		},
		
		open: function(e)
		{
			if (e)
				if (e.stopPropagation)
					e.stopPropagation();
			this.i.dialog('open');
			return false;
		}
	});
})(jQuery);

//initializing this class
RedPopupLogin = new RedPopupLoginClass();
