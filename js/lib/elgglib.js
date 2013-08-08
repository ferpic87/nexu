/**
 * @namespace Singleton object for holding the Elgg javascript library
 */
var elgg = elgg || {};

/**
 * Pointer to the global context
 *
 * @see elgg.require
 * @see elgg.provide
 */
elgg.global = this;

/**
 * Convenience reference to an empty function.
 *
 * Save memory by not generating multiple empty functions.
 */
elgg.nullFunction = function() {};

/**
 * This forces an inheriting class to implement the method or
 * it will throw an error.
 *
 * @example
 * AbstractClass.prototype.toBeImplemented = elgg.abstractMethod;
 */
elgg.abstractMethod = function() {
	throw new Error("Oops... you forgot to implement an abstract method!");
};

/**
 * Merges two or more objects together and returns the result.
 */
elgg.extend = jQuery.extend;

/**
 * Check if the value is an array.
 *
 * No sense in reinventing the wheel!
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isArray = jQuery.isArray;

/**
 * Check if the value is a function.
 *
 * No sense in reinventing the wheel!
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isFunction = jQuery.isFunction;

/**
 * Check if the value is a "plain" object (i.e., created by {} or new Object())
 *
 * No sense in reinventing the wheel!
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isPlainObject = jQuery.isPlainObject;

/**
 * Check if the value is a string
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isString = function(val) {
	return typeof val === 'string';
};

/**
 * Check if the value is a number
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isNumber = function(val) {
	return typeof val === 'number';
};

/**
 * Check if the value is an object
 *
 * @note This returns true for functions and arrays!  If you want to return true only
 * for "plain" objects (created using {} or new Object()) use elgg.isPlainObject.
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isObject = function(val) {
	return typeof val === 'object';
};

/**
 * Check if the value is undefined
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isUndefined = function(val) {
	return val === undefined;
};

/**
 * Check if the value is null
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isNull = function(val) {
	return val === null;
};

/**
 * Check if the value is either null or undefined
 *
 * @param {*} val
 *
 * @return boolean
 */
elgg.isNullOrUndefined = function(val) {
	return val == null;
};

/**
 * Throw an exception of the type doesn't match
 *
 * @todo Might be more appropriate for debug mode only?
 */
elgg.assertTypeOf = function(type, val) {
	if (typeof val !== type) {
		throw new TypeError("Expecting param of " +
							arguments.caller + "to be a(n) " + type + "." +
							"  Was actually a(n) " + typeof val + ".");
	}
};

/**
 * Throw an error if the required package isn't present
 *
 * @param {String} pkg The required package (e.g., 'elgg.package')
 */
elgg.require = function(pkg) {
	elgg.assertTypeOf('string', pkg);

	var parts = pkg.split('.'),
		cur = elgg.global,
		part, i;

	for (i = 0; i < parts.length; i += 1) {
		part = parts[i];
		cur = cur[part];
		if (elgg.isUndefined(cur)) {
			throw new Error("Missing package: " + pkg);
		}
	}
};

/**
 * Generate the skeleton for a package.
 *
 * <pre>
 * elgg.provide('elgg.package.subpackage');
 * </pre>
 *
 * is equivalent to
 *
 * <pre>
 * elgg = elgg || {};
 * elgg.package = elgg.package || {};
 * elgg.package.subpackage = elgg.package.subpackage || {};
 * </pre>
 *
 * @example elgg.provide('elgg.config.translations')
 *
 * @param {string} pkg The package name.
 */
elgg.provide = function(pkg, opt_context) {
	elgg.assertTypeOf('string', pkg);

	var parts = pkg.split('.'),
		context = opt_context || elgg.global,
		part, i;


	for (i = 0; i < parts.length; i += 1) {
		part = parts[i];
		context[part] = context[part] || {};
		context = context[part];
	}
};

/**
 * Inherit the prototype methods from one constructor into another.
 *
 * @example
 * <pre>
 * function ParentClass(a, b) { }
 *
 * ParentClass.prototype.foo = function(a) { alert(a); }
 *
 * function ChildClass(a, b, c) {
 *     //equivalent of parent::__construct(a, b); in PHP
 *     ParentClass.call(this, a, b);
 * }
 *
 * elgg.inherit(ChildClass, ParentClass);
 *
 * var child = new ChildClass('a', 'b', 'see');
 * child.foo('boo!'); // alert('boo!');
 * </pre>
 *
 * @param {Function} Child Child class constructor.
 * @param {Function} Parent Parent class constructor.
 */
