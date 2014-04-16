<?php

namespace Gecky\Facebook;

class Facebook extends \Facebook
{	
	protected $appHost;
	protected $tabUrl;
	protected $loginScope;
	
	public function init($appHost, $tabUrl, $loginScope = 'basic_info, email, user_birthday') 
	{
		$this->appHost = $appHost;
		$this->tabUrl = $tabUrl;
		$this->loginScope = $loginScope;
		
		//Tomo el access token desde el redirect de facebook
		$access_token = isset($_SESSION['fb_access_token'])?$_SESSION['fb_access_token']:null;
		if ($code = isset($_REQUEST["code"])?$_REQUEST["code"]:false)
		{
			$token_url="https://graph.facebook.com/oauth/access_token?client_id=".$this->getAppId()."&redirect_uri=" . urlencode($this->getAppHost())
			. "&client_secret=".$this->getAppSecret()."&code=" . $code . "&display=popup";
			$response = @file_get_contents($token_url);
			if($response)
			{
				$params = null;
				parse_str($response, $params);
				$access_token = $_SESSION['fb_access_token'] =  $params['access_token'];
			}
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
			'scope' => $this->loginScope,
			'redirect_uri' => $this->appHost
		));
	}
}