# VerifyMyContent Identity-Check PHP SDK

PHP SDK to use VerifyMyContent Identity-Check OAuth service. 

## Installation

```bash
composer require verifymycontent/identity-check
```

## Get Started

The main class to handle the moderation integration process is the \VerifyMyContent\IdentityCheck\VMC. It will abstract the HMAC generation for the API calls.

## Start an Identity Verification
```php
<?php
require(__DIR__ . "/vendor/autoload.php");

$vmc = new \VerifyMyContent\IdentityCheck\VMC(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$vmc->useSandbox();

try {
    $response = $vmc->createIdentityVerification([
            "customer" => [
                "id" => "YOUR-CUSTOMER-UNIQUE-ID",
                "email" => "person@example.com",
                "phone" => "+4412345678"
            ],
            "redirect_uri" => "https://example.com/callback",
            "webhook" => "https://example.com/webhook",
        ]
    );
    
    // save $response->id if you want to save the verification of your customer

    // redirect user to check identity
    header("Location: {$response->redirect_uri}");
} catch (Exception $e) {
  echo $e;
}
```

### Retrieve Identity Verification by ID

Retrieves a specific identity verification to get current status.

- Pass the `id` of the identity verification to the `getIdentityVerification` method.
- Receive an `\VerifyMyContent\SDK\IdentityVerification\Entity\Responses\GetIdentityVerificationResponse` (library used internally by this sdk).


```php
<?php
require(__DIR__ . "/vendor/autoload.php");

$vmc = new \VerifyMyContent\IdentityCheck\VMC(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));
//$vmc->useSandbox();

$response = $vmc->getIdentityVerification("YOUR-IDENTITY-VERIFICATION-ID");

// Printing current status
echo "Status: {$response->status}";
```

### Receive an Identity Verification Webhook

Receive a webhook from VerifyMyContent when the identity verification status changes.

- Receive a webhook from VerifyMyContent with the `$_POST` data that can be parsed using method `parseIdentityVerificationWebhookPayload`.

```php
<?php
require(__DIR__ . "/vendor/autoload.php");

$vmc = new \VerifyMyContent\IdentityCheck\VMC(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'));

$jsonPayload = '{
  "id": "ABC-123-5678-ABC",
  "customer_id": "customer_id",
  "status": "pending"
}';
$data = json_decode($jsonPayload, true);
$webhook = $vmc->parseIdentityVerificationWebhookPayload($data);

// Printing current status
echo "Status: {$webhook->status} received from verification {$webhook->id}";

// This is how you can check if the identity verification is approved.
if ($webhook->status === \VerifyMyContent\SDK\IdentityVerification\IdentityVerificationStatus::APPROVED) {
    // do your thing
}
```