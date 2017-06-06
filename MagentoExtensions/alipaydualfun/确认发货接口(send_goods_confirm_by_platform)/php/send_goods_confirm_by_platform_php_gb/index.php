<?php
/* *
 * 功能：支付宝确认发货接口调试入口页面
 * 版本：3.2
 * 日期：2011-03-17
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 */

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<HEAD><TITLE>支付宝确认发货接口</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
</HEAD>
<BODY>
	<FORM name=alisubmit action=sendgoods.php method=post target="_blank">
		<div style="text-align: center; font-size: 9pt; font-family: 宋体">
                支付宝交易号：<INPUT type="text" size="30" name="trade_no" value=""><br />
                物流公司名称：<INPUT type="text" size="30" name="logistics_name" value=""><br />
                物流发货单号：<INPUT type="text" size="30" name="invoice_no" value=""><br />
              物流发货类型：<select name="transport_type">
                  <option value="EMS">EMS</option>
                  <option value="POST">平邮</option>
                  <option value="EXPRESS" selected="selected">快递</option>
                </select><br />
                <INPUT type="submit" value="确认"  name="btnAlipay">
		</div>
	</FORM>
</BODY>
</HTML>