elgg.inherit = function(Child, Parent) {
	Child.prototype = new Parent();
	Child.prototype.constructor = Child;
};

/**
 * Converts shorthand urls to absolute urls.
 *
 * If the url is already absolute or protocol-relative, no change is made.
 *
 * elgg.normalize_url('');                   // 'http://my.site.com/'
 * elgg.normalize_url('dashboard');          // 'http://my.site.com/dashboard'
 * elgg.normalize_url('http://google.com/'); // no change
 * elgg.normalize_url('//google.com/');      // no change
 *
 * @param {String} url The url to normalize
 * @return {String} The extended url
 * @private
 */
elgg.normalize_url = function(url) {
	url = url || '';
	elgg.assertTypeOf('string', url);

	validated = (function(url) {
		url = elgg.parse_url(url);
		if (url.scheme){
			url.scheme = url.scheme.toLowerCase();
		}
		if (url.scheme == 'http' || url.scheme == 'https') {
			if (!url.host) {
				return false;
			}
			/* hostname labels may contain only alphanumeric characters, dots and hypens. */
			if (!(new RegExp("^([a-zA-Z0-9][a-zA-Z0-9\\-\\.]*)$", "i")).test(url.host) || url.host.charAt(-1) == '.') {
				return false;
			}
		}
		/* some schemas allow the host to be empty */
		if (!url.scheme || !url.host && url.scheme != 'mailto' && url.scheme != 'news' && url.scheme != 'file') {
			return false;
		}
		return true;
	})(url);

	// all normal URLs including mailto:
	if (validated) {		
		return url;
	}

	// '//example.com' (Shortcut for protocol.)
	// '?query=test', #target
	else if ((new RegExp("^(\\#|\\?|//)", "i")).test(url)) {
		return url;
	}

	// 'javascript:'
	else if (url.indexOf('javascript:') === 0 || url.indexOf('mailto:') === 0 ) {
		return url;
	}

	// watch those double escapes in JS.

	// 'install.php', 'install.php?step=step'
	else if ((new RegExp("^[^\/]*\\.php(\\?.*)?$", "i")).test(url)) {
		return elgg.config.wwwroot + url.ltrim('/');
	}

	// 'example.com', 'example.com/subpage'
	else if ((new RegExp("^[^/]*\\.", "i")).test(url)) {
		return 'http://' + url;
	}

	// 'page/handler', 'mod/plugin/file.php'
	else {
		// trim off any leading / because the site URL is stored
		// with a trailing /
		return elgg.config.wwwroot + url.ltrim('/');
	}
};

/**
 * Displays system messages via javascript rather than php.
 *
 * @param {String} msgs The message we want to display
 * @param {Number} delay The amount of time to display the message in milliseconds. Defaults to 6 seconds.
 * @param {String} type The type of message (typically 'error' or 'message')
 * @private
 */
elgg.system_messages = function(msgs, delay, type) {
	if (elgg.isUndefined(msgs)) {
		return;
	}

	var classes = ['elgg-message'],
		messages_html = [],
		appendMessage = function(msg) {
			messages_html.push('<li class="' + classes.join(' ') + '"><p>' + msg + '</p></li>');
		},
		systemMessages = $('ul.elgg-system-messages'),
		i;

	//validate delay.  Must be a positive integer.
	delay = parseInt(delay || 6000, 10);
	if (isNaN(delay) || delay <= 0) {
		delay = 6000;
	}

	//Handle non-arrays
	if (!elgg.isArray(msgs)) {
		msgs = [msgs];
	}

	if (type === 'error') {
		classes.push('elgg-state-error');
	} else {
		classes.push('elgg-state-success');
	}

	msgs.forEach(appendMessage);

	if (type != 'error') {
		$(messages_html.join('')).appendTo(systemMessages)
			.animate({opacity: '1.0'}, delay).fadeOut('slow');
	} else {
		$(messages_html.join('')).appendTo(systemMessages);
	}
};

