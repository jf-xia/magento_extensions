<?php
   define('ZOPIM_BASE_URL', "https://www.zopim.com/");
   define('ZOPIM_LOGIN_URL', ZOPIM_BASE_URL."plugins/login");
   define('ZOPIM_SIGNUP_URL', ZOPIM_BASE_URL."plugins/createTrialAccount");
   define('ZOPIM_GETACCOUNTDETAILS_URL', ZOPIM_BASE_URL."plugins/getAccountDetails");
class Zopim_Livechat_Block_Accountconfig extends Mage_Core_Block_Template
{

   private $zmodel;
   protected function _toHtml()
   { 
      $this->zmodel = Mage::getModel('livechat/livechat')->load(1);
      $zoptions = $this->zmodel->_data;
   /*
   $object = Mage::getModel('livechat/livechat')->load(1);
   $object->setTitle('This is a changed title');
   $object->save();*/

      $password = "";
      if ($zoptions['salt'] != "") {
         $password = "password";
      }

      $authenticated = "";
      $error = array();
      $gotologin = 0;
      
      if ($this->getRequest()->getParam('deactivate')=="yes") {
         $this->zmodel->setSalt('');
         $this->zmodel->setCode('zopim');
      } else if ($this->getRequest()->getParam('zopimusername')!="") {
         // logging in
         if ($this->getRequest()->getParam('zopimUseSSL')!="") {
            $this->zmodel->setUseSSL('zopimUseSSL');
         } else {
            $this->zmodel->setUseSSL('');
         }

         $zopimusername = $this->getRequest()->getParam('zopimusername');
         $zopimpassword = $this->getRequest()->getParam('zopimpassword');

         $logindata = array("email" => $zopimusername, "password" => $zopimpassword);
         $loginresult = $this->do_post_request(ZOPIM_LOGIN_URL, $logindata);
         $loginresult = Zend_Json::decode($loginresult);

         if (isset($loginresult["error"])) {
            $error["login"] = "<b>Could not log in to Zopim. Please check your login details. If problem persists, try connecting without SSL enabled.</b>";
            $gotologin = 1;
            $this->zmodel->setSalt('');
         } else if (isset($loginresult["salt"])) {

            $this->zmodel->setUsername($zopimusername);
            $this->zmodel->setSalt($loginresult["salt"]);
            $account = Zend_Json::decode($this->do_post_request(ZOPIM_GETACCOUNTDETAILS_URL, array("salt" => $loginresult["salt"])));

            if (isset($account)) {
               $this->zmodel->setCode($account["account_key"]);

               if ($this->zmodel->getGreetings() == "") {
                  $jsongreetings = Zend_Json::encode($account["settings"]["greetings"]);
                  $this->zmodel->setGreetings($jsongreetings);
               }
            }
         } else {
            $this->zmodel->setSalt('');
            $error["login"] = "<b>Could not log in to Zopim. We were unable to contact Zopim servers. Please check with your server administrator to ensure that <a href='http://www.php.net/manual/en/book.curl.php'>PHP Curl</a> is installed and permissions are set correctly.</b>";
         }
      } else if ($this->getRequest()->getParam('zopimfirstname')!="") {
         // signing up

         if ($this->getRequest()->getParam('zopimUseSSL')!="") {
            $this->zmodel->setUseSSL('zopimUseSSL');
         } else {
            $this->zmodel->setUseSSL('');
         }
         
         $createdata = array(
            "email" => $this->getRequest()->getParam('zopimnewemail'),
            "first_name" => $this->getRequest()->getParam('zopimfirstname'),
            "last_name" => $this->getRequest()->getParam('zopimlastname'), 
            "display_name" => $this->getRequest()->getParam('zopimfirstname')." ".$this->getRequest()->getParam('zopimlastname'),
            "eref" => "",
            "source" => "magento",
            "recaptcha_challenge_field" => $this->getRequest()->getParam('recaptcha_challenge_field'),
            "recaptcha_response_field" => $this->getRequest()->getParam('recaptcha_response_field')
         );

         $signupresult = Zend_Json::decode($this->do_post_request(ZOPIM_SIGNUP_URL, $createdata));
         if (isset($signupresult["error"])) {
            $error["auth"] = "<div style='color:#c33;'>Error during activation: <b>".$signupresult["error"]."</b> Please try again.</div>";
         } else if (isset($signupresult["account_key"])) {
            $message = "<b>Thank you for signing up. Please check your mail for your password to complete the process. </b>";
            $gotologin = 1;
         } else {
           $error["auth"] = "<b>Could not activate account. The Magento installation was unable to contact Zopim servers. Please check with your server administrator to ensure that <a href='http://www.php.net/manual/en/book.curl.php'>PHP Curl</a> is installed and permissions are set correctly.</b>";
         }
      }
      $this->zmodel->save();         

      if ($this->zmodel->getCode() != "" && $this->zmodel->getCode() != "zopim") {

         if (isset($account)) {
            $accountDetails = $account;
         } else {
            $accountDetails = Zend_Json::decode($this->do_post_request(ZOPIM_GETACCOUNTDETAILS_URL, array("salt" => $this->zmodel->getSalt())));
         }
      
         if (!isset($accountDetails) || isset($accountDetails["error"])) {
            $gotologin = 1;
            $error["auth"] = '
         		<h3 class="hndle"><span>Account no longer linked!</span></h3>
                  We could not verify your Zopim account. Please check your password and try again.
                ';
         } else {
            $authenticated = "ok";
         }
      }

      if (isset($error["auth"])) {
         $html = '
         <div id="messagesbox"><ul class="messages"><li class="error-msg"><ul><li><div id="themessage">'.$error["auth"].'</div></li></ul></li></ul><br></div>
         ';
      } else if (isset($error["login"])) {
         $html = '
         <div id="messagesbox"><ul class="messages"><li class="error-msg"><ul><li><div id="themessage">'.$error["login"].'</div></li></ul></li></ul><br></div>
         ';
      } else if (isset($message)) {
         $html = '
         <div id="messagesbox"><ul class="messages"><li class="success-msg"><ul><li><div id="themessage">'.$message.'</div></li></ul></li></ul><br></div>
         ';
      } else {
         $html = '
         <div id="messagesbox" style="display: none"><ul class="messages"><li class="error-msg"><ul><li><div id="themessage"></div></li></ul></li></ul><br></div>
         ';
      }

      if ($authenticated == "ok") {
         if ($accountDetails["package_id"]=="trial") {
            $accountDetails["package_id"] = "Free Lite Package + 14 Days Full-features";
         } else {
            $accountDetails["package_id"] .= " Package";
         }
         $html .= '
         <div class="content-header" style="visibility: visible;">
         <h3 class="icon-head head-categories" style="background-image: url(https://zopim.com/assets/branding/zopim.com/chatman/online.png")>Zopim Account Configuration</h3>
         </div><p>
         <div id="existingform" style="">
         <div>
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Successfully connected to Zopim</h4>
         </div>
         <div id="sales_report_base_fieldset" class="fieldset ">
         <div class="hor-scroll">


<br>
<span style="float:right; line-height: 40px"><a href="'.$this->curpageurl().'?deactivate=yes">Deactivate</a></span>
<div class="fieldset-legend" style="display: inline; line-height: 40px">Currently Activated Account </div>&rarr; <b>'.$this->zmodel->getUsername().'</b> <div style="display:inline-block;background:#444;color:#fff;font-size:10px;text-transform:uppercase;padding:3px 8px;-moz-border-radius:5px;-webkit-border-radius:5px;">'.$accountDetails["package_id"].'</div><p>You may now proceed to the dashboard to chat with customers, customize your widget or enable instant messaging integration through the menu.
<br><br>
         </div>
         </div>
         </div></div>
         </div>
         
         ';
      } else {
      $waschecked = "";
      if ($this->getRequest()->getParam('zopimfirstname')) {
         $waschecked = "checked";
      }
      $html .= '
         <div class="content-header" style="visibility: visible;">
					<h3 class="icon-head head-categories" style="background-image: url(http://zopim.com/assets/branding/zopim.com/chatman/online.png")>Account Configuration</h3>	<p class="content-buttons form-buttons" style=""><span id="btn_new"><button style="" onclick="document.getElementById(\'login_form\').submit()" class="scalable save" type="button" id="id_0b860228d9b3c83ba14a7ae8fed1a587"><span>Link Up</span></button></span><span id="btn_link"><button style="" onclick="checkSignUp()" class="scalable save" type="button" id="id_0b860228d9b3c83ba14a7ae8fed1a587"><span>Sign Up</span></button></span></p>
         </div><p>
         ';
      // .$this->getRequest()->getParam('abc')
      $html .= '
         <table cellspacing="0">
         <tr><td class="page">
         <b>Select A Setup</b>
         <div style="padding:10px; display: inline;">
         <div style="padding:5px 0;cursor:pointer;vertical-align:middle;" onclick="javascript: showSignup(1)"><input type="radio" name="formtoshow" class="input-text page" id="formtoshow_signup" value="yes" onchange="javascript: showSignup(1)"/> Give me a new account &mdash; <i>absolutely free!</i></div>
         <div style="padding:5px 0;cursor:pointer;" onclick="javascript: showSignup(0)"><input type="radio" name="formtoshow" class="input-text page" id="formtoshow_existing" value="no" onchange="javascript: showSignup(0)"/> I already have a Zopim account</div>
         </div>
         </td>

