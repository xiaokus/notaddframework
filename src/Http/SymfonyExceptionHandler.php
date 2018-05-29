<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime      2017-12-11 09:50
 */
namespace Notadd\Foundation\Http;

use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler;

/**
 * Class SymfonyExceptionHandler.
 */
class SymfonyExceptionHandler extends ExceptionHandler
{
    /**
     * @var bool
     */
    protected $debug = true;

    public function __construct($debug = true, $charset = null, $fileLinkFormat = null)
    {
        parent::__construct($debug, $charset, $fileLinkFormat);
        $this->debug = $debug;
    }

    /**
     * @param FlattenException $exception
     *
     * @return string
     */
    public function getContent(FlattenException $exception)
    {
        switch ($exception->getStatusCode()) {
            case 404:
                $title = '对不起，页面已丢失！';
                break;
            default:
                $title = '错误：' . $exception->getMessage();
        }
        $content = '';
        if ($this->debug) {
            return parent::getContent($exception);
        }
        return <<<EOF
            <div class="exception-summary">
                <div class="container">
                    <div class="exception-message-wrapper">
                        <h1 class="break-long-words exception-message">$title</h1>
                    </div>
                </div>
            </div>

            <div class="container">
                $content
            </div>
EOF;
    }
}