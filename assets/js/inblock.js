/* globals initMap: false */

const config = require('./modules/config');
const DetectElementResize = require('detect-element-resize');

(function() {
  'use strict';

  const existingStyleSheets = Array.prototype.slice.call(
    document.querySelectorAll('style')
  );
  DetectElementResize.addResizeListener(document.getElementById('page'), () =>
    parent.postMessage('onHeightChange', '*')
  );
  const allStylesheets = document.querySelectorAll('style');
  allStylesheets.forEach(style => {
    if (existingStyleSheets.indexOf(style) === -1)
      style.classList.add('resize-triggers-styles');
  });

  let scriptTag = document.createElement('SCRIPT');
  scriptTag.id = 'fr-fek';
  scriptTag.text =
    "try{(function (k){localStorage.FEK=k;t=document.getElementById('fr-fek');t.parentNode.removeChild(t);})('KB2A3C2D3rD1H5D4H3B2B10C8E2D5C3eUh1QBRVCDLPAZMBQ==')}catch(e){};";

  document.body.appendChild(scriptTag);

  config.runInBlocks();

  window.addEventListener('message', receiveMessage, false);

  function receiveMessage(event) {
    if (event.data.action === 'loadMapAPI') {
      if (document.body.querySelectorAll('script.mapapi').length === 0) {
        var scriptTag = document.createElement('SCRIPT');
        scriptTag.classList.add('mapapi');
        scriptTag.src =
          'https://maps.googleapis.com/maps/api/js?key=' +
          event.data.key +
          '&callback=initMap';
        scriptTag.setAttribute('async', '');
        scriptTag.setAttribute('defer', '');

        document.body.appendChild(scriptTag);
      } else {
        initMap();
      }
    }
  }
})();

/* this attempts to load custom JS code to include in the inblock page */
try {
  require('./custom/inblock.js');
} catch (e) {}
