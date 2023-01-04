<?php namespace VerifyMyContent\IdentityCheck;

use VerifyMyContent\SDK\Core\Validator\ValidationException;
use VerifyMyContent\SDK\IdentityVerification\Entity\Requests\CreateIdentityVerificationRequest;
use VerifyMyContent\SDK\IdentityVerification\Entity\Requests\WebhookIdentityVerificationRequest;
use VerifyMyContent\SDK\VerifyMyContent;

interface IdentityVerificationStatus
{
  const PENDING = 'pending';
  const STARTED = 'started';
  const EXPIRED = 'expired';
  const FAILED = 'failed';
  const APPROVED = 'approved';
}

class VMC
{

  private $client;

  public function __construct($clientID, $clientSecret)
  {
    $this->client = (new VerifyMyContent($clientID, $clientSecret))->identityVerification();
  }

  /**
   * If you're still in development stages, you can use our sandbox environment
   */
  public function useSandbox()
  {
    $this->client->useSandbox();
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

  /**
   * @throws ValidationException
   */
  public function parseIdentityVerificationWebhookPayload($data)
  {
    return new WebhookIdentityVerificationRequest($data);
  }
}