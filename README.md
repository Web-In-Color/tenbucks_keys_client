# TenbucksKeysClient
Send API keys TenBucks API server

# Usage
```php
$client = new TenbucksKeysClient();
$url = 'http://example.org';
$data = array(
    'url'         => $url, // MANDATORY: complete (with protocol) shop url
    'platform'    => 'TestPlatform', // Prestashop|Magento|WooCommerce
    'credentials' => array(
        'key'    => 'test_key', // key
        'secret' => 'test_secret', // secret
    )
);

if ($client->setKey($url)->send($data)) {
    // success
}
```

# test
```bash
$ phpunit --bootstrap lib/TenbucksKeysClient.php tests/TenbucksKeysClientTest
```