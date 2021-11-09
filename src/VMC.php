<?php namespace VerifyMyContent\IdentityCheck;

class VMC {

    private $clientID;

    private $clientSecret;

    private $redirectURL;

    private $currentState;

    private $currentProvider;

    public function __construct($clientID, $clientSecret, $redirectURL)
    {
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;
        $this->redirectURL = $redirectURL;
        $this->currentState = null;
        $this->currentProvider = null;
    }

    /**
     * If you're still in development stages, you can use our sandbox environment
     */
    public function useSandbox()
    {
        $this->provider()->useSandbox();
    }

    /**
     * URL to be redirect your user after the age-gate
     */
    public function redirectURL()
    {
        return $this->provider()->getAuthorizationUrl([
            "scope" => Providers\VerifyMyContentProvider::DEFAULT_SCOPE, 
            "state" => $this->state(),
        ]);
    }

    /**
     * OAuth state parameter used to avoid CSRF attacks
     */
    public function state()
    {
        if (null === $this->currentState) {
            $this->currentState = uniqid();
        }
        return $this->currentState;
    }

    /**
     * Exchange code by an access token
     */
    public function exchangeCodeByToken($code)
    {
        return $this->provider()->getAccessToken('authorization_code', [
            'code' => $code,
        ]);
    }

    /**
     * Return user data
     */
    public function user($accessToken)
    {
        return $this->provider()->getResourceOwner($accessToken)->toArray();
    }

    /**
     * VMA Provider
     */
    private function provider()
    {
        if ($this->currentProvider === null) {
            $this->currentProvider = new Providers\VerifyMyContentProvider([
                'clientId'                 => $this->clientID,
                'clientSecret'             => $this->clientSecret,
                'redirectUri'              => $this->redirectURL,
            ]);
        }
        return $this->currentProvider;
    }
}