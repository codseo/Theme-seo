(function (root, factory) {
    if (typeof exports === 'object' && typeof module === 'object') {
      module.exports = factory();
    } else if(typeof define === 'function' && define.amd) {
      define([], factory);
    } else if(typeof exports === 'object') {
      exports['mlm_notifier'] = factory();
    } else {
      root["mlm_notifier"] = factory();
    }
  }(typeof self !== 'undefined' ? self : this, function () {
    var count = 0;
    var d = document;
  
    var myCreateElement = function(elem, attrs) {
      var el = d.createElement(elem);
      for (var prop in attrs) {
        el.setAttribute(prop, attrs[prop]);
      }
      return el;
    };
  
    var createContainer = function() {
      var container = myCreateElement('div', {class: 'mlm-notifier-container', id: 'mlm-notifier-container'});
      d.body.appendChild(container);
    };
  
    var show = function(msg,icon,timeout,location,style,audio) {

        if (String(location)=='undefined') location = 'top-right';

      if (typeof timeout != 'number') timeout = 0;
  
      var ntfId = 'notifier-' + count;
    
      var container = d.querySelector('.mlm-notifier-container'),
          ntf       = myCreateElement('div', {class: 'notifier '}),
          ntfTitle  = myCreateElement('h4',  {class: 'notifier-title'}),
          ntfTextWrapper  = myCreateElement('p',  {class: 'notifier-text-wrapper'}),
          ntfBody   = myCreateElement('div', {class: 'notifier-body'}),
          ntfImg    = myCreateElement('div', {class: 'notifier-img'}),
          img       = myCreateElement('img', {class: 'img', src: icon}),
          ntfClose  = myCreateElement('button',{class: 'notifier-close', type: 'button'});
  
      container.style.width = '350px';
      // ntfTitle.innerHTML = title;
      ntfBody.innerHTML  = msg;
      ntfClose.innerHTML = '&times;';
  
      if (icon.length > 0) {
        ntfImg.appendChild(img);
      }
  
      ntf.appendChild(ntfClose);
      ntf.appendChild(ntfImg);
      // ntfTextWrapper.appendChild(ntfTitle)
      ntfTextWrapper.appendChild(ntfBody)
      ntf.appendChild(ntfTextWrapper);
  
      if(location == 'bottom-right' || location == 'bottom-left'){
        container.insertBefore(ntf, container.firstChild);
      }else{
        container.appendChild(ntf);
      }


      container.classList.add(location);
      if(String(icon).length > 0 || String(icon) != 'undefined'){
        ntf.classList.add('null-icon');
      }
      container.classList.add(location);
      ntf.style.background = style.background;
      ntf.style.borderRadius = style.radius + 'px';
      ntf.style.direction = 'rtl';
      ntfClose.style.right = 'auto';
      ntfClose.style.left = '0px';
      // ntfTitle.style.color = style.color;
      ntfBody.style.color = style.color;
      move_border_top(ntf,timeout,style.border);
  
  
      setTimeout(function() {
        ntf.className += ' shown';
        ntf.setAttribute('id', ntfId);
      }, 100);
  
      if (timeout > 0) {
  
        setTimeout(function() {
          hide(ntfId);

          // setTimeout(function () {
          //   container.style.width = "0px";
          //   container.style.padding = "0px !important";
          // },5000)
        }, timeout);
  
      }
  
      ntfClose.addEventListener('click', function() {
        hide(ntfId);
        // setTimeout(function(){
        //    container.style.width = "0px";
        //    container.style.padding = "0px !important";
        // },5000)
      });
  
      count += 1;
  
      return ntfId;
  
    };
  
    var move_border_top = function(ntf,time,color){
        sec = time / 1000;
        ntf.pseudoStyle("before","transition",sec+'s');
        ntf.pseudoStyle("before","background",color);
    }


    var hide = function(notificationId) {
  
      var notification = document.getElementById(notificationId);
  
      if (notification) {
  
        notification.className = notification.className.replace(' shown', '');
  
        setTimeout(function() {
          notification.parentNode.removeChild(notification);
        }, 600);
  
        return true;
  
      } else {
        return false;
      }
    };
  
    createContainer();
  
    return {
      show : show,
      hide : hide
    };
  }));
  

  var UID = {
	_current: 0,
	getNew: function(){
		this._current++;
		return this._current;
	}
};

HTMLElement.prototype.pseudoStyle = function(element,prop,value){
	var _this = this;
	var _sheetId = "pseudoStyles";
	var _head = document.head || document.getElementsByTagName('head')[0];
	var _sheet = document.getElementById(_sheetId) || document.createElement('style');
	_sheet.id = _sheetId;
	var className = "pseudoStyle" + UID.getNew();
	
	_this.className +=  " "+className; 
	
	_sheet.innerHTML += " ."+className+":"+element+"{"+prop+":"+value+"}";
	_head.appendChild(_sheet);
	return this;
};