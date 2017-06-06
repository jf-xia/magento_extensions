###
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
###

_currentId = null

_closeUrl = _saveUrl = _filesUrl = null

saveFile = (id, content) ->
    if _currentId then _saveFile(_currentId, content)
    return 

_saveFile = (id, content) ->
    new Ajax.Request _saveUrl.replace("{{file}}", id).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
        method: "post"
        onSuccess: (transport) =>
            try
                response = eval('(' + transport.responseText + ')')
            catch exception
                response = {}

            {success, files, error} = response
            if error
                alert error   

            if success                
                file = editAreaLoader.getFile "edit_area", id
                file.edited = no
                iframe = document.getElementById('frame_edit_area')
                innerDoc = if iframe.contentDocument then iframe.contentDocument else iframe.contentWindow.document
                a = innerDoc.getElementById(file.html_id).getElementsByTagName('a')[0]
                a.className = ''
            return 
        parameters: {content: content}
    } 
    
    return 

openFile = (url, file) ->

    filename = Base64.encode file
    
    new Ajax.Request url.replace("{{filename}}", filename).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
        method: "get"
        onSuccess: (transport) ->
            try
                response = eval('(' + transport.responseText + ')')
            catch exception
                response = {}

            {success, content, error, path} = response
            if error
                alert error
            if success 
                new_file = eval('(' + content + ')')
                {id} = new_file
                _currentId = id
                editAreaLoader.openFile "edit_area", new_file

                file = editAreaLoader.getFile "edit_area", id
                iframe = document.getElementById('frame_edit_area')
                innerDoc = if iframe.contentDocument then iframe.contentDocument else iframe.contentWindow.document
                a = innerDoc.getElementById(file.html_id).getElementsByTagName('a')[0]
                a.title = path

            return        
        onComplete: (transport) => 


            return
    }       



    return


closeFile = (info) ->
    if info.edited is yes
        answer = confirm "File was changed. Do you want to save it?"
        if answer is yes
           _saveFile info.id, info.text


    {id} = info
    _currentId = null
    new Ajax.Request _closeUrl.replace("{{file}}", id).replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
        method: "get"
        onSuccess: (transport) ->
            try
                response = eval('(' + transport.responseText + ')')
            catch exception
                response = {}

            {success, error} = response
            if error
                alert error                
            return        
    }      
    
    return 

switchOn = (info) ->
    {id} = info
    if id then _currentId = id       
    return


getFiles = () ->
    new Ajax.Request _filesUrl.replace(/^http[s]{0,1}/, window.location.href.replace(/:[^:].*$/i, '')), {
        method: "get"
        onSuccess: (transport) ->
            try
                response = eval('(' + transport.responseText + ')')
            catch exception
                response = {}

            {success, files, error} = response
            if error
                alert error   

            if success
                for info in files                    
                    new_file = eval('(' + info + ')')                    
                    {id, path} = new_file
                    _currentId = id
                    editAreaLoader.openFile "edit_area", new_file  
                    file = editAreaLoader.getFile "edit_area", id
                    iframe = document.getElementById('frame_edit_area')
                    innerDoc = if iframe.contentDocument then iframe.contentDocument else iframe.contentWindow.document
                    a = innerDoc.getElementById(file.html_id).getElementsByTagName('a')[0]
                    a.title = path                             
             
            return        
    }    

    return