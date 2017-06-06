<?php
define('ZOPIM_THEMES_LIST', "http://zopim.com/assets/dashboard/themes/window/plugins-themes.txt");
define('ZOPIM_COLORS_LIST', "http://zopim.com/assets/dashboard/themes/window/plugins-colors.txt");

class Zopim_Livechat_Block_Customization extends Mage_Core_Block_Template
{
    private $zmodel;
	protected function _toHtml()
	{
      $this->zmodel = Mage::getModel('livechat/livechat')->load(1);
      $zoptions = $this->zmodel->_data;
      $greetings = Zend_JSON::Decode($zoptions['greetings']);

      if ($zoptions["code"] == "") {
         $zoptions["code"] = "zopim";
      }

      $html = "";
      if ($this->getRequest()->getParam('zopimColor')!="" || $this->getRequest()->getParam('zopimTheme')!="") {
         
         $this->zmodel->setGetvisitorinfo($this->checkbox_helper("zopimGetVisitorInfo"));
         $this->zmodel->setHideonoffline($this->checkbox_helper("zopimHideOnOffline"));
         $this->zmodel->setBubbleenable($this->checkbox_helper("zopimBubbleEnable"));

         $this->zmodel->setLang($this->getRequest()->getParam('zopimLang'));
         $this->zmodel->setPosition($this->getRequest()->getParam('zopimPosition'));
         
         $this->zmodel->setTheme($this->getRequest()->getParam('zopimTheme'));
         $this->zmodel->setBubbletitle($this->getRequest()->getParam('zopimBubbleTitle'));
         $this->zmodel->setBubbletext($this->getRequest()->getParam('zopimBubbleText'));
         $this->zmodel->setColor($this->getRequest()->getParam('zopimColor'));

         $greetings["online"]["window"] = stripslashes($this->getRequest()->getParam("zopimOnlineLong"));
         $greetings["away"]["window"] = stripslashes($this->getRequest()->getParam("zopimAwayLong"));
         $greetings["offline"]["window"] = stripslashes($this->getRequest()->getParam("zopimOfflineLong"));
         $greetings["online"]["bar"] = stripslashes($this->getRequest()->getParam("zopimOnlineShort"));
         $greetings["away"]["bar"] = stripslashes($this->getRequest()->getParam("zopimAwayShort"));
         $greetings["offline"]["bar"] = stripslashes($this->getRequest()->getParam("zopimOfflineShort"));

         $this->zmodel->setGreetings(Zend_JSON::Encode($greetings));

         $this->zmodel->save();
         $zoptions = $this->zmodel->_data;

         $html = '
         <div id="messagesbox"><ul class="messages"><li class="success-msg"><ul><li><div id="themessage">Settings saved. Thank you for customizing Zopim Live Chat!</div></li></ul></li></ul><br></div>
         ';
      }

//      print_r(Mage::app()->getLocale());
      
      $html .= "
      <!-- start of zopim live chat script -->
      <script type=\"text/javascript\">
document.write(unescape(\"%3cscript src='\" + document.location.protocol + \"//zopim.com/?".$zoptions["code"]."' charset='utf-8' type='text/javascript'%3e%3c/script%3e\"));
         var thisLocale = '".substr(Mage::app()->getLocale()->getLocaleCode(), 0, 2)."';
      </script>
         <!-- end of zopim live chat script -->";
      
      $html .= <<<EOT
   <script type="text/javascript">

   function updateWidget() {

      var lang = document.getElementById('zopimLang').options[ document.getElementById('zopimLang').options.selectedIndex ].value;
      if (lang == 'md') { lang = thisLocale; }
      \$zopim.livechat.setLanguage(lang);

      if (document.getElementById("zopimHideOnOffline").checked) {
         \$zopim.livechat.button.setHideWhenOffline(true);
      } else {
         \$zopim.livechat.button.setHideWhenOffline(false);
      }

      \$zopim.livechat.window.setColor(document.getElementById("zopimColor").value);
      \$zopim.livechat.window.setTheme(document.getElementById("zopimTheme").value);

      \$zopim.livechat.bubble.setTitle(document.getElementById("zopimBubbleTitle").value);
      \$zopim.livechat.bubble.setText(document.getElementById("zopimBubbleText").value);

      \$zopim.livechat.setGreetings({
         'online': [document.getElementById("zopimOnlineShort").value, document.getElementById("zopimOnlineLong").value],
            'offline': [document.getElementById("zopimOfflineShort").value, document.getElementById("zopimOfflineLong").value],
            'away': [document.getElementById("zopimAwayShort").value, document.getElementById("zopimAwayLong").value]
      });
   }

   function updatePosition() {

      var position = document.getElementById('zopimPosition').options[ document.getElementById('zopimPosition').options.selectedIndex ].value;
      \$zopim.livechat.button.setPosition(position);
   }

   function updateBubbleStatus() {
      if (document.getElementById("zopimBubbleEnable").checked) {
         \$zopim.livechat.bubble.show();
         \$zopim.livechat.bubble.reset();
      } else {
         \$zopim.livechat.bubble.hide();
      }
   }

   var timer;
   function updateSoon() {

      clearTimeout(timer);
      timer = setTimeout("updateWidget()", 300);
   }
   </script>
<style type="text/css">
td{
vertical-align:middle;}
}
.smallExplanation {
background:#FAFAFA;
color:#667788;
font-size:8pt;
line-height:13px;
margin:4px 0 0 0;
padding:8px;
display: inline-block;
}
.inputtextshort {
width:200px;
padding:2px;
}
.inputtext {
width:450px;
padding:2px;
}
.secthead {
border-bottom:1px solid #EEEEEE;
color:#8899AA;
font-size:13px;
line-height:21px;
}
.sethead {
	width:200px;
}
.swatch {
	float: left;
	width: 15px;
	height:20px;
}
.swatch:hover {
	background-image:url(http://www.zopim.com/static/images/colorselectbg.gif);
	cursor:pointer;
}
.sorry {
  color:#c33;
}
</style>
EOT;
      if ($zoptions['hideonoffline'] && $zoptions['hideonoffline']!="disabled") { $hideonoffline = "checked='checked'"; } else { $hideonoffline = ''; }

      $html .= '
         <form method="get" action="'.$this->curpageurl().'" id="customize_form">
         <div class="content-header" style="visibility: visible;">
					<h3 class="icon-head head-categories" style="background-image: url(https://zopim.com/assets/branding/zopim.com/chatman/online.png")>Customize your widget</h3><p class="content-buttons form-buttons" style=""><button style="" onclick="document.getElementById(\'customize_form\').submit()" class="scalable save" type="button" id="id_0b860228d9b3c83ba14a7ae8fed1a587"><span>Save Changes</span></button></p>
         </div><p>
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">General Settings</h4>
         </div>
         <div id="sales_report_base_fieldset" class="fieldset ">
         <div class="hor-scroll">
         <table cellspacing="0" class="form-list">
         <tbody>
         <tr>
         <td class="label" style="width:180px;">Language</td>
         <td class="value">
         <select name="zopimLang" id="zopimLang" onchange="updateWidget()">
         '.$this->generate_options($this->get_languages(), $zoptions["lang"]).'
         </selct>
         </td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Position</td>
         <td class="value">
         <select name="zopimPosition" id="zopimPosition" onchange="updatePosition()">
         '.$this->generate_options(array("br" => "Bottom Right", "bl" => "Bottom Left", "mr" => "Right", "ml" => "Left"), $zoptions["position"]).'
         </select>
         </td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Hide chat bar when offline</td>
         <td><input onchange="updateWidget()" type="checkbox" id="zopimHideOnOffline" name="zopimHideOnOffline" value="checked" '.$hideonoffline.' /> This prevents visitors from sending you offline messages</td>
         </tr>
         </tbody>
         </table>
         </div>
         </div>
         </div>
         ';

      $colorpick = "<div style='display:inline-block;border:11px solid #888;background:#888;color:#fee;'>";
      $colors = $this->curl_get_url(ZOPIM_COLORS_LIST);
      $colors = explode("\n", $colors);

      $i=0;
      foreach ($colors as $color) {
         $colorpick .= "<div class='swatch' style='background-color: $color;' onclick=\"document.getElementById('zopimColor').value='$color'; updateWidget();\">&nbsp</div>";
         if (++$i%40==0) {
            $colorpick .= "<br>";
         }
      }   
      $colorpick .= "<br><a href=# style='color:#ff8' onclick=\"document.getElementById('zopimColor').value=''; updateWidget();\">Restore default color</a></div>";
      
      $themeselect = '<select name="zopimTheme" id="zopimTheme" onchange="updateWidget()">';
      $themes = $this->curl_get_url(ZOPIM_THEMES_LIST);
      $themes = $this->valuekeys(explode("\n", $themes));
      ksort($themes); 

      $themeselect .= $this->generate_options($themes, $zoptions['theme']);
      $themeselect .= "</select> <a href='#' onclick='\$zopim.livechat.window.toggle();return false;'>View the Chat Panel</a> for changes";

      $html .= '
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Color & Theme Settings</h4>
         </div>
         <div id="sales_report_base_fieldset" class="fieldset ">
         <div class="hor-scroll">
         <table cellspacing="0" class="form-list">
         <tbody>
         <tr>
         <td class="label" style="width:180px; vertical-align:top;">
         <input type="hidden" id="zopimColor" name="zopimColor" value="'.$zoptions["color"].'">
         Color
         </td>
         <td class="value">
         '.$colorpick.'
         </td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Theme</td>
         <td class="value">'.$themeselect.'</td>
         </tr>
         </tbody>
         </table>
         </div>
         </div>

         </div>
         ';

      $bubbleCheck = "";
      if ($zoptions["bubbleenable"]!="disabled") {
         $bubbleCheck = "checked='checked'";
      }

      $html .= '
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Help Bubble Settings</h4>
         </div>
         <div id="sales_report_base_fieldset" class="fieldset ">
         <div class="hor-scroll">
         <table cellspacing="0" class="form-list">
         <tbody>
         <tr>
         <td class="label" style="width:180px;">Display Help Bubble</td>
         <td><input onchange="updateBubbleStatus()" type="checkbox" id="zopimBubbleEnable" name="zopimBubbleEnable" value="zopimBubbleEnable" '.$bubbleCheck.' /> Use this pretty chat bubble to grab attention!</td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Help Bubble Title</td>
         <td class="value">
         <input class="inputtextshort" name="zopimBubbleTitle" id="zopimBubbleTitle" onKeyup="updateSoon()" value="'.$zoptions["bubbletitle"].'"> <a href="#" onclick="updateBubbleStatus();">Refresh</a>
         </td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Help Bubble Message</td>
         <td class="value">
         <input class="inputtext" name="zopimBubbleText" id="zopimBubbleText" onKeyup="updateSoon()" value="'.$zoptions["bubbletext"].'">
         </td>
         </tr>
         </tbody>
         </table>
         </div>
         </div>
         </div>
         </div>
         ';

      $html .= '
         <div class="entry-edit">
         <div class="entry-edit-head">
         <h4 class="icon-head head-edit-form fieldset-legend">Greeting Message Settings</h4>
         </div>
         <div id="sales_report_base_fieldset" class="fieldset ">
         <div class="hor-scroll">
         <table cellspacing="0" class="form-list">
         <tbody>
    	 <tr><td colspan="2"><div class="secthead">Message Shown on Chat Bar</div></td></tr>
         <tr>
         <td class="label" style="width:180px;">Online</td>
         <td class="value">
         <input class="inputtextshort" name="zopimOnlineShort" id="zopimOnlineShort" onKeyup="updateSoon()" value="'.$greetings["online"]["bar"].'">
         </td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Away</td>
         <td class="value">
        <input class="inputtextshort" name="zopimAwayShort" id="zopimAwayShort" onKeyup="updateSoon()"  value="'.$greetings["away"]["bar"].'">
         </td>
         </tr>
         <tr>
         <td class="label" style="width:180px;">Offline</td>
         <td class="value">
        <input class="inputtextshort" name="zopimOfflineShort" id="zopimOfflineShort" onKeyup="updateSoon()" value="'.$greetings["offline"]["bar"].'">
         </td>
         </tr>
    	 <tr><td colspan="2"><div class="secthead">Message Shown on Chat Panel</div></td></tr>
         <tr>
         <td class="label" style="width:180px;vertical-align:top;">Online</td>
         <td style="padding:5px">
         <textarea class="inputtext" name="zopimOnlineLong" id="zopimOnlineLong" onKeyup="updateSoon()">'.$greetings["online"]["window"].'</textarea>
         </td>
         </tr>
         <tr>
         <td class="label" style="width:180px;vertical-align:top;">Away</td>
         <td style="padding:5px">
         <textarea class="inputtext" name="zopimAwayLong" id="zopimAwayLong" onKeyup="updateSoon()">'.$greetings["away"]["window"].'</textarea>
         </td>
         </tr>
         <tr>
         <td class="label" style="width:180px;vertical-align:top;">Offline</td>
         <td style="padding:5px">
         <textarea class="inputtext" name="zopimOfflineLong" id="zopimOfflineLong" onKeyup="updateSoon()">'.$greetings["offline"]["window"].'</textarea>
         </td>
         </tr>
         </tbody>
         </table>
         </div>
         </div>

         ';

      $html .= '
         <div align="right">
         <button style="" onclick="document.getElementById(\'customize_form\').submit()" class="scalable save" type="button" id="id_0b860228d9b3c83ba14a7ae8fed1a587"><span>Save Changes</span></button></div><br>
         <script language="javascript">
            updateWidget();
            updatePosition();
         </script>
         ';
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

   private function generate_options($options, $current) {

      $out = "";
      foreach ($options as $key => $value) {

         if ($value != "") {
            $isselected = "";
            if ($current == $key) {
             $isselected = "selected";
            }
            $out .= '<option value="'.$key.'" '.$isselected.'>'.$value.'</option>';
         }
      }

      return $out;
   }

   private function get_languages() {

      $langjson = '{"--":" - Auto Detect - ","md":" - Magento Locale Detection - ","ar":"Arabic","bg":"Bulgarian","cs":"Czech","da":"Danish","de":"German","en":"English","es":"Spanish; Castilian","fa":"Persian","fo":"Faroese","fr":"French","he":"Hebrew","hr":"Croatian","id":"Indonesian","it":"Italian","ja":"Japanese","ko":"Korean","ms":"Malay","nb":"Norwegian Bokmal","nl":"Dutch; Flemish","pl":"Polish","pt":"Portuguese","ru":"Russian","sk":"Slovak","sl":"Slovenian","sv":"Swedish","th":"Thai","tr":"Turkish","ur":"Urdu","vi":"Vietnamese","zh_CN":"Chinese (China)"}';

      return Zend_Json::decode($langjson);
   }

   private function curl_get_url($filename) {

      $ch = curl_init();
      $timeout = 5; // set to zero for no timeout
      curl_setopt ($ch, CURLOPT_URL, $filename);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
      $file_contents = curl_exec($ch);
      curl_close($ch);

      return $file_contents;
   }
   
   private function valuekeys($array) {

      $newarray = array();
      foreach ($array as $s) {
         $newarray[$s] = $s;
      }

      return $newarray;
   }

   private function checkbox_helper($fieldname) {

      $val = $this->getRequest()->getParam($fieldname);
      if (isset($val) && $val != "") {
         return $this->getRequest()->getParam($fieldname);
      } else {
         return "disabled";
      }
   }
}
