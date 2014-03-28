<?php

namespace Gecky\Facebook;

class Facebook extends \Facebook
{	
	protected $appHost;
	protected $tabUrl;
	
	public function init($appHost, $tabUrl) 
	{
		$this->appHost = $appHost;
		$this->tabUrl = $tabUrl;
		
		//Tomo el access token desde el redirect de facebook
		$access_token = isset($_SESSION['fb_access_token'])?$_SESSION['fb_access_token']:null;
		if (!$access_token && $code = isset($_REQUEST["code"])?$_REQUEST["code"]:false)
		{
			$token_url="https://graph.facebook.com/oauth/access_token?client_id=".$this->getAppId()."&redirect_uri=" . urlencode($this->getAppHost())
			. "&client_secret=".$this->getAppSecret()."&code=" . $code . "&display=popup";
			$response = file_get_contents($token_url);
			$params = null;
			parse_str($response, $params);
			$access_token = $_SESSION['fb_access_token'] =  $params['access_token'];
		}
		$this->setAccessToken($access_token);
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
		return $this->getLoginUrl(array(
			'scope' => 'basic_info, email, user_birthday, publish_actions',
			'redirect_uri' => $this->appHost
		));
	}
}