/**
 * Wrapper function for system_messages. Specifies "messages" as the type of message
 * @param {String} msgs  The message to display
 * @param {Number} delay How long to display the message (milliseconds)
 */
elgg.system_message = function(msgs, delay) {
	elgg.system_messages(msgs, delay, "message");
};

/**
 * Wrapper function for system_messages.  Specifies "errors" as the type of message
 * @param {String} errors The error message to display
 * @param {Number} delay  How long to dispaly the error message (milliseconds)
 */
elgg.register_error = function(errors, delay) {
	elgg.system_messages(errors, delay, "error");
};

/**
 * Meant to mimic the php forward() function by simply redirecting the
 * user to another page.
 *
 * @param {String} url The url to forward to
 */
elgg.forward = function(url) {
	location.href = elgg.normalize_url(url);
};

/**
 * Parse a URL into its parts. Mimicks http://php.net/parse_url
 *
 * @param {String} url       The URL to parse
 * @param {Int}    component A component to return
 * @param {Bool}   expand    Expand the query into an object? Else it's a string.
 *
 * @return {Object} The parsed URL
 */
elgg.parse_url = function(url, component, expand) {
	// Adapted from http://blog.stevenlevithan.com/archives/parseuri
	// which was release under the MIT
	// It was modified to fix mailto: and javascript: support.
	var
	expand = expand || false,
	component = component || false,
	
	re_str =
		// scheme (and user@ testing)
		'^(?:(?![^:@]+:[^:@/]*@)([^:/?#.]+):)?(?://)?'
		// possibly a user[:password]@
		+ '((?:(([^:@]*)(?::([^:@]*))?)?@)?'
		// host and port
		+ '([^:/?#]*)(?::(\\d*))?)'
		// path
		+ '(((/(?:[^?#](?![^?#/]*\\.[^?#/.]+(?:[?#]|$)))*/?)?([^?#/]*))'
		// query string
		+ '(?:\\?([^#]*))?'
		// fragment
		+ '(?:#(.*))?)',
	keys = {
			1: "scheme",
			4: "user",
			5: "pass",
			6: "host",
			7: "port",
			9: "path",
			12: "query",
			13: "fragment"
	},
	results = {};

	if (url.indexOf('mailto:') === 0) {
		results['scheme'] = 'mailto';
		results['path'] = url.replace('mailto:', '');
		return results;
	}

	if (url.indexOf('javascript:') === 0) {
		results['scheme'] = 'javascript';
		results['path'] = url.replace('javascript:', '');
		return results;
	}

	var re = new RegExp(re_str);
	var matches = re.exec(url);

	for (var i in keys) {
		if (matches[i]) {
			results[keys[i]] = matches[i];
		}
	}

	if (expand && typeof(results['query']) != 'undefined') {
		results['query'] = elgg.parse_str(results['query']);
	}

	if (component) {
		if (typeof(results[component]) != 'undefined') {
			return results[component];
		} else {
			return false;
		}
	}
	return results;
};

/**
 * Returns an object with key/values of the parsed query string.
 *
 * @param  {String} string The string to parse
 * @return {Object} The parsed object string
 */
elgg.parse_str = function(string) {
	var params = {};
	var result,
		key,
		value,
		re = /([^&=]+)=?([^&]*)/g;

	while (result = re.exec(string)) {
		key = decodeURIComponent(result[1])
		value = decodeURIComponent(result[2])
		params[key] = value;
	}
	
	return params;
};

/**
 * Returns a jQuery selector from a URL's fragement.  Defaults to expecting an ID.
 *
 * Examples:
 *  http://elgg.org/download.php returns ''
 *	http://elgg.org/download.php#id returns #id
 *	http://elgg.org/download.php#.class-name return .class-name
 *	http://elgg.org/download.php#a.class-name return a.class-name
 *
 * @param {String} url The URL
 * @return {String} The selector
 */
elgg.getSelectorFromUrlFragment = function(url) {
	var fragment = url.split('#')[1];

	if (fragment) {
		// this is a .class or a tag.class
		if (fragment.indexOf('.') > -1) {
			return fragment;
		}

		// this is an id
		else {
			return '#' + fragment;
		}
	}
	return '';
};

