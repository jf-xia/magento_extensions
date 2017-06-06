var G_AJAXLOGIN_URL = '/ajaxlogin/login/form/';

function onepageLogin(button)
{
	var loginForm = button.up('form');
    if(loginForm.validator && loginForm.validator.validate()){
        button.disabled = true;
        loginForm.request({
      	  onComplete: function(){ alert('Form data saved!'); }
      	});
      	return false;
    }
}

function bindLoginPost(evt){
    if (evt.keyCode == Event.KEY_RETURN) {
    	onepageLogin(Event.element(evt));
    }
}

function showLogin(url) {
	TINY.box.show({iframe:url,boxid:'frameless',width:420,height:270,fixed:false,maskid:'bluemask',maskopacity:40});
	return false;
}

function showloginbox() {
	showLogin(G_AJAXLOGIN_URL);
	return false;
}





