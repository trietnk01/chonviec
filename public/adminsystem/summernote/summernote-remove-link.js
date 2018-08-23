/**
 * 
 * copyright [year] [your Business Name and/or Your Name].
 * email: your@email.com
 * license: Your chosen license, or link to a license file.
 * 
 */
(function (factory){
  if (typeof define === 'function' && define.amd) {
    // AMD. Register as an anonymous module.
    define(['jquery'], factory);
  } else if (typeof module === 'object' && module.exports) {
    // Node/CommonJS
    module.exports = factory(require('jquery'));
  } else {
    // Browser globals
    factory(window.jQuery);
  }
}(function ($){
  var readFileAsDataURL = function (file) {
    return $.Deferred( function (deferred) {
      $.extend(new FileReader(),{
        onload: function (e) {
          var sDataURL = e.target.result;
          deferred.resolve(sDataURL);
        },
        onerror: function () {
          deferred.reject(this);
        }
      }).readAsDataURL(file);
    }).promise();
  };
  $.extend(true,$.summernote.lang, {
    'en-US': { 
      removeLinkPlugin: {
        exampleText: 'RemoveLink',
        dialogTitle: 'RemoveLink',
        okButton: 'OK'
      }
    }
  });
  $.extend($.summernote.options, {
    removeLinkPlugin: {
      icon: '<i class="fas fa-unlink"></i>',
      tooltip: 'Unlink'
    }
  });
  $.extend($.summernote.plugins,{
    'removeLinkPlugin':function (context){
      
    }
  });
}));