/**
 * Adds child to object[parent] array.
 *
 * @param {Object} object The object to add to
 * @param {String} parent The parent array to add to.
 * @param {Mixed}  value  The value
 */
elgg.push_to_object_array = function(object, parent, value) {
	elgg.assertTypeOf('object', object);
	elgg.assertTypeOf('string', parent);

	if (!(object[parent] instanceof Array)) {
		object[parent] = []
	}

	if ($.inArray(value, object[parent]) < 0) {
		return object[parent].push(value);
	}

	return false;
};

/**
 * Tests if object[parent] contains child
 *
 * @param {Object} object The object to add to
 * @param {String} parent The parent array to add to.
 * @param {Mixed}  value  The value
 */
elgg.is_in_object_array = function(object, parent, value) {
	elgg.assertTypeOf('object', object);
	elgg.assertTypeOf('string', parent);

	return typeof(object[parent]) != 'undefined' && $.inArray(value, object[parent]) >= 0;
};

/**
 * Triggers the init hook when the library is ready
 *
 * Current requirements:
 * - DOM is ready
 * - languages loaded
 *
 */
elgg.initWhenReady = function() {
	if (elgg.config.languageReady && elgg.config.domReady) {
		elgg.trigger_hook('init', 'system');
		elgg.trigger_hook('ready', 'system');
	}
};

var users;
var prev=-1;
var bool_tiny;

function thereIsTinyMCE() {
	return bool_tiny;
}

function showSuggestions(parent) { 
	bool_tiny = (parent != null);
	if(thereIsTinyMCE()) {
		selectElem.css("margin-top","10px");
		parent.append(selectElem);
		selectElem.css("display", "inline"); 
	}
} 

function aggiungi() { 
	if(thereIsTinyMCE()) {
		var opt = selectElem.attr("value");
		selectElem.css("display", "none");
		if(opt!="") { 
				if($.browser.mozilla == undefined)
					tinymce.activeEditor.execCommand('mceInsertContent', false, opt+" ");
		 		else {
					alert("aaaaaa");	
				}
			selectElem.remove();
		}
	
		selectElem.prop("selectedIndex", "0"); 
	}
		
}

var selectElem;
		

$(document).ready(function() {

	$.get('<?php echo $CONFIG->url;?>services/api/rest/json/?method=members', function(response) {
		var toAppend = "";
		toAppend += '<select id="sel" style="display:none;position: absolute; background-color: white; z-index:  200; font-size:small; " onChange="parent.aggiungi(this);"><option value="">Seleziona persona da menzionare</option>';
		users = response.result;
		for(i=0; i<users.length; i++)
			toAppend += '<option value=\''+users[i].id+'\'>'+users[i].name+'</option>';
		toAppend += '</select>';
		selectElem =  $(toAppend);
		$('.mention').mentionsInput({
  			onDataRequest:function (mode, query, callback) {
			    data = response.result;
		
			    data = _.filter(data, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) > -1 });
			    callback.call(this, data);
		  	}
		});
    
	});
	
	
});

function checkLength(obj) {
	if(obj.value.length==0 || obj.value.length>140) 
		obj.style.backgroundColor="red";
	else 
		obj.style.backgroundColor="white";
} 

function textCounter (textarea, status, limit) { 
	var remaining_chars = limit - $(textarea).val().length; 
	status.html(remaining_chars); 
	if (remaining_chars < 0) {
		 status.parent().addClass("thewire-characters-remaining-warning"); 
	} else { 
		status.parent().removeClass("thewire-characters-remaining-warning"); 
	} 
}


var word="";

