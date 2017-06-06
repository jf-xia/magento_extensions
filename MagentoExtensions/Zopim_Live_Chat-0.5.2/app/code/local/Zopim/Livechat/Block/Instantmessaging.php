<?php
define('ZOPIM_BASE_URL', "https://www.zopim.com/");
define('ZOPIM_IM_LOGOS', "https://www.zopim.com/static/images/im/");
define('ZOPIM_IMINFO_URL', ZOPIM_BASE_URL."plugins/getImSetupInfo");
define('ZOPIM_IMREMOVE_URL', ZOPIM_BASE_URL."plugins/removeImSetup");
class Zopim_Livechat_Block_Instantmessaging extends Mage_Adminhtml_Block_Widget
{
   private $zmodel;

   protected function _toHtml()
   {
      $this->zmodel = Mage::getModel('livechat/livechat')->load(1);
      $zoptions = $this->zmodel->_data;
      $salt = array('salt' => $zoptions["salt"]);
         
      // this removes im bots
      if ($this->getRequest()->getParam('remove')=="yes") {

         $this->do_post_request(ZOPIM_IMREMOVE_URL, $salt);
         $html = '<div id="messagesbox"><ul class="messages"><li class="success-msg"><ul><li><div id="themessage">IM integration has been removed.</div></li></ul></li></ul><br></div>';
      }

      $iminfo = Zend_Json::decode($this->do_post_request(ZOPIM_IMINFO_URL, $salt));

      $html = '
         <div class="content-header" style="visibility: visible;">
         <table cellspacing="0">
         <tbody><tr>
         <td style="width: 50%;"><h3 class="icon-head head-categories" style="background-image: url(http://zopim.com/assets/branding/zopim.com/chatman/online.png")>Instant Messaging Integration</h3></td>
         </tr>
         </tbody></table>
         </div><p>
         ';
      if (isset($iminfo["bots"])) {
         $html .= "
   ";
      // .$this->getRequest()->getParam('abc')
      $html .= '
 <style>
    td {vertical-align:middle;}
    .clients td.first {border:none;background:#888;color:#fff;}
    .steps {width:100%}
    .steps td {background:#f9f9f9;padding:15px;}
    .clients td {padding:8px;border-top:1px solid #dfdfdf;background:#fff;}
    .clients {border:1px solid #dfdfdf;background:#fff}
    .explain {
background:#FAFAFA;
color:#667788;
font-size:8pt;
line-height:13px;
margin:4px 0 0 0;
padding:8px 3px;
display: inline-block;
}
 </style>
         <div style="" id="existingform">
         <div>
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Step 1: Add Control Bot to the IM Client of Choice</h4>
         </div>
         <div class="fieldset " id="sales_report_base_fieldset">
         <div class="hor-scroll">
    <table class="clients" cellpadding="0" cellspacing="0">
    <tr><td align="center" width="160" class="first"><b>IM Cient</b></td><td class="first" width="200"><b>Chat Bot Name</b></td></tr>
    <tr><td valign="center" align="center"><img src="'.ZOPIM_IM_LOGOS.'big/gtalk.png"></td><td>'.$iminfo["bots"]["gtalk"].'</td></tr>
    <tr><td valign="center" align="center"><img src="'.ZOPIM_IM_LOGOS.'big/msn.png"></td><td>'.$iminfo["bots"]["msn"].'</td></tr>
    <tr><td valign="center" align="center"><img src="'.ZOPIM_IM_LOGOS.'big/yahoo.png"></td><td>'.$iminfo["bots"]["yahoo"].'</td></tr>
    <tr><td valign="center" align="center"><img src="'.ZOPIM_IM_LOGOS.'big/aim.png"></td><td>'.$iminfo["bots"]["aim"].'</td></tr>
    </table>
    <div class="explain">For example, to use <b>MSN Live Messenger</b> to chat,<br/>add <b>'.$iminfo["bots"]["msn"].'</b> to your MSN contact list.</div>
         </p></div>
         </div>
         </div></div>
         </div>
         <div style="" id="existingform">
         <div>
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Step 2: Send Setup Message to Control Bot</h4>
         </div>
         <div class="fieldset " id="sales_report_base_fieldset">';
      $html .= "
         <iframe id=\"zopim_bot_clipboard\" height=100px frameborder=0 width=100%></iframe>
         <script language=\"javascript\">
var ifd = document.getElementById('zopim_bot_clipboard').contentWindow.document;
ifd.open();
ifd.write ('<body style=\"margin:0px;\"><div class=\"hor-scroll\" style=\"font-size:12px;font-family: arial;\">Send the Control Bot this message:<br/><br/><input style=\"font-size:31px;color:#555;margin:0 0 5px;width:380px;\" type=\"text\" value=\"#setup ".$iminfo["auth_key"]."\" id=\"box-content\" readonly></input><br/><input id=\"copy\" value=\"Copy to Clipboard\" type=\"button\"></input>');
var jss2 = document.createElement('script');
jss2.type = 'text/javascript';
jss2.src = 'http://www.zopim.com/static/ZeroClipboard.js';
ifd.body.appendChild(jss2);

var jss = document.createElement('script');
jss.type = 'text/javascript';
jss.innerHTML = 'ZeroClipboard.setMoviePath(\'http://www.zopim.com/static/ZeroClipboard.swf\'); var clip = new ZeroClipboard.Client(); function $(id) { return document.getElementById(id); } clip.addEventListener(\'mousedown\',function() {  clip.setText(document.getElementById(\'box-content\').value); clip.glue(\'copy\'); ';
ifd.body.appendChild(jss);
ifd.close();
</script>
         ";
            $html .= '
         </p></div>
         </div>
         </div></div>
         </div>
         <div style="" id="existingform">
         <div>
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Step 3: Accept the invitations to add the chat bots</h4>
         </div>
         <div class="fieldset " id="sales_report_base_fieldset">
         Depending on the number of Chat Bots available in your Package, you may need to accept up to 8 invitations. You\'re done! <p>
         </p></div>
         </div>
         </div></div>
         </div>
         ';

      } else if (isset($iminfo["status"])) {
      $html .= '
         <div style="" id="existingform">
         <div>
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Successfully linked Zopim with your '.$iminfo["protocol"].' account.</h4>
         </div>
         <div class="fieldset " id="sales_report_base_fieldset">
         <div class="hor-scroll">
         <br>
         <span style="float:right; line-height: 40px"><a href="'.$this->curpageurl().'?remove=yes">Remove IM Integration</a></span>
         <div style="display: inline; line-height: 20px; ">
         <span style="float:left;"><img src="'.ZOPIM_IM_LOGOS.'big/'.$iminfo["protocol"].'.png"></img></span>
            <div style="margin-left: 150px; margin-top: 5px;"> 
            You are connected using the account: '.$iminfo["username"].'.<br> 
            Your status is now <b>'.$iminfo["status"].'</b>.</div>
         </div>
         <br><br>
         </p></div>
         </div>
         </div></div>
         </div>';
      } else {
         $html .= '
         <div id="messagesbox"><ul class="messages"><li class="error-msg"><ul><li><div id="themessage">Please configure your account in the account configuration page before setting up chat bots.</div></li></ul></li></ul><br></div>
         ' ;
      }
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
