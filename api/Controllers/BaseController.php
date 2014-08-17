<?php
require_once 'Util/SerializationMgr.php';
require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

class BaseController
{
	private $statusCodeMessages = array(200=>"Successful",
				401=>"Authentication Failed",
				403=>"You are not authorized to access that resource",
				404=>"Not Found");

	public function getAuthenticatedUserId() {
		$app = \Slim\Slim::getInstance();

		$env = $app->environment();
		$authenticatedUserId = $env['AuthenticatedUserId'];

		return $authenticatedUserId;
	}

	public function getRequestBody() {
		$app = \Slim\Slim::getInstance();
		$request = $app->request();
		$body = $request->getBody();

		return $body;
	}

	public function deserializeRequestBody($objectType) {
		$body = $this->getRequestBody();

		$serializationMgr = new SerializationMgr();	
		$credentials = $serializationMgr->deserialize($body, $objectType);

		return $credentials;
	}

	public function sendResponse() {
		list($status, $object, $accessToken) = func_get_args();

		if(is_null($object)) {
			$statusCodeMessage = $this->statusCodeMessages[$status];
			$object = array(message=>$statusCodeMessage);
		}

		$app = \Slim\Slim::getInstance();
		$res = $app->response();
		$res->status($status);

		if(!is_null($accessToken)) {
			$res['X-Authentication'] = $accessToken;
		}

		$serializationMgr = new SerializationMgr();
		$json = $serializationMgr->serialize($object);

		$res->body($json);		
	}

}
