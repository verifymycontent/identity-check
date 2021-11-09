<?php

namespace VerifyMyContent\IdentityCheck\Providers;

use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;
use League\OAuth2\Client\Provider\GenericResourceOwner;

class VerifyMyContentProvider extends \League\OAuth2\Client\Provider\AbstractProvider {

    use BearerAuthorizationTrait;

    const ACCESS_TOKEN_RESOURCE_OWNER_ID = null;

    const DEFAULT_SCOPE = 'identity-check';
    
    private $baseURL = "https://oauth.verifymycontent.com";

    public function useSandbox(){
        $this->baseURL = "https://oauth.sandbox.verifymycontent.com";
    }

    public function getBaseAuthorizationUrl(){
        return "{$this->baseURL}/authorize";
    }

    public function getBaseAccessTokenUrl(array $params) {
        return "{$this->baseURL}/token";
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token) {
        return "{$this->baseURL}/users/me";
    }
    
    protected function getDefaultScopes() {
        return [static::DEFAULT_SCOPE];
    }

    protected function checkResponse(ResponseInterface $response, $data) {

    }

    protected function createResourceOwner(array $response, AccessToken $token) {
        return new GenericResourceOwner($response, null);
    }
}