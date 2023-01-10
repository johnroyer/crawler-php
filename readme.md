## Introduction

Web crawler in simple.

**Note**: this is a site project. Do NOT use in production.

## Usage

Create handler from `AbstractHandler`, and set domain which handler should handles:

```php
class MyHandler extends \Zeroplex\Crawler\Handler\AbstractHandler
{
    public function getDomain(): string
    {
        return 'test.com';
    }

    public function shouldFetch(\Psr\Http\Message\RequestInterface $request): bool
    {
        if (1 === preg_match('/(css|js|jpg|png|gif)$/', $request->getUri())) {
            // ignore css, js and common images
            return false;
        }
        return true;
    }

    public function handle(\Psr\Http\Message\ResponseInterface $response): void
    {
        // get content using $response->getBody()->getContents()
    }
}
```

Then setup crawler and run:

```php
$crawler = new \Zeroplex\Crawler\Crawler();

$crawler->setDelay(0)
    ->setTimeout(3)
    ->setFollowRedirect(true)
    ->setUserAgnet('Mozilla/5.0 (platform; rv:geckoversion) Gecko/geckotrail Firefox/100.1');

$crawler->addHandler(new BlogHandler());

// URL to start
$crawler->run('https://test.com');
```