/* ------------------------------------------------------------------------------------------------
	PARSERS FUNCTION
 ----------------------------------------------------------------------------------------------- */
 
/**
 * Return true if v in {true, "true", "1", 1}
 */
function parseBoolean(v)
{
	return (v == 1 || v == "1" || v == "true" || v == true);
}

/**
 * Assume color is correct, convert #XYZ to #XXYYZZ 
 */
function parseColor(color)
{
	if (color && color.length == 4)
	{
		var a = color.substr(1,1);
		var b = color.substr(2,1);
		var c = color.substr(3,1);
		
		color = "#" + a + a + b + b + c + c;
	}
	return color;
}

 
/* ------------------------------------------------------------------------------------------------
	MISC
 ----------------------------------------------------------------------------------------------- */
 
 function convertToMilliSeconds(v)
{
	var result = v * 1000;
	
	return Math.floor(result);
}

function convertValueToPercentage(value, max)
{
	if (!value && !max) return 0;
	return Math.round(value * 100 / max);
}

function convertPercentageToValue(percent, max)
{
	if (!percent && !max) return 0;
	return Math.round(percent * max / 100);
}

 
/* ------------------------------------------------------------------------------------------------
	
 ----------------------------------------------------------------------------------------------- */

/**
 * Set an element attribute name & value
 */
function setAttribute(elt, attributeName, attributeValue)
{
	elt.setAttribute(attributeName, attributeValue);
}

/**
 * Add a css class to an element
 */
function addElementClassName(element, name)
{
	element.className += " " + name;
}

/**
 * Removes specified class from an element
 */
function removeElementClassName(element, name)
{
	var str = element.className;
	if (str)
	{
		var newClassName = "";
		var ar = str.split(" ");
		
		for (var n in ar)
		{
			if (ar[n] != name)
				newClassName += ar[n] + " ";
		}
		
		element.className = newClassName;
	}
}




function getPixelsAsNumber(div, property)
{
	var rawValue = div.style[property];
	
	if (rawValue) return parseInt(rawValue.substr(0, rawValue.indexOf("px")));
	else return null;
}

/**
 * Retreive the element with specified ID. If multiple ids set, returns an array containing those.
 * Usage : $("div_id") or $("div_id1", "div_id2", ...)
*/
function $()
{
   var elements = [];
   
   for (var i = 0, len = arguments.length; i < len; ++i)
   {
		var element = arguments[i];
		
		if(typeof element === 'string')
			element = document.getElementById(element);
			
		if(arguments.length === 1)
			return element;
        
		elements.push(element);
    }
	
	return elements;
}


/* 
	Method designed to fix difference of behaviour between IE & Firefox for method getElementsByTagName
	Use tag to specify if element is DIV, TR, SPAN, ... 
*/
function $$(tag, name)
{
	var elem = document.getElementsByTagName(tag);
	var arr = new Array();
	
	for (i = 0,iarr = 0; i < elem.length; i++)
	{
		att = elem[i].name;
		if (att == name)
		{
			arr[iarr] = elem[i];
			iarr++;
		}
	}
	return arr;
}


/* ------------------------------------------------------------------------------------------------
	EVENTS RELATED FUNCTIONS
 ----------------------------------------------------------------------------------------------- */

function getEvent(e)
{
	var ev = e || window.event;
	if(!ev)
	{
		var c = getEvent.caller;
		while(c)
		{
			ev = c.arguments[0];
			if(ev && Event == ev.constructor)
			{
				break;
			}
			c = c.caller;
		 }
    }
	return ev;
}

function stopEvent(ev)
{
	preventDefault(ev);
	stopPropagation(ev);
}

function stopPropagation(ev)
{
	if (!ev) return;
	
	if(ev.stopPropagation)
	{
		ev.stopPropagation();
	}
	else
	{
		ev.cancelBubble = true;
	}
}
   
function preventDefault(ev)
{
	if (!ev) return;
	
	if(ev.preventDefault)
	{
		ev.preventDefault();
	}
	else 
	{
		ev.returnValue = false;
	}
}
   
function getEventSrc(e)
{
   if (!e)e = window.event;
   if (e.originalTarget) return e.originalTarget;
   else if(e.srcElement) return e.srcElement;
}

function addLoadEvent(func)
{
	var oldonload = window.onload;
	if (typeof window.onload != 'function')
	{
		window.onload = func;
	}
	else
	{
		window.onload = function()
		{
			oldonload();
			func();
		}
	}
}

