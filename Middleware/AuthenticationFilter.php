<?php
require_once 'Slim/Slim.php';
\Slim\Slim::registerAutoloader();

class AuthenticationFilter extends \Slim\Middleware
{
    public function call()
    {
        $app = $this->app;
        $env = $app->environment();
        $req = $app->request();
        $res = $app->response();

	$httpMethod = $req->getMethod();
	$resourceUri = strtolower($req->getResourceUri());

	if($httpMethod == "POST" && ($resourceUri == '/restservices/volunteers' || $resourceUri == '/restservices/accesstoken')) {
		$this->next->call();
		return;
	}

	$authMgr = new AuthenticationMgr();
	$authToken = $req->headers('X-Authentication');
	$isValid = $authMgr->isValidToken($authToken);
	if($isValid) {
		$authenticatedUserId = $authMgr->getUserIdFromToken($authToken);
		$env['AuthenticatedUserId'] = $authenticatedUserId;

		$this->next->call();

		$newToken = $authMgr->createAccessTokenFromAccessToken($authToken);
		$res['X-Authentication'] = $newToken->access_token;
	}
	else {
		$res->status(401);
		$res->body("{\"error\": \"Invalid Authentication Token\"}");
	}
    }
}

