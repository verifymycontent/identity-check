# VerifyMyContent Identity-Check PHP SDK

PHP SDK to use VerifyMyContent Identity-Check OAuth service. 

## Installation

```bash
composer require verifymycontent/identity-check
```

## Get Started

```php
<?php

require(__DIR__ . "/vendor/autoload.php");

$vmc = new VerifyMyContent\IdentityCheck\VMC(getenv('VMC_API_KEY'), getenv('VMC_API_SECRET'), getenv('VMC_REDIRECT_URL'));
//$vmc->useSandbox();

// Redirect or show age-gate if we don't have a code yet
if (!isset($_GET['code'])) {
    $redirectURL = $vmc->redirectURL();
    $_SESSION['oauth2state'] = $vmc->state();
    header('Location: ' . $redirectURL);
    exit;

// Avoid CSRF attack
} elseif (empty($_GET['state']) || (isset($_SESSION['oauth2state']) && $_GET['state'] !== $_SESSION['oauth2state'])) {
    if (isset($_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
    }
    exit('Invalid state');
} else {
    try {
        // Try to get an access token using the authorization code grant.
        $accessToken = $vmc->exchangeCodeByToken($_GET['code']);
        $user = $vmc->user($accessToken);
        var_export($user);
    } catch (\Exception $e) {
        // Failed to get the access token or user details.
        exit($e->getMessage());
    }
}
```