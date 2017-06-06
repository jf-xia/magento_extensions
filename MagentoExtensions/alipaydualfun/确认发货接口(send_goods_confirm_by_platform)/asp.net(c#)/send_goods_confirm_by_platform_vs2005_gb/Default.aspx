<%@ Page Language="C#" AutoEventWireup="true" CodeFile="default.aspx.cs" Inherits="_Default" %>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>支付宝确认发货接口</title>
</head>

<body>
    <form id="Form1" runat="server">
        <div style="text-align: center; font-size: 9pt; font-family: 宋体">
            确认发货接口<br />
            支付宝交易号：<asp:TextBox ID="Trade_no" runat="server" Text="默认值"></asp:TextBox><br />
            物流公司名称：<asp:TextBox ID="Logistics_name" runat="server" Text="默认值"></asp:TextBox><br />
           物流发货单号：<asp:TextBox ID="Invoice_no" runat="server" Text="默认值"></asp:TextBox><br />
            发货时的运输类型：<asp:TextBox ID="Transport_type" runat="server" Text="默认值"></asp:TextBox><br />
            <asp:Button ID="BtnAlipay" runat="server" Text="查 询 " OnClick="BtnAlipay_Click" /></div>
    </form>
</body>
</html>
