<?php namespace VerifyMyContent\IdentityCheck;

use VerifyMyContent\SDK\Core\Validator\ValidationException;
use VerifyMyContent\SDK\IdentityVerification\Entity\Requests\CreateIdentityVerificationRequest;
use VerifyMyContent\SDK\IdentityVerification\Entity\Requests\WebhookIdentityVerificationRequest;
use VerifyMyContent\SDK\VerifyMyContent;

class VMC
{

  private $client;

  private $verifyMyContent;

  public function __construct($clientID, $clientSecret)
  {
    $this->verifyMyContent = new VerifyMyContent($clientID, $clientSecret);
    $this->client = $this->verifyMyContent->identityVerification();
  }

  /**
   * If you're still in development stages, you can use our sandbox environment
   */
  public function useSandbox()
  {
    $this->client->useSandbox();
    $this->verifyMyContent->useSandbox();
  }

  public function setBaseURL($url)
  {
    $this->client->setBaseURL($url);
  }

  /**
   * @throws ValidationException
   */
  public function createIdentityVerification($data)
  {
    return $this->client->createIdentityVerification(new CreateIdentityVerificationRequest($data));
  }

  public function getIdentityVerification($id)
  {
    return $this->client->getIdentityVerification($id);
  }

  public function addAllowedRedirectUrls($urls){
    $this->verifyMyContent->addRedirectUrls($urls);
  }

  public function removeAllowedRedirectUrls($urls){
    $this->verifyMyContent->removeRedirectUrls($urls);
  }

  /**
   * @throws ValidationException
   */
  public function parseIdentityVerificationWebhookPayload($data)
  {
    return new WebhookIdentityVerificationRequest($data);
  }
}