define(["jquery","wsbcore/helper","appconfig"],function(e,n,i){function l(e){var n="js!//maps.googleapis.com/maps/api/js?v=3.24&libraries=places,geometry";return e.loadUsingClient&&s.clientId||"publish"===e.loadingMode&&e.clientId?n+="&client="+(s.clientId||e.clientId):s.apiKey&&(n+="&key="+(e.apiKey||s.apiKey)),(e.loadUsingClient&&s.channel||"publish"===e.loadingMode&&e.channel)&&(n+="&channel="+(s.channel||e.channel)),n+="&language="+(e.language||s.language),n+="&callback="+(e.callbackName||s.callbackName)}function o(){require(["/i18n/resources/client","wsbcore/growl"],function(n){e("<div></div>").sfGrowl({title:n.resources.Client__Designer__Yikes_hit_a_snag,content:n.resources.Server__There_has_been_an_unexpected_error,icon:"error"})})}function a(e,n){"editor"===n&&o(),e.resolve(!1)}function r(){window.google&&(window.google=void 0)}function c(i,o){var c=l(o),t=o.loadingMode||s.loadingMode;return r(),n.require([c],e.noop,a.bind(null,i,t)),i}function t(e,n){n?u.withClientId=e:u.withApiKey=e}function g(e,n){window[e]=function(){n.resolve(window.google.maps)}}function d(n){var i=n||{},l=i.loadUsingClient?u.withClientId:u.withApiKey,o=i.callbackName||s.callbackName;return l||(l=e.Deferred(),g(o,l),c(l,i),t(l,i.loadUsingClient)),l.promise()}var s={loadUsingKey:!0,loadingMode:"editor",language:"en_US",callbackName:"onGoogleMapsReady",apiKey:i.googleMapsApiKey,clientId:i.googleMapsClientID,channel:i.googleMapsDesignerChannel},u={withClientId:null,withApiKey:null};return d});
//# sourceMappingURL=googleMaps.js.map