$(document).ready(function() {                                                                                  
        $("#blog_excerpt").live('keydown', function() {                                                       
                textCounter(this, $("#thewire-characters-remaining span"), 140);                     
        });                                                                                                       
        $("#blog_excerpt").live('keyup', function() {                                                         
                textCounter(this, $("#thewire-characters-remaining span"), 140);                     
        });                                                                                                       
                                  
	    $("form").submit(function(e) {
			var oldContent = tinymce.activeEditor.getContent();
			var newContent = oldContent.replace(/<select\b[^<]*(?:<[^<]*)*<\/select>/gi, '');
			tinymce.activeEditor.setContent(newContent);
		});

		$("body").keypress(function(e) {
			var c = String.fromCharCode(e.which);
			word += c;
			
			if(word == "!sognare") {
				$.each($(".elgg-menu-item-like > a"), function(key, value) {
				  if($(value).text().indexOf("Mi piace")==0 || $(value).text()=="Like")
					$(value).text("Mi hai fatto sognare");
				  else
					$(value).text("Non voglio sognare più");
				});
			}
		});
});

function checkAbstract(vuoto, troppo_lungo) {
	var abstract = $('#blog_excerpt')[0].value;
	checkLength($('#blog_excerpt')[0]);
	if(abstract.length == 0) {
	    alert(vuoto);
	    return false;
	} else if(abstract.length > 140) {
	    alert(troppo_lungo);
	    return false;
	} 
	return true;
}

function checkFileSize() {
	if(!window.FileReader) {
		return true;
	}
	var input = $("input[type='file']")[0];
	var file = input.files[0];	
	if(file.size > 1048576) {
		alert(document.getElementById("error_file_size").innerHTML);
		return false;
	}
	return true;
}

function checkDashboard() {
	if(document.URL.indexOf("dashboard")!=-1) {
		$(".extended_tinymce-toggle-editor").trigger('click');
	}
}

function controllaRegistrazione() {
	var filledAll = true;
	$(".mandatory input").each(function(index) {
		var thisValue = $(this)[0].value;
		resetErrorMessage($(this));
		if(thisValue == "") {
			filledAll = false;
			$(this).css("background-color","red");
		} else {
			$(this).css("background-color","white");
			if($(this)[0].name=="email") {
				if(!isValidEmailAddress(thisValue)) {
					$(this).css("background-color","orange");
					showErrorMessage("Non hai inserito un'email valida!",$(this));
					filledAll = false;
				}
			} else {
				if(containsIllegalChar(thisValue)) {
			    		showErrorMessage("Hai inserito caratteri non ammissibili!",$(this));
	  				$(this).css("background-color", "orange");
					filledAll = false;
				}
			}

			if($(this)[0].name=="password")
				if(thisValue.length<6) {
					showErrorMessage("La password deve essere almeno di 6 caratteri!",$(this));
					$(this).css("background-color","orange");
					filledAll = false;
				}

			if($(this)[0].name=="username")
				if(containsASpace(thisValue)) {
					showErrorMessage("Il nome utente non pu&ograve contenere spazi!",$(this));
					$(this).css("background-color","orange");
					filledAll = false;
					
				}

			/*if($(this)[0].className=="elgg-input-tags")
				if(containsASpace(thisValue) && thisValue.indexOf(",")==-1) {
					showErrorMessage("Usa la virgola come separatore!",$(this));
					$(this).css("background-color","orange");
					filledAll = false;

				}	*/
		}

							
	});
	
	if(filledAll && $("#register-password")[0].value != $("#register-password2")[0].value)	{
		showErrorMessage("Le due password non corrispondono!",$("#register-password"));
		$("#register-password").css("background-color","orange");
		$("#register-password2").css("background-color","orange");
		filledAll = false;
	}
		
	return filledAll;
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
}

function containsIllegalChar(text) {
    var pattern = new RegExp(/[@°#$£%\^&\*(){}\\\[\]<>?"'!\/|\+=]/i);
    
    return pattern.test(text);
}

function containsASpace(text) {
    return text.indexOf(" ")!=-1;
}

function showErrorMessage(message, jQElem) {
      var offset = jQElem.offset();
      if($("#error_"+jQElem[0].name).length==0)
	   $('body').append("<div id='error_"+jQElem[0].name+"' style='position:absolute;left:"+(offset.left+203)+"px;top:"+(offset.top-16)+"px;border: 1px solid red;padding-left: 5px;padding-right: 5px;'>"+message+"</div>");
}

function resetErrorMessage(jQElem) {
	var id = "#error_"+jQElem[0].name;
	$(id).remove();
}


