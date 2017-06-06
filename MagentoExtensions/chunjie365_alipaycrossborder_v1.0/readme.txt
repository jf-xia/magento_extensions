How to install:
Just unzip to the root dir of Magento.

If you are not using the default theme, you should:
1. Copy app/design/frontend/default/default/template/needtool*/ to your template;
2. Copy skin/frontend/default/default/images/needtool_paylogo to your skin;

You should refresh magento cache to active the extension.

** You must set [Settlement Currency] if you use CNY price in the store
=====================================================================

Some mistake when install/config:

1.Fill the security code field with password of the alipay account. 
The security code is not password, please check the document from alipay.

2.Copy with blank from the document provided by alipay to the field.
eg: 
security code is:"ggq171icp1y767a2vn9mxu13bqto1c96"
but copied with :"ggq171icp1y767a2vn9mxu13bqto1c96 "
Please delete the blank from field in Configuration of Admin Panel.





如何安装：

	如果使用SSH登录：
		将zip包FTP上传到Magento的安装目录中，用命令行解压即可。

	如果只能使用FTP：
		在本地将zip包解压，FTP上传到Magento的安装目录中即可。

如果您使用自定义的模板或者皮肤，您需要：
1.将app/design/frontend/default/default/template/needtool*/目录拷贝到您自定义的界面模板目录下。
2.将skin/frontend/default/default/images/needtool_paylogo 目录拷贝到您自定义的皮肤目录下。

在以上步骤后，您需要刷新Magento的缓存，以使该插件可用。

============================================================================
常见问题:
1.密钥错填为密码: 应填写密钥,不是登录用的密码
2.密钥或其他栏位拷贝填写时含有空格: 拷贝时请注意删去首尾空格

***注意***
交易情况请以支付服务提供商的交易记录为准。
***免责声明***
由于网络及客户使用环境的复杂，本软件对Magento交易状态的更新仅供参考，作者及NEEDTOOL.COM不保证其正确性及准确性，亦不对因此造成的后果负责。
============================================================================
版权及权利说明：
您当前使用的是由NEEDTOOL.com开发的插件/软件(以下统称为软件)，用于Magento电子商务系统，您被授权使用该软件，但您不能将该软件：
1.免费或收费传播给第三方，无论其使用或者未使用；
2.进行解密和/或反编译操作，包括但不限于以下任何之目的：学习、研究、修改、发布；
2.修改代码后进行传播，无论免费或者收费，无论第三方使用或者未使用；
本软件（及包含的代码）不包含由作者或利拓NEEDTOOL.COM提供的技术支持服务，作者或利拓NEEDTOOL.COM并不保证其可用性；
本软件对Magento交易状态的更新仅供参考，作者或利拓NEEDTOOL.COM不保证其正确性及准确性，请以支付服务提供商的交易记录为准,作者或利拓NEEDTOOL.COM不对任何交易错误和/或交易纠纷负责；
本软件仅限于用于被授权之域名下;
如果您开始使用本软件，您即代表已同意本条款。