var EventCache = 
{
	listEvents : [], 
	add : function(node, sEventName, fHandler, bCapture, wrappedFn)
	{
		EventCache.listEvents.push(arguments);
	},
	simpleRemove : function(element, type, listener, useCapture)
	{
		if(element.removeEventListener)
		{
			element.removeEventListener(type, listener, useCapture);
		};
		if(type.substring(0, 2) != "on")
		{
			type = "on" + type;
		};
		if(element.detachEvent)
		{
			element.detachEvent(type, listener);
		};
	},
	flush : function()
	{
		var i, item;
		listEvents = EventCache.listEvents;
		for (i = listEvents.length - 1; i >= 0; i = i - 1)
		{
			item = listEvents[i];
			EventCache.simpleRemove(item[0], item[1], item[4], item[3]);
			item[0][item[1]] = null;
			EventCache.listEvents.splice(i, 1);
		};
    },
	removeListener : function(node, sEventName, fHandler, bCapture)
	{
		var i, item;
		var b = false;
		for(i = EventCache.listEvents.length - 1; i >= 0; i = i - 1)
		{
			item = EventCache.listEvents[i];
			if(item[0] == node && item[1] == sEventName && item[2] == fHandler)
			{
				EventCache.simpleRemove(item[0], item[1], item[4], item[3]);
				item[0][item[1]] = null;
				EventCache.listEvents.splice(i, 1);
				b = true;
				break;
			}
		};
		return b;
	}
   }
addEvent(window, 'unload', EventCache.flush, false);
 
function addEvent(elm, evType, fn, useCapture)
{
   var wrappedFn = function(e){ return fn.call(elm, getEvent(e));};
   
   if (elm.addEventListener)
   {
		elm.addEventListener(evType, wrappedFn, useCapture);
		var result = true;
	}
	else if (elm.attachEvent)
	{
		var result = elm.attachEvent('on' + evType, wrappedFn);
    }
	else
	{
		elm['on' + evType] = fn;
		var result = true;
	}
	
	if(evType != 'unload')
	{
		EventCache.add(elm, evType, fn, useCapture, wrappedFn);
	}

	return result;
}

function fireEvent(elt, event)
{
	if (document.createEvent)
	{
		var evObj = document.createEvent('MouseEvents');
		/*
		evObj.initMouseEvent(
			event,    	// le type d'événement souris
			true,       // est-ce que l'événement doit se propager (bubbling) ?
			true,       // est-ce que le défaut pour cet événement peut être annulé ?
			window,     // l' 'AbstractView' pour cet événement
			1,          // details -- Pour les événements click, le nombre de clicks
			1,          // screenX
			1,          // screenY
			1,          // clientX
			1,          // clientY
			false,      // est-ce que la touche Ctrl est pressée ?
			false,      // est-ce que la touche Alt est pressée ?
			false,      // est-ce que la touche Shift est pressée ?
			false,      // est-ce que la touche Meta est pressée ?
			0,          // quel est le bouton pressé
			elt     	// l'élément source de cet événement
		);
		*/
		evObj.initMouseEvent( event, true, false, window, 0, 0, 0, 0, 0, false, false, true, false, 0, elt);
		elt.dispatchEvent(evObj);
	}
	else if( document.createEventObject )
	{
		var evObj = document.createEventObject();
		evObj.detail = 0;
		evObj.screenX = 0;
		evObj.screenY = 0;
		evObj.clientX = 0;
		evObj.clientY = 0;
		evObj.ctrlKey = false;
		evObj.altKey = false;
		evObj.shiftKey = true;
		evObj.metaKey = false;
		evObj.button = 0;
		evObj.relatedTarget = elt;
		elt.fireEvent("on" + event, evObj);
	}
}

/* ------------------------------------------------------------------------------------------------
	JSON RELATED FUNCTIONS
 ----------------------------------------------------------------------------------------------- */

function convertStringToJSON(str)
{
	if (str)
		return eval('(' + str + ')');
}

/* ------------------------------------------------------------------------------------------------
	XML RELATED FUNCTIONS
 ----------------------------------------------------------------------------------------------- */
 
/**
 * Return all children nodes in an array.
*/
function getAllChildren(xmlNode)
{
	return xmlNode.childNodes;
}

/**
 * Return all children nodes having specified name in an array.
*/
function getChildren(xmlNode, childrenName)
{
	var result = [];
	for (var i = 0; i < xmlNode.childNodes.length; i++)
	{
		if (xmlNode.childNodes.item(i).nodeName == childrenName)
		{
			result.push(xmlNode.childNodes.item(i));
		}
	}
	
	return result; //xmlNode.getElementsByTagName(childrenName); // this methods also returns grand-children of same name
}

/**
 * Return child node if any, null otherwise.
 * If node has multiple matching children, display an alert.
*/
function getUniqueChild(xmlNode, childName)
{
	var children = getChildren(xmlNode, childName);
	//var children = xmlNode.getElementsByTagName(childName);
	
	if (children.length > 1)
	{	
		alert("Multiple '" + childName + "' nodes set within parent node '" + xmlNode.nodeName + "'. Use last one.");
	}
	
	return (children.length == 0) ? null : children[children.length - 1];
}


