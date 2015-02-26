<?php

namespace Gecky\Facebook;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequestException;
use Facebook\FacebookRequest;
use Facebook\FacebookPageTabHelper;

class Facebook
{	
	protected $appHost;
	protected $tabUrl;
	protected $loginScope;
	
	public function __construct($appId, $appSecret, $appHost, $tabUrl, $loginScope = 'basic_info, email, user_birthday') 
	{
		$this->appHost = $appHost;
		$this->tabUrl = $tabUrl;
		$this->loginScope = $loginScope;
		
		FacebookSession::setDefaultApplication($appId, $appSecret);
	}
	
	public function getAppHost()
	{
		return $this->appHost;			
	}
	
	public function getTabUrl()
	{
		return $this->tabUrl;
	}
	
	public function getConfiguredLoginUrl()
	{
		$helper = new FacebookRedirectLoginHelper($this->appHost);
		return $helper->getLoginUrl($this->loginScope);
	}
	
	public function getUserId()
	{
		try {
			$session = $this->getFacebookSession();
			if($session)
			{
				return $session->getSessionInfo()->asArray()['user_id'];
			}
		} catch(\Exception $ex) {
			//echo $ex->getMessage();
		}	
		
		return null;
	}
	
	public function api($path, $method, $parameters = null)
	{
		try {
			$session = $this->getFacebookSession();
			
			$request = new FacebookRequest($session, 'GET', '/me', $parameters);
			$response = $request->execute();
			$graphObject = $response->getGraphObject();
			
			return $graphObject->asArray();
		} catch(FacebookRequestException $ex) {
			// When Facebook returns an error
			throw $ex;
		} catch(\Exception $ex) {
			// When validation fails or other local issues
			throw $ex;
		}
	}
	
	public function isOnPageTab()
	{
		$tabHelper = new FacebookPageTabHelper();
		
		return $tabHelper->getPageId();
	}
	
	public function getFacebookSession()
	{
		$helper = new FacebookRedirectLoginHelper($this->appHost);
		$session = null;
				
		try 
		{
			$session = $helper->getSessionFromRedirect();
			if(!$session && isset($_SESSION['myfb_access_token']))
			{
				$session = new FacebookSession($_SESSION['myfb_access_token']);
			}
		} catch(FacebookRequestException $ex) {
			// When Facebook returns an error
			throw $ex;
		} catch(\Exception $ex) {
			// When validation fails or other local issues
			throw $ex;
		}
		
		if($session)
		{
			$_SESSION['myfb_access_token'] = $session->getToken();
		}
		
		return $session;
	}
}