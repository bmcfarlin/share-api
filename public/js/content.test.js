"use strict";

(function($){

  var app = {
    'id':'fw.qea.la',
    'name':'Qeala Labs',
    'author':'Ben McFarlin',
    'traceLevel':3,
    'data':null,
    'loading':null,
    'jwt':null,
    'settings':{
      'url':'/en/handler'
    },
    'storage':{
      'get':function(k){
        return window.localStorage.getItem(k);
      },
      'set':function(k,v){
        window.localStorage.setItem(k, v);
      },
      'remove':function(k){
        window.localStorage.removeItem(k);
      }
    },
    'ajax':function(cmd, data, cb){
      var settings = {};
      settings.url = app.settings.url + '/' + cmd;
      settings.cache = false;
      settings.async = true;
      settings.dataType = 'json';
      settings.type = 'POST';

      settings.data = data;

      settings.beforeSend = function(xhr){
        if(app.jwt){
          xhr.setRequestHeader('Authorization', 'Bearer ' + app.jwt);
        }
      };

      settings.error = function(rq,cd,ex){
        var r = {};
        r['status'] = 'error';
        r['cmd'] = cmd;
        r['error'] = ex.message;
        if(cb) cb(r);
        app.hideLoading();
      };

      settings.success = function(rs,cd,rq){

        if(rs.jwt){
          var jwt = rs.jwt;
          var expires = 365;
          if(rs.expires){
            var ts = parseInt(rs.expires, 10);
            ts = ts * 1000;
            var dtm = new Date(ts);
            var delta = dtm.getTime() - (new Date()).getTime();
            expires = Math.floor(delta / (1000 * 3600 * 24));
          }
          trace({expires:expires});
          Cookies.set('__jwt', jwt, {expires:expires});
        }

        if(rs.status == 'success'){
          var r = {};
          r['status'] = 'success';
          r['cmd'] = cmd;
          r['items'] = rs['items']? rs['items'] : [];
          if(cb) cb(r);
          app.hideLoading();
        }else{
          var r = {};
          r['status'] = 'error';
          r['cmd'] = cmd;
          r['error'] = rs.error;
          if(cb) cb(r);
          app.hideLoading();
        }
      };

      app.showLoading();
      $.ajax(settings);
    },
    'showLoading':function(){
      app.loading = window.setTimeout(function(){
        if(app.loading) $('div.loading').show();
      }, 333);
    },
    'hideLoading':function(){
      app.loading = null;
      $('div.loading').hide();
    },
    'bindHandlers':function(cb){

      $('button.test').on('click', function(e){
        var element = $(this);
        var data = {'name':'ben'};
        app.ajax('test', data, function(data){
          if(data.status == 'success'){
            $('div.status').html('success');
          }else{
            $('div.status').html(data.error);
          }
        });
      });

      // newhandler
      if(cb) cb();
    },
    'init':function(cb){

      if(typeof(window.navigator) == 'undefined') throw new Error('Browser does not support window navigator');
      if(typeof(window.navigator.geolocation) == 'undefined') throw new Error('Browser does not support window navigator geolocation');
      if(typeof(window.localStorage) == 'undefined') throw new Error('Browser does not support local storage');

      app.jwt = Cookies.get('__jwt');

      if(cb) cb();
    }
  };


  $(window).on('load', function(){
    $.ready.then(function(){
      trace('window.load');
    });
  });

  $(document).ready(function(){
    trace('document.ready');
    app.bindHandlers(function(data){
      app.init(function(data){
      });
    });
  });

  String.prototype.local = function(){
    var dtm = this;
    dtm = dtm.replace(' ', 'T');
    dtm = dtm + 'Z';
    dtm = new Date(dtm);
    dtm = dtm.toLocaleString();
    return dtm;
  }

  Date.UTC = function(){
    var dtm = new Date();
    var udtms = dtm.toUTCString();
    var sdtms = dtm.toISOString();
    sdtms = sdtms.replace('T', ' ');
    sdtms = sdtms.replace('Z', '');
    var ms = Date.parse(udtms);
    return sdtms;
  };

  function clone(object) {
    function F() {}
    F.prototype = object;
    return new F;
  }

  function verbose(s){
    if(app.traceLevel > 2) trace(s);
  }

  function debug(s){
    if(app.traceLevel > 1) trace(s);
  }

  function trace(s){
    if(app.traceLevel > 0){
      //if(typeof(s) == 'object') s = JSON.stringify(s);
      if(window.runtime){
        window.runtime.trace(s);
      }else{
        if(window.console) window.console.log(s);
      }
    }
  }


})(jQuery);



