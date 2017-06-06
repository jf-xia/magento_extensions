<?php   
class Topbuy_Customercare_Block_Index extends Mage_Core_Block_Template{   

    public function __construct()
    {
        parent::__construct();
//        $this->addData(array(
//            'cache_lifetime'    => "3600",
//            'cache_tags'        => array("Topbuy_Customercare"),
//        ));

        $collection = Mage::getModel('customercare/pccomments')->getCollection();
        $this->setCollection($collection);
    }
 
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
 
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(10=>10,20=>20,30=>30,'all'=>'all'));
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection();//->setOrder('pccomm_editeddate', 'ASC')->load();
        return $this;
    }
 
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
    
    public function getPcMessageList($idparent=0,$idstore=99){
        $session = Mage::getSingleton("customer/session");
        if(!$session->isLoggedIn()){
            $session->setBeforeAuthUrl(Mage::helper("core/url")->getCurrentUrl());
            $this->_redirect('customer/account/login');	
        }
        if($session->isLoggedIn()){
            $customerId = $session->getCustomer()->getId();
            $pcftypeorder=$this->getPcFTypeOrder();
            $pcftypeno=$this->getPcFTypeNo();
            //substr($pcftype[$_item->getPccommFtype()],0,strrpos($pcftype[$_item->getPccommFtype()],'|'))
            $pcftype=$pcftypeno+$pcftypeorder;
            $pcfstatu=$this->getPcFStatu();
            $commentMessages='';
            $pccomments=$this->getCollection();
            $pccomments->getSelect()->where('pccomm_idmaguser=?', $customerId)->where('pccomm_idparent=?', $idparent)->where('idstore=?', $idstore)->order('pccomm_createddate DESC'); 
            foreach ($pccomments as $_item){
           
                $commentMessages.='<tr class="odd"><td width="5%"><a href="'.Mage::getBaseUrl().'customercare/index/detail/idparent/'.$_item->getIdpccomments().'">'.$_item->getIdpccomments().'</a></td>';
                $commentMessages.='<td width="40%" style="text-align:left"><a href="'.Mage::getBaseUrl().'customercare/index/detail/idparent/'.$_item->getIdpccomments().'">'.$_item->getPccommDescription().'</a></td>';
                if(isset($pcftype[$_item->getPccommFtype()]))
                    {$commentMessages.="<td width='15%'>".substr($pcftype[$_item->getPccommFtype()],0,strrpos($pcftype[$_item->getPccommFtype()],'|'))."</td>";}
                $commentMessages.="<td width='10%'>".date('m-d-Y',strtotime($_item->getPccommEditeddate()))."</td>";
                $commentMessages.="<td width='5%'>".$pcfstatu[$_item->getPccommFstatus()]."</td>";
                $commentMessages.="<td width='10%'>".$_item->getPccommIdmagorder()."</td>";
                $commentMessages.='<td width="5%"><a href="'.Mage::getBaseUrl()."customercare/index/detail/idparent/".$_item->getIdpccomments().'" ><img src="'.$this->getSkinUrl().'images/layout/account_icon_view.png"></a></td></tr>';
            }
        }
        return $commentMessages;
    } 
    
    public function getCRankTime($idparent){
        $rankTime=Mage::getModel('customercare/pccommentsrank')->getCollection()
                ->addFilter("id_mag_pccomments",$idparent)
                ->addFilter("rank_type",1);
        return $rankTime->getLastItem()->getRankPoint();
    }
    
    public function getCRankQuality($idparent){
        $rankQuality=Mage::getModel('customercare/pccommentsrank')->getCollection()
                ->addFilter("id_mag_pccomments",$idparent)
                ->addFilter("rank_type",2);
        return $rankQuality->getLastItem()->getRankPoint();
    }
    
    public function getPcMegDetailList($idparent=0){
        $idparent  = $this->getRequest()->getParam('idparent');
        $pccommentList = "";
        $pccommentLists = Mage::getModel('customercare/pccomments')->getCollection();
        $pccommentLists->getSelect()->where('pccomm_idmagparent=?', $idparent)->order('pccomm_editeddate ASC');

        foreach ($pccommentLists as $_item) {
            $pccommentList.='<li>';
            if (!$_item->getPccommPriority()){
            	$pccommentList.='<h4 class="who"><strong>You</strong> said:</h4>';            	
            } else {
            	$pccommentList.='<h4 class="who"><strong>Customer Service Representative</strong> said:</h4>';            	
            }
            $pccommentList.='<h4 class="when"><strong>';
            $pccommentList.=date("F j, Y, g:i a", strtotime($_item->getPccommEditeddate()));
            $pccommentList.='</strong></h4><div class="clear"></div>';
            $pccommentList.="<div>".$_item->getPccommDetails()."</div>";//.", Responsed by " . $pcCCare[pccomm_priority];
            
//              Mage::log($_item->getPccommIdmaguser());
            if ($_item->getPccommPriority()){
                $pccommentList.="<b><div style='margin-bottom:5px;'><span class='f11 red'>Rank Our Service: </span></b> &nbsp;&nbsp;&nbsp;&nbsp;(1 Unsatisfied, 3 Average, 5 Excellent)</div>";
                $pccommentList.="Response Time: ";
                //checked
                for($i=(int)1;$i<6;$i++){
                    if ($i==$this->getCRankTime($_item->getIdpccomments())) {
                        $pccommentList.="<input type='radio' checked name='rank_time".$_item->getIdpccomments()."' onclick='Message_Call.submitRank(1,".$_item->getIdpccomments().",".$i.")' value='".$i."'>".$i;
                    } else {
                        $pccommentList.="<input type='radio' name='rank_time".$_item->getIdpccomments()."' onclick='Message_Call.submitRank(1,".$_item->getIdpccomments().",".$i.")' value='".$i."'>".$i;
                    }
                }
                $pccommentList.="<br />Response Quality: ";
                for($i=1;$i<6;$i++){
                    if ($i==$this->getCRankQuality($_item->getIdpccomments())) {
                        $pccommentList.="<input type='radio' checked name='rank_quality".$_item->getIdpccomments()."' onclick='Message_Call.submitRank(2,".$_item->getIdpccomments().",".$i.")'  value='".$i."'>".$i;
                    } else {
                        $pccommentList.="<input type='radio' name='rank_quality".$_item->getIdpccomments()."' onclick='Message_Call.submitRank(2,".$_item->getIdpccomments().",".$i.")'  value='".$i."'>".$i;
                    }
                }
                $pccommentList.="<div style='margin-top:5px; color:Green;' id='rankReturn".$_item->getIdpccomments()."' ></div>";                
            }
            $pccommentList.="</li>";
        }
        return $pccommentList;
    }
    
    
    
    
    public function getPcMessageDetail($idfeedback=0){
        $idfeedback  = $this->getRequest()->getParam('idparent');
        $pcftypeorder=$this->getPcFTypeOrder();
        $pcftypeno=$this->getPcFTypeNo();
        $pcftype=$pcftypeno+$pcftypeorder;
        $pcfstatu=$this->getPcFStatu();
        //$pcCCare=$this->getPcCCare();
        $pccommentDetail=array();
        $pccomments=Mage::getModel('customercare/pccomments')->getCollection();
        $pccomments->getSelect()->where('idpccomments=?',$idfeedback); 
        foreach ($pccomments as $_item){
            $pccommentDetail["idfeedback"]=$_item->getIdpccomments();
            $pccommentDetail["pccommDescription"]=$_item->getPccommDescription();
            $pccommentDetail["pccommDetails"]=$_item->getPccommDetails();
            $pccommentDetail["pccommFtype"]=substr($pcftype[$_item->getPccommFtype()],0,strrpos($pcftype[$_item->getPccommFtype()],'|'));
            $pccommentDetail["pccommCreateddate"]=date("F j, Y, g:i a",strtotime($_item->getPccommCreateddate()));
            $pccommentDetail["pccommFstatus"]=$pcfstatu[$_item->getPccommFstatus()];
        //    $pccommentDetail["pccommIdmagorder"]=$_item->getPccommIdmagorder();
        //    $pccommentDetail["pccommIdorder"]=$_item->getPccommIdorder();
            if ($_item->getPccommIdmaguser()==0&&$_item->getPccommIduser()==0){
                $pccommentDetail["pccommRank"]="<b><div style='margin-bottom:5px;'><span class='f11 red'>Rank Our Service: </span></b> &nbsp;&nbsp;&nbsp;&nbsp;(1 Unsatisfied, 3 Average, 5 Excellent)</div>";
                $pccommentDetail["pccommRank"].="Response Time: ";
                //checked
                for($i=1;$i<6;$i++){
                    if ($i==$this->getCRankTime($_item->getIdpccomments())) {
                        $pccommentDetail["pccommRank"].="<input type='radio' checked name='rank_time".$_item->getIdpccomments()."' onclick='Message_Call.submitRank(1,".$_item->getIdpccomments().",".$i.")' value='".$i."'>".$i."&nbsp;&nbsp;&nbsp;&nbsp;";
                    } else {
                        $pccommentDetail["pccommRank"].="<input type='radio' name='rank_time".$_item->getIdpccomments()."' onclick='Message_Call.submitRank(1,".$_item->getIdpccomments().",".$i.")' value='".$i."'>".$i."&nbsp;&nbsp;&nbsp;&nbsp;";
                    }
                }
                $pccommentDetail["pccommRank"].="Response Quality: ";
                for($i=1;$i<6;$i++){
                    if ($i==$this->getCRankQuality($_item->getIdpccomments())) {
                        $pccommentDetail["pccommRank"].="<input type='radio' checked name='rank_quality".$_item->getIdpccomments()."' onclick='Message_Call.submitRank(2,".$_item->getIdpccomments().",".$i.")'  value='".$i."'>".$i."&nbsp;&nbsp;";
                    } else {
                        $pccommentDetail["pccommRank"].="<input type='radio' name='rank_quality".$_item->getIdpccomments()."' onclick='Message_Call.submitRank(2,".$_item->getIdpccomments().",".$i.")'  value='".$i."'>".$i."&nbsp;&nbsp;";
                    }
                }
                $pccommentDetail["pccommRank"].="<div style='margin-top:5px; color:Green;' id='rankReturn".$_item->getIdpccomments()."' ></div>";                
            }
        }
        return $pccommentDetail;
    }

    public function getPcMessageCount($idparent=0,$idstore=99){
        $session = Mage::getSingleton("customer/session");
        $customerId = $session->getCustomer()->getId();
        $pccomments=Mage::getModel('customercare/pccomments')->getCollection();
        $pccomments->getSelect()->where('pccomm_idmaguser=?', $customerId)->where('pccomm_idparent=?', $idparent)->where('idstore=?', $idstore); 
        
        return $pccomments->count();
    }
    
    public function getPcFTypeOrder(){
        $pcftypeorder=array();
        $pcftypes=Mage::getModel('customercare/pcftypes')->getCollection();
        $pcftypes->getSelect()->order('pcftype_name ASC');
        foreach ($pcftypes as $_item){
            if ($_item->getDisplaytype()==1||$_item->getDisplaytype()==0) $pcftypeorder[$_item->getPcftypeIdtype()]=$_item->getPcftypeName()."|".$_item->getManager();
        }
        return $pcftypeorder;
    }
    
    public function getPcFTypeNo(){
        $pcftypeno=array();
        $pcftypes=Mage::getModel('customercare/pcftypes')->getCollection();
        $pcftypes->getSelect()->order('pcftype_name ASC');
        foreach ($pcftypes as $_item){
            if ($_item->getDisplaytype()==2||$_item->getDisplaytype()==0) $pcftypeno[$_item->getPcftypeIdtype()]=$_item->getPcftypeName()."|".$_item->getManager();
        }
        return $pcftypeno;
    }

    public function getPcFStatu(){
        $pcfstatu=array();
        $pcfstatus=Mage::getModel('customercare/pcfstatus')->getCollection();
        foreach ($pcfstatus as $_item){
            $pcfstatu[$_item->getPcfstatIdstatus()]=$_item->getPcfstatName();
        }
        return $pcfstatu;
    }

    public function getPcCCare(){
        $pcCCare=array();
        $pcpriority=Mage::getModel('customercare/pcpriority')->getCollection();
        foreach ($pcpriority as $_item){
            $pcCCare[$_item->getPcpriIdpri()]=$_item->getPcpriName();
        }
        return $pcCCare;
    }
    
}