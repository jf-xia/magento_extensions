using System;
using System.Data;
using System.Configuration;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;
using System.Collections.Generic;
using Com.Alipay;
using System.Xml;
using System.Text;

/// <summary>
/// 功能：确认发货接口接入页
/// 版本：3.2
/// 日期：2011-03-11
/// 说明：
/// 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
/// 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。
/// 
/// /////////////////注意///////////////////////////////////////////////////////////////
/// 如果您在接口集成过程中遇到问题，可以按照下面的途径来解决
/// 1、商户服务中心（https://b.alipay.com/support/helperApply.htm?action=consultationApply），提交申请集成协助，我们会有专业的技术工程师主动联系您协助解决
/// 2、商户帮助中心（http://help.alipay.com/support/232511-16307/0-16307.htm?sh=Y&info_type=9）
/// 3、支付宝论坛（http://club.alipay.com/read-htm-tid-8681712.html）
/// 
/// 如果不想使用扩展功能请把扩展功能参数赋空值。
/// </summary>
public partial class _Default : System.Web.UI.Page 
{
    protected void Page_Load(object sender, EventArgs e)
    {
    }

 

    protected void BtnAlipay_Click(object sender, EventArgs e)
    {
        ////////////////////////////////////////////请求参数////////////////////////////////////////////

        //必填参数//

        //支付宝交易号，支付宝根据商户请求，创建订单生成的支付宝交易号。
        string trade_no = Trade_no.Text.Trim();
      

        //物流公司名称，物流公司名称
        string logistics_name = Logistics_name.Text.Trim();


        //物流发货单号
        string invoice_no = Invoice_no.Text.Trim();


        //物流发货时的运输类型，三个值可选：POST（平邮）、EXPRESS（快递）、EMS（EMS）
        string transport_type = Transport_type.Text.Trim();
        //建议与创建交易时选择的运输类型一致

        ////////////////////////////////////////////////////////////////////////////////////////////////

        //把请求参数打包成数组
        SortedDictionary<string, string> sParaTemp = new SortedDictionary<string, string>();
        sParaTemp.Add("trade_no", trade_no);
        sParaTemp.Add("logistics_name", logistics_name);
        sParaTemp.Add("invoice_no", invoice_no);
        sParaTemp.Add("transport_type", transport_type);
      

        //构造确认发货接口，无需修改
        Service ali = new Service();
        XmlDocument xmlDoc = ali.Send_goods_confirm_by_platform(sParaTemp);

        //请在这里加上商户的业务逻辑程序代码

        //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

        //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

        StringBuilder sbxml = new StringBuilder();
        string nodeIs_success = xmlDoc.SelectSingleNode("/alipay/is_success").InnerText;
        if (nodeIs_success != "T")//请求不成功的错误信息
        {
            sbxml.Append("错误：" + xmlDoc.SelectSingleNode("/alipay/error").InnerText);
        }
        else//请求成功的支付返回宝处理结果信息
        {
            sbxml.Append(xmlDoc.SelectSingleNode("/alipay/response").InnerText);
        }



        Response.Write(sbxml.ToString());

        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    }
}
