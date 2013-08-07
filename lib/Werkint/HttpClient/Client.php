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
        parent::__construct($baseUrl, $config);

        $cookiePlugin = new CookiePlugin(new ArrayCookieJar());
        $this->addSubscriber($cookiePlugin);
    }

    public function getQuery($url, array $data)
    {
        $req = $this->get($url, [], [
            'query' => $data
        ]);
        return $req->send()->getBody(true);
    }

    public function parseForm($data)
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