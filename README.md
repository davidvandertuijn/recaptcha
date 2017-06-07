# reCAPTCHA

<a href="https://packagist.org/packages/davidvandertuijn/recaptcha"><img src="https://poser.pugx.org/davidvandertuijn/recaptcha/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/davidvandertuijn/recaptcha"><img src="https://poser.pugx.org/davidvandertuijn/recaptcha/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/davidvandertuijn/recaptcha"><img src="https://poser.pugx.org/davidvandertuijn/recaptcha/license.svg" alt="License"></a>

## Install

```
composer require davidvandertuijn/recaptcha
```

## Usage

```php
use Davidvandertuijn\Recaptcha;
```

**Register your site**
<a href="https://www.google.com/recaptcha/admin#list">reCAPTCHA: Easy on Humans, Hard on Bots</a>

*Add script -tag*
```<script src="//www.google.com/recaptcha/api.js"></script>```

**Add div -tag within ```<form>``` ... ```</form>```**
```<div class="g-recaptcha" data-sitekey="your-sitekey-here"></div>```

**Verify**

```php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oRecaptcha = new Recaptcha;

    $oRecaptcha->setSecret('your-secret-key-here');
    $oRecaptcha->setResponse($_POST['g-recaptcha-response']);
    $oRecaptcha->setRemoteIp($_SERVER['REMOTE_ADDR']);

    if (!$oRecaptcha->verify()) {
        // false
    } else {
        // true
    }
}
```
