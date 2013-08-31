<?php
namespace Werkint\HttpClient;

/**
 * Class Link
 * Класс, отвечающий за соединение с сервером QIWI
 * @package Werkint\Qiwi
 */
class Link
{
    protected $cookiePrefix;
    protected $base;
    protected $timeout;

    public function __construct(
        $base,
        $timeout = 6
    ) {
        $this->base = $base;
        $this->timeout = $timeout;
        $this->cookie = '/tmp/httpclient-tmp-' . sha1(microtime(true) . $base);

        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
        @unlink($this->cookie);
    }

    protected $link;
    protected $cookie;

    public function getConnection()
    {
        return $this->link;
    }

    protected function connect()
    {
        $this->link = curl_init();
        curl_setopt($this->link, CURLOPT_COOKIEFILE, $this->cookie);
        curl_setopt($this->link, CURLOPT_COOKIEJAR, $this->cookie);
        curl_setopt($this->link, CURLOPT_POST, true);
        curl_setopt($this->link, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->link, CURLOPT_TIMEOUT, $this->timeout);
    }

    public function setHeadersAjax()
    {
        $this->headers = [
            'Accept'           => 'application/json',
            'Content-Type'     => 'application/x-www-form-urlencoded; charset=UTF-8',
            'User-Agent'       => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36',
            'Connection'       => 'keep-alive',
            'X-Requested-With' => 'XMLHttpRequest',
        ];
    }

    public function setHeadersHtml()
    {
        $this->headers = [
            'Accept'     => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.71 Safari/537.36',
            'Connection' => 'keep-alive',
        ];
    }

    public function setHeadersHtmlForm()
    {
        $this->setHeadersHtml();
        $this->headers = [
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ];
    }

    protected function disconnect()
    {
        curl_close($this->link);
    }

    public function reconnect()
    {
        $this->disconnect();
        $this->connect();
    }

    public $headers = [];

    public function postRaw($link, $format, $data)
    {
        $link = $this->base . ($link === '' || substr($link, 0, 1) == '/' ? $link : '/' . $link);
        curl_setopt($this->link, CURLOPT_URL, $link);
        curl_setopt($this->link, CURLOPT_POSTFIELDS, $data);
        $hdr = [];
        foreach ($this->headers as $k => $v) {
            $hdr[] = $k . ':' . $v;
        }
        curl_setopt($this->link, CURLOPT_HTTPHEADER, $hdr);

        $ret = curl_exec($this->link);

        if (curl_errno($this->link)) {
            //die('Да ебать нахуй');
            throw new \Exception('Curl error: ' . curl_error($this->link));
        }

        return $format ? new Response($ret, $format, $link) : null;
    }

    /**
     * @param int    $link
     * @param string $format
     * @param array  $data
     * @param bool   $skipHeaders
     * @return null|Response
     */
    public function post($link, $format = null, array $data = [], $skipHeaders = false)
    {
        $data = http_build_query($data);

        if (!$skipHeaders) {
            if ($format == 'json') {
                $this->setHeadersAjax();
            } else {
                $this->setHeadersHtml();
            }
        }


        return $this->postRaw($link, $format, $data);
    }

}