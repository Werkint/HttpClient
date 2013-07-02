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

    public function __construct($base)
    {
        $this->base = $base;
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
        curl_setopt($this->link, CURLOPT_HTTPHEADER, [
            'Content-Type:application/x-www-form-urlencoded; charset=UTF-8',
            'User-Agent:Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.52 Safari/537.36',
            'Connection:keep-alive',
        ]);
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

    public function postRaw($link, $format, $data)
    {
        curl_setopt($this->link, CURLOPT_POST, true);
        $link = $this->base . ($link === '' || substr($link, 0, 1) == '/' ? $link : '/' . $link);
        curl_setopt($this->link, CURLOPT_URL, $link);
        curl_setopt($this->link, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->link, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($this->link);

        return $format ? new Response($ret, $format, $link) : null;
    }

    /**
     * @param int    $link
     * @param string $format
     * @param array  $data
     * @return null|Response
     */
    public function post($link, $format = null, array $data = [])
    {
        $data = http_build_query($data);

        return $this->postRaw($link, $format, $data);
    }

}