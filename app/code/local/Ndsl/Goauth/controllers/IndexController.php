<?php
class Ndsl_Goauth_IndexController extends Mage_Core_Controller_Front_Action{
	
	//http://magelocal.com/goauth/
    public function indexAction() {
        $params = array(
            'siteUrl' => 'http://magelocal.com/goauth',
            'requestTokenUrl' => 'http://magelocal.com/oauth/initiate',
            'accessTokenUrl' => 'http://magelocal.com/oauth/token',
            'authorizeUrl' => 'http://magelocal.com/admin/oauth_authorize',
            'consumerKey' => '1234567890*****1212181288812',
            'consumerSecret' => '8238shjsd87*********',
            'callbackUrl' => 'http://magelocal.com/goauth/index/callback',
        );
 
      
        $consumer = new Zend_Oauth_Consumer($params);
        $requestToken = $consumer->getRequestToken();
        $session = Mage::getSingleton('core/session');
        $session->setRequestToken(serialize($requestToken));
        $consumer->redirect();
        return;
    }
 
	//http://magelocal.com/goauth/index/callback
    public function callbackAction() {
        //oAuth parameters
        $params = array(
            'siteUrl' => 'http://magelocal.com/',
            'requestTokenUrl' => 'http://magelocal.com/oauth/initiate',
            'accessTokenUrl' => 'http://magelocal.com/oauth/token',
            'consumerKey' => '1234567890*****1212181288812',
            'consumerSecret' => '8238shjsd87*********'
        );
        $session = Mage::getSingleton('core/session');
        $requestToken = unserialize($session->getRequestToken());
        $consumer = new Zend_Oauth_Consumer($params);
        $acessToken = $consumer->getAccessToken($_GET, $requestToken);
		Zend_Debug::dump($acessToken);
        $restClient = $acessToken->getHttpClient($params);
        $restClient->setUri('http://magelocal.com/api/rest/stockitems/1335');
		
		$xml = '<?xml version="1.0"?><magento_api><qty>99</qty></magento_api>';
        //$restClient->setHeaders('Accept', 'application/xml');
        $restClient->setHeaders('Accept', '*/*');
		$restClient->setHeaders('Content-Type','application/json');
		$restClient->setParameterPost($xml);
        
		
        $restClient->setMethod(Zend_Http_Client::PUT);
        $response = $restClient->request();
        Zend_Debug::dump($response->getBody());
        return;
    }
	
	public function rejectAction() {
		echo "this is called";
    }
}