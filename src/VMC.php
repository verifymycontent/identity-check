<?php namespace VerifyMyContent\IdentityCheck;

use VerifyMyContent\SDK\Core\Validator\ValidationException;
use VerifyMyContent\SDK\IdentityVerification\Entity\Requests\CreateIdentityVerificationRequest;
use VerifyMyContent\SDK\IdentityVerification\Entity\Requests\WebhookIdentityVerificationRequest;
use VerifyMyContent\SDK\ReIdentification\Entity\Requests\CreateReIdentificationRequest;
use VerifyMyContent\SDK\ReIdentification\Entity\Requests\WebhookReIdentificationRequest;
use VerifyMyContent\SDK\VerifyMyContent;

class VMC
{

  private $client;

  private $reIdentificationClient;

  private $verifyMyContent;

  public function __construct($clientID, $clientSecret)
  {
    $this->verifyMyContent = new VerifyMyContent($clientID, $clientSecret);
    $this->client = $this->verifyMyContent->identityVerification();
    $this->reIdentificationClient = $this->verifyMyContent->reIdentification();
  }

  /**
   * If you're still in development stages, you can use our sandbox environment
   */
  public function useSandbox()
  {
    $this->client->useSandbox();
    $this->reIdentificationClient->useSandbox();
    $this->verifyMyContent->useSandbox();
  }

  public function setBaseURL($url)
  {
    $this->client->setBaseURL($url);
    $this->reIdentificationClient->setBaseURL($url);
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

  /**
   * @throws ValidationException
   * @throws \VerifyMyContent\SDK\ReIdentification\Exception\FeatureNotEnabledException
   * @throws \VerifyMyContent\SDK\ReIdentification\Exception\NoApprovedVerificationFoundException
   */
  public function createReIdentification($data)
  {
    return $this->reIdentificationClient->createReIdentification(new CreateReIdentificationRequest($data));
  }

  public function getReIdentification($id)
  {
    return $this->reIdentificationClient->getReIdentification($id);
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

  /**
   * @throws ValidationException
   */
  public function parseReIdentificationWebhookPayload($data)
  {
    return new WebhookReIdentificationRequest($data);
  }
}
