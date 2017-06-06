/*
 MagPleasure Co.
 
 NOTICE OF LICENSE
 
 This source file is subject to the EULA
 that is bundled with this package in the file LICENSE.txt.
 It is also available through the world-wide-web at this URL:
 http://www.magpleasure.com/LICENSE.txt

 @category   Magpleasure
 @package    Magpleasure_Filesystem
 @copyright  Copyright (c) 2011 Magpleasure Co. (http://www.magpleasure.com)
 @license    http://www.magpleasure.com/LICENSE.txt
*/var closeFile, getFiles, openFile, saveFile, switchOn, _closeUrl, _currentId, _filesUrl, _saveFile, _saveUrl;
var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };
_currentId = null;
_closeUrl = _saveUrl = _filesUrl = null;
saveFile = function(id, content) {
  if (_currentId) {
    _saveFile(_currentId, content);
  }
};
_saveFile = function(id, content) {
  new Ajax.Request(_saveUrl.replace("{{file}}", id).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
    method: "post",
    onSuccess: __bind(function(transport) {
      var a, error, file, files, iframe, innerDoc, response, success;
      try {
        response = eval('(' + transport.responseText + ')');
      } catch (exception) {
        response = {};
      }
      success = response.success, files = response.files, error = response.error;
      if (error) {
        alert(error);
      }
      if (success) {
        file = editAreaLoader.getFile("edit_area", id);
        file.edited = false;
        iframe = document.getElementById('frame_edit_area');
        innerDoc = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;
        a = innerDoc.getElementById(file.html_id).getElementsByTagName('a')[0];
        a.className = '';
      }
    }, this),
    parameters: {
      content: content
    }
  });
};
openFile = function(url, file) {
  var filename;
  filename = Base64.encode(file);
  new Ajax.Request(url.replace("{{filename}}", filename).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
    method: "get",
    onSuccess: function(transport) {
      var a, content, error, id, iframe, innerDoc, new_file, path, response, success;
      try {
        response = eval('(' + transport.responseText + ')');
      } catch (exception) {
        response = {};
      }
      success = response.success, content = response.content, error = response.error, path = response.path;
      if (error) {
        alert(error);
      }
      if (success) {
        new_file = eval('(' + content + ')');
        id = new_file.id;
        _currentId = id;
        editAreaLoader.openFile("edit_area", new_file);
        file = editAreaLoader.getFile("edit_area", id);
        iframe = document.getElementById('frame_edit_area');
        innerDoc = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;
        a = innerDoc.getElementById(file.html_id).getElementsByTagName('a')[0];
        a.title = path;
      }
    },
    onComplete: __bind(function(transport) {}, this)
  });
};
closeFile = function(info) {
  var answer, id;
  if (info.edited === true) {
    answer = confirm("File was changed. Do you want to save it?");
    if (answer === true) {
      _saveFile(info.id, info.text);
    }
  }
  id = info.id;
  _currentId = null;
  new Ajax.Request(_closeUrl.replace("{{file}}", id).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
    method: "get",
    onSuccess: function(transport) {
      var error, response, success;
      try {
        response = eval('(' + transport.responseText + ')');
      } catch (exception) {
        response = {};
      }
      success = response.success, error = response.error;
      if (error) {
        alert(error);
      }
    }
  });
};
switchOn = function(info) {
  var id;
  id = info.id;
  if (id) {
    _currentId = id;
  }
};
getFiles = function() {
  new Ajax.Request(_filesUrl.replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
    method: "get",
    onSuccess: function(transport) {
      var a, error, file, files, id, iframe, info, innerDoc, new_file, path, response, success, _i, _len;
      try {
        response = eval('(' + transport.responseText + ')');
      } catch (exception) {
        response = {};
      }
      success = response.success, files = response.files, error = response.error;
      if (error) {
        alert(error);
      }
      if (success) {
        for (_i = 0, _len = files.length; _i < _len; _i++) {
          info = files[_i];
          new_file = eval('(' + info + ')');
          id = new_file.id, path = new_file.path;
          _currentId = id;
          editAreaLoader.openFile("edit_area", new_file);
          file = editAreaLoader.getFile("edit_area", id);
          iframe = document.getElementById('frame_edit_area');
          innerDoc = iframe.contentDocument ? iframe.contentDocument : iframe.contentWindow.document;
          a = innerDoc.getElementById(file.html_id).getElementsByTagName('a')[0];
          a.title = path;
        }
      }
    }
  });
};