/**
 * Create a string from an XML object.
*/
function convertXMLToString(xmlNode)
{
	try 
	{
		// Gecko-based browsers, Safari, Opera.
		return (new XMLSerializer()).serializeToString(xmlNode);
	}
	catch (e) 
	{
		try 
		{
			// Internet Explorer.
			return xmlNode.xml;
		}
		catch (e)
		{
			//Strange Browser ??
			alert('Xmlserializer not supported');
		}
	}
	return false;
}


/**
 * Create an XML object from a string
 * Requires header <?xml version="1.0" encoding="UTF-8" ?> for example in str
 */
function convertStringToXML(str)
{
	// As DOMParser is not supported by IE, create XML object using its own way
	if(typeof(DOMParser) == 'undefined')
	{
		DOMParser = function() {};
		DOMParser.prototype.parseFromString = function(str, contentType)
		{
			if (typeof(ActiveXObject) != 'undefined')
			{
				var xmldata = new ActiveXObject('MSXML.DomDocument');
				xmldata.async = false;
				xmldata.loadXML(str);
				
				return xmldata;
			}
			else if (typeof(XMLHttpRequest) != 'undefined')
			{
				var xmldata = new XMLHttpRequest;
				if(!contentType)
				{
					contentType = 'application/xml';
				}
				
				xmldata.open('GET', 'data:' + contentType + ';charset=utf-8,' + encodeURIComponent(str), false);
				
				if(xmldata.overrideMimeType)
				{
					xmldata.overrideMimeType(contentType);
				}
				
				xmldata.send(null);
				
				return xmldata.responseXML;
			}
		}
	}

	return (new DOMParser()).parseFromString(str, "text/xml");
}

/**
 * Create a new Document object. If no arguments are specified,
 * the document will be empty. If a root tag is specified, the document
 * will contain that single root tag. If the root tag has a namespace
 * prefix, the second argument must specify the URL that identifies the
 *namespace.
 */
function createXMLDocument(rootTagName, namespaceURL)
{
    if (!rootTagName) rootTagName = "";
    if (!namespaceURL) namespaceURL = "";

    if (document.implementation && document.implementation.createDocument)
	{
        // This is the W3C standard way to do it
        return document.implementation.createDocument(namespaceURL, rootTagName, null);
    }
    else 
	{
		// This is the IE way to do it
        // Create an empty document as an ActiveX object
        // If there is no root element, this is all we have to do
        var doc = new ActiveXObject("MSXML2.DOMDocument");

        // If there is a root tag, initialize the document
        if (rootTagName) 
		{
            // Look for a namespace prefix
            var prefix = "";
            var tagname = rootTagName;
            var p = rootTagName.indexOf(':');
            if (p != -1)
			{
                prefix = rootTagName.substring(0, p);
                tagname = rootTagName.substring(p+1);
            }

            // If we have a namespace, we must have a namespace prefix
            // If we don't have a namespace, we discard any prefix
            if (namespaceURL)
			{
                if (!prefix) prefix = "a0"; // What Firefox uses
            }
            else prefix = "";

            // Create the root element (with optional namespace) as a
            // string of text
            var text = "<" + (prefix?(prefix+":"):"") + tagname +
				(namespaceURL
                 ?(" xmlns:" + prefix + '="' + namespaceURL +'"')
                 :"") +
                "/>";
            // And parse that text into the empty document
            doc.loadXML(text);
        }
        return doc;
    }
};

/* ------------------------------------------------------------------------------------------------
	NODE CLEARNER
 ----------------------------------------------------------------------------------------------- */

var notWhitespace = /\S/;

var TEXT_NODE;
try {
   TEXT_NODE = Node.TEXT_NODE;
} catch(e) {
   TEXT_NODE = 3;
}

var ELEMENT_NODE;
try {
  ELEMENT_NODE = Node.ELEMENT_NODE;
} catch(e) {
   ELEMENT_NODE = 1;
}

function cleanWhitespace(node) {
  for (var x = 0; x < node.childNodes.length; x++) {
    var childNode = node.childNodes[x];
    if ((childNode.nodeType == TEXT_NODE)&&(!notWhitespace.test(childNode.nodeValue))) {
      node.removeChild(node.childNodes[x]);
      x--;
    }
    if (childNode.nodeType == ELEMENT_NODE) {
      cleanWhitespace(childNode);
    }
  }
}


/* ------------------------------------------------------------------------------------------------
	EXTEND STRING PROTOTYPE
 ----------------------------------------------------------------------------------------------- */
 
String.prototype.trim = function()
{
	return (this.replace(/^[\s\xA0]+/, "").replace(/[\s\xA0]+$/, ""))
}

String.prototype.startsWith = function(str)
{
	return (this.match("^"+str)==str)
}

String.prototype.endsWith = function(str)
{
	return (this.match(str+"$")==str)
}

