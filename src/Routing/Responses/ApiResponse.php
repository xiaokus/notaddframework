<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-08 11:03
 */
namespace Notadd\Foundation\Routing\Responses;

use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

/**
 * Class ApiResponse.
 */
class ApiResponse
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * Generate a api response to http response.
     *
     * @param \Psr\Http\Message\ResponseInterface|null $response
     * @param array                                    $params
     *
     * @return \Notadd\Foundation\Routing\Responses\ApiResponse|\Psr\Http\Message\ResponseInterface|\Zend\Diactoros\Response
     */
    public function generateHttpResponse(ResponseInterface $response = null, $params = [])
    {
        is_null($response) && $response = new Response();
        $params && $this->params = array_merge($this->params, $params);
        $status = collect($this->params)->get('code', 200);
        if (!is_int($status)) {
            $status = 500;
        }
        if ($status > 598 || $status < 100) {
            $status = 500;
        }
        $response = $response->withStatus($status)
            ->withHeader('pragma', 'no-cache')
            ->withHeader('cache-control', 'no-store')
            ->withHeader('content-type', 'application/json; charset=UTF-8');
        $response->getBody()->write(json_encode($this->params));

        return $response;
    }

    /**
     * Add params to api response.
     *
     * @param array $params
     *
     * @return $this
     */
    public function withParams($params = [])
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }
}
