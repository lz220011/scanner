<?php
/**
 * Created by PhpStorm.
 * User: lvzhao
 * Date: 2018/3/27
 * Time: 21:59
 */
namespace Url;

require 'vendor/autoload.php';
class Scanner
{
    /*
     * @var array url数组
     */
    protected $urls;
    /*
     * $var \GuzzleHttp\Client
     */
    protected $httpClient;

    public function __construct(array $urls)
    {
        $this->urls = $urls;
        $this->httpClient = new \GuzzleHttp\Client();
    }

    public function getInvalidUrls()
    {
        $invalidUrls = [];
        foreach ($this->urls as $url) {
            try {
                $statusCode = $this->getStatusCodeForUrl($url);
            } catch (\Exception $e) {
                $statusCode = 500;
            }
            if ($statusCode >= 400) {
                array_push($invalidUrls,[
                    'url' => $url,
                    'status' => $statusCode
                ]);
            }
        }
        return $invalidUrls;
    }

    protected function getStatusCodeForUrl($url)
    {
        $httpResponse = $this->httpClient->options($url);
        return $httpResponse->getStatusCode();
    }
}