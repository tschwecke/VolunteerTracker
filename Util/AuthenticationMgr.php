<?php

class AuthenticationMgr {

	private $TOKEN_SECRET_KEY = "CC18F8C9-B109-4EF6-8C8B-E9C57D737F3E";
        private $TOKEN_EXPIRATION_MINUTES = 60;
        private $TOKEN_DELIMITER = "|";

	public function createPasswordHash($password, $salt) {
		$hash = $this->computeHash($password . $salt);
		return $hash;
	}

	public function createAccessTokenFromId($id) {
		$expiration = time() + ($this->TOKEN_EXPIRATION_MINUTES*60);

		$token = $id . $this->TOKEN_DELIMITER . $expiration . $this->TOKEN_DELIMITER;
		$hash = $this->computeHash($token . $this->TOKEN_SECRET_KEY);
		$token .= $hash;

		$accessToken = new DomainObject('AccessToken', array("access_token" => $token, "expiration" => $expiration));
		return $accessToken;

	}

	public function createAccessTokenFromAccessToken($accessToken) {
		if(!$this->isValidToken($accessToken)) {
			return null;
		}

		$id = $this->getUserIdFromToken($accessToken);

		$newAccessToken = $this->createAccessTokenFromId($id);
		return $newAccessToken;

	}

        public function isValidToken($accessToken)
        {
		$tokenParts = explode($this->TOKEN_DELIMITER, $accessToken);
		if (count($tokenParts) != 3) {
			return false;
		}
		$hash = $tokenParts[2];

		$token = $tokenParts[0] . $this->TOKEN_DELIMITER . $tokenParts[1] . $this->TOKEN_DELIMITER;
		$computedHash = $this->computeHash($token . $this->TOKEN_SECRET_KEY);

		if ($hash != $computedHash) {
			return false;
		}

		$tokenExpiration = intval($tokenParts[1]);

		if ($tokenExpiration < $currentTime) {
			return false;
		}

		return true;
        }

        public function getUserIdFromToken($accessToken) {
            $tokenParts = explode($this->TOKEN_DELIMITER, $accessToken);
            $userId = intval($tokenParts[0]);

            return $userId;
        }



	private function computeHash($string) {
		$hash = hash('sha256', $string);

		return $hash;
	}
}