         </tr>
         </table>
         <div id="existingform" style="display: none">
         <div>
         <div class="entry-edit">
         <form method="get" action="'.$this->curpageurl().'" id="login_form">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Link up to your zopim account</h4>
         <div class="form-buttons"></div>
         </div>
         <div id="sales_report_base_fieldset" class="fieldset ">
         <div class="hor-scroll">
         <table cellspacing="0" class="form-list">
         <tbody>
         <tr>
         <td class="hidden" colspan="2"><input type="hidden" value="" name="store_ids" id="sales_report_store_ids"></td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Zopim username (e-mail)</td>
         <td class="value"><input class=" input" style="width:200px;padding:2px;" type="text" name="zopimusername" value="'.$zoptions["username"].'" /></td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Zopim password</td>
         <td class="value"><input class=" input" style="width:200px;padding:2px;" type="password" name="zopimpassword" /></td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Use SSL</td>
         <td class="value"><input type="checkbox" name="zopimusessl" value="zopimusessl" checked> Uncheck this if you are unable to login</td>
         </tr>
         </tbody>
         </table>
         </div>
         </div>
         <div align="right">
         <button style="" onclick="document.getElementById(\'login_form\').submit()" class="scalable save" type="button" id="id_0b860228d9b3c83ba14a7ae8fed1a587"><span>Link Up</span></button></div><br>
         </form></div></div>
         </div>
         ';
      $html .= '
         <div id="signupform" style="display: none;">
         <div>
         <div class="entry-edit">
         <form method="GET" action="'.$this->curPageURL().'" id="signup_form"><div></div><div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Activate your free Zopim Account</h4>
         <div class="form-buttons"></div>
         </div>
         <div id="sales_report_base_fieldset" class="fieldset ">
         <div class="hor-scroll">
         <table cellspacing="0" class="form-list">
         <tbody>
         <tr>
         <td class="hidden" colspan="2"><input type="hidden" value="" name="store_ids" id="sales_report_store_ids"></td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">First Name</td>
         <td class="value"><input class=" input" style="width:200px;padding:2px;" type="text" name="zopimfirstname" id="zopimfirstname" value="'.$this->getRequest()->getParam('zopimfirstname').'" /></td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Last Name</td>
         <td class="value"><input class=" input" style="width:200px;padding:2px;" type="text" name="zopimlastname" id="zopimlastname" value="'.$this->getRequest()->getParam('zopimlastname').'" /></td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">E-mail</td>
         <td class="value"><input class=" input" style="width:200px;padding:2px;" type="text" id="zopimnewemail" name="zopimnewemail" value="'.$this->getRequest()->getParam('zopimnewemail').'" /></td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Use SSL</td>
         <td class="value"><input type="checkbox" name="zopimUseSSL" value="zopimUseSSL" checked> Uncheck this if you are unable to login</td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Verification</td>
         <td class="value">
         <script type="text/javascript" src="https://api-secure.recaptcha.net/challenge?k=6Lfr8AQAAAAAAC7MpRXM2hgLfyss_KKjvcJ_JFIk">
         </script>
         <noscript>
            <iframe src="https://api-secure.recaptcha.net/noscript?k=6Lfr8AQAAAAAAC7MpRXM2hgLfyss_KKjvcJ_JFIk"
                height="300" width="500" frameborder="0"></iframe><br>
            <textarea name="recaptcha_challenge_field" rows="3" cols="40">
            </textarea>
            <input type="hidden" name="recaptcha_response_field"
                value="manual_challenge">
         </noscript>
</td>
</tr>
         <tr>
         <td class="label" style="width:180px;">
         </td>
         <td class="value"><input type="checkbox" name="zopimagree" id="zopimagree" value="agree" '.$waschecked.'> I agree to Zopim\'s <a href="http://www.zopim.com/termsnconditions" target="_blank">Terms of Service</a> & <a href="http://www.zopim.com/privacypolicy" target="_blank">Privacy Policy</a>.

<br/><br/>The Zopim live chat bar will be displayed on your shop front once your account is activated.
         </td>
         </tr>
      </tbody>
        </table>
            </div>
        </div>
<div align="right">
<button style="" onclick="checkSignUp()" class="scalable save" type="button" id="id_0b860228d9b3c83ba14a7ae8fed1a587"><span>Sign Up</span></button></div></form></div></div></div>

<script type="text/javascript">
function showSignup(whichform) {
   if (whichform == \'1\') {
      document.getElementById(\'existingform\').style.display = "none";
			document.getElementById(\'btn_new\').style.display = "none";
			document.getElementById(\'btn_link\').style.display = "inline";
      document.getElementById(\'signupform\').style.display = "block";
      document.getElementById(\'formtoshow_signup\').checked = \'true\';
   } else {
      document.getElementById(\'signupform\').style.display = "none";
			document.getElementById(\'btn_link\').style.display = "none";
			document.getElementById(\'btn_new\').style.display = "inline";
      document.getElementById(\'existingform\').style.display = "block";
      document.getElementById(\'formtoshow_existing\').checked = \'true\';
   }
   }
</script>
<script type="text/javascript">
         '; 

if ($authenticated != "ok" && $gotologin!=1) {
   $html .= "showSignup(1); ";
} else {
   $html .= "showSignup(0); ";
}
      $html .= "
function checkSignUp() {

   var message = 'Oops! ';
   if (document.getElementById('zopimfirstname').value == '') {

      message = message + 'First name is required. ';
   }
   if (document.getElementById('zopimlastname').value == '') {

      message = message + 'Last name is required. ';
   }
   if (document.getElementById('zopimnewemail').value == '') {

      message = message + 'Your email is required. ';
   }
   if (document.getElementById('zopimagree').checked == '') {

      message = message + 'You must agree to our Terms of Service to continue. ';
   }

   if (message != 'Oops! ') {

      document.getElementById('messagesbox').style.display = 'block';
      document.getElementById('themessage').innerHTML = message;
      return false;
   }

   document.getElementById('signup_form').submit();
   return true; 
   }
   </script>
"; }
      return $html;
   }

   private function curPageURL() {
      $pageURL = 'http';
      if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
      $pageURL .= "://";
      if ($_SERVER["SERVER_PORT"] != "80") {
      $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
      } else {
      $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
      }

      $pageURL = preg_replace("/\?.*$/", "", $pageURL);

      return $pageURL;
   }

   private function do_post_request($url, $_data)
   {
      if ($this->zmodel->getUseSSL() != "zopimUseSSL") {
         $url = str_replace("https", "http", $url);
      }

      $data = array();    
      while(list($n,$v) = each($_data)){
         $data[] = urlencode($n)."=".urlencode($v);
      }    
      $data = implode('&', $data);

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $response = curl_exec($ch);
      curl_close($ch);

      return $response;
   }
   }
