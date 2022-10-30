<?php

/*
 * This file is part of the hedeqiang/alchemypay.
 *
 * (c) hedeqiang <laravel_code@163.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Hedeqiang\AlchemyPay;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hedeqiang\AlchemyPay\Support\Config;
use GuzzleHttp\Psr7\ServerRequest;
use Hedeqiang\AlchemyPay\Traits\Utils;
use Psr\Http\Message\ServerRequestInterface;

class Pay implements PayInterface
{
    use Utils;

    protected Config $config;

    protected $guzzleOptions = [];

    public function __construct(array $config)
    {
        $this->config = new Config($config);
    }

    protected function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    protected function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    /**
     * @param $path
     * @param $params
     *
     * @return array
     * @throws GuzzleException
     */
    public function request($path, $params): array
    {
        if (empty($params['merchantCode'])) {
            $params['merchantCode'] = $this->config->get('merchantCode');
        }
        $params = $this->getSortParams($params);
        $privateKey = $this->config->get('privateKey');
        $signStr = $this->getSignString($params,$privateKey);
        $params['sign'] = $signStr;

        $response = $this->getHttpClient()->post($this->buildEndpoint($path), [
            'json'    => $params,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return array|false
     */
    public function handleNotify(): array
    {
        $request = $this->getCallbackParams();
        $response = $request->getBody()->getContents();
    }


    /**
     * @param array|ServerRequestInterface|null $contents
     */
    protected function getCallbackParams($contents = null): ServerRequestInterface
    {
        if (is_array($contents) && isset($contents['body']) && isset($contents['headers'])) {
            return new ServerRequest('POST', 'http://localhost', $contents['headers'], $contents['body']);
        }

        if (is_array($contents)) {
            return new ServerRequest('POST', 'http://localhost', [], json_encode($contents));
        }

        if ($contents instanceof ServerRequestInterface) {
            return $contents;
        }

        return ServerRequest::fromGlobals();
    }

    protected function buildEndpoint(string $path): string
    {
        return sprintf("%s/%s",$this->config->get('endpoint'), $path);
    }
}
