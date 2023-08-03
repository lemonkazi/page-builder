(function() {
  'use strict';

  exports.getRandomArbitrary = function(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
  };

  exports.randomString = function(length) {
    var text = '';
    var possible =
      'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    for (var i = 0; i < length; i++) {
      text += possible.charAt(Math.floor(Math.random() * possible.length));
    }

    return text;
  };

  exports.getParameterByName = function(name, url) {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
      results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  };

  exports.contains = function(needle) {
    // Per spec, the way to identify NaN is that it is not equal to itself
    var findNaN = needle !== needle;
    var indexOf;

    if (!findNaN && typeof Array.prototype.indexOf === 'function') {
      indexOf = Array.prototype.indexOf;
    } else {
      indexOf = function(needle) {
        var i = -1,
          index = -1;

        for (i = 0; i < this.length; i++) {
          var item = this[i];

          if ((findNaN && item !== item) || item === needle) {
            index = i;
            break;
          }
        }

        return index;
      };
    }

    return indexOf.call(this, needle) > -1;
  };

  exports.htmlToElement = function(html) {
    var template = document.createElement('template');
    template.innerHTML = html;
    return template.content.firstChild;
  };

  // applies base64 encoding to a string
  exports.custom_base64_encode = function(str) {
    let encoded_element = btoa(encodeURIComponent(str));

    return encoded_element;
  };

  // decodes a base64 encoded string
  exports.custom_base64_decode = function(str) {
    let decoded_element = atob(str);

    return decoded_element;
  };

  exports.debounce = (func, wait, immediate) => {
    var timeout;
    return () => {
      const context = this,
        args = arguments;
      const later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };
      const callNow = immediate && !timeout;
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
      if (callNow) func.apply(context, args);
    };
  };
})();
