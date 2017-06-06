<?php
class CommerceStack_CsNotification_Model_AdminNotification_Feed extends Mage_AdminNotification_Model_Feed
{
    public function getFeedData()
    {
        try 
        {
            $xml = parent::getFeedData();
            if ($xml === false) 
            {
                return false;
            }
        
            $server = Mage::getModel('csapiclient/server');
            $server->curl_opts[CURLOPT_TIMEOUT] = 3; // Don't hang login if server is down
            
            $endPoint = Mage::getStoreConfig('system/csnotification/api/notification_uri');

            // Get recommender version (this copies code in the Recommender module and should
            // be replaced in a module-agnostic way).
            $modules = (array)Mage::getConfig()->getNode('modules')->children();
            $recommenderVersion = "null";

            if(isset($modules['CommerceStack_Recommender']))
            {
                $module = $modules['CommerceStack_Recommender'];
                $recommenderVersion = (string)$module->version;
            }

            $endPoint .= '?recommender_version=' . $recommenderVersion;
            
            $commercestackXml = $server->get($endPoint, false);
            
            if(!$commercestackXml) return $xml;
            $commercestackXml = simplexml_load_string($commercestackXml);
            foreach($commercestackXml as $notification)
            {
                $item = $xml->channel->addChild('item');
                foreach($notification as $name => $value)
                {
                    $item->addChild($name, $value);
                }
            }
        }
        catch(Exception $e)
        {
            // Swallow and continue. Don't do anything fancy here and risk breaking the Admin login
        }
        
        return $xml;
    }
}