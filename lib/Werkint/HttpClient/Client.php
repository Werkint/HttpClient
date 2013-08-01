<?php
namespace Werkint\HttpClient;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Symfony\Component\DomCrawler\Crawler;

class Client extends GuzzleClient
{
    public function __construct($baseUrl = '', $config = null)
    {
        parent::__construct($baseUrl = '', $config = null);

        $cookiePlugin = new CookiePlugin(new ArrayCookieJar());
        $this->addSubscriber($cookiePlugin);
    }

    public function getQuery($url, array $data)
    {
        return $this->get($url . '?' . http_build_query($data))->send();
    }

    public function parseForm(array $data)
    {
        $doc = new Crawler();
        $doc->addHtmlContent($data);
        $ret = [];
        foreach ($doc->filter('input[type="hidden"]') as $node) {
            /** @var \DOMElement $node */
            $ret[$node->getAttribute('name')] = $node->getAttribute('value');
        }
        return $ret;
    }
}