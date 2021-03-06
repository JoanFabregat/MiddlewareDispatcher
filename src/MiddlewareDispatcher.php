<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     10/04/2018
// Time:     18:49
// Project:  MiddlewareDispatcher
//
declare(strict_types=1);
namespace CodeInc\MiddlewareDispatcher;
use CodeInc\MiddlewareDispatcher\Exceptions\NotAMiddlewareException;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


/**
 * Class MiddlewareDispatcher
 *
 * @package CodeInc\MiddlewareDispatcher
 * @author Joan Fabrégat <joan@codeinc.fr>
 * @license MIT <https://github.com/CodeIncHQ/MiddlewareDispatcher/blob/master/LICENSE>
 * @link https://github.com/CodeIncHQ/MiddlewareDispatcher
 */
final class MiddlewareDispatcher extends AbstractMiddlewareDispatcher
{
    /**
     * @var MiddlewareInterface[]
     */
    private $middleware = [];

    /**
     * Final PSR-15 request handler called if no middleware can process the request.
     *
     * @see MiddlewareDispatcher::getFinalRequestHandler()
     * @see MiddlewareDispatcher::setFinalRequestHandler()
     * @var RequestHandlerInterface|null
     */
    private $finalRequestHandler;

    /**
     * MiddlewareDispatcher constructor.
     *
     * @param iterable|null $middleware
     * @param null|RequestHandlerInterface $finalRequestHandler
     * @throws NotAMiddlewareException
     */
    public function __construct(?iterable $middleware = null, ?RequestHandlerInterface $finalRequestHandler = null)
    {
        if ($middleware !== null) {
            foreach ($middleware as $item) {
                if (!$item instanceof MiddlewareInterface) {
                    throw new NotAMiddlewareException($item);
                }
                $this->addMiddleware($item);
            }
        }
        $this->finalRequestHandler = $finalRequestHandler;
    }

    /**
     * @inheritdoc
     * @return RequestHandlerInterface
     */
    public function getFinalRequestHandler():RequestHandlerInterface
    {
        return $this->finalRequestHandler ?? parent::getFinalRequestHandler();
    }

    /**
     * Sets the final PSR-15 request handler called if no middleware can process the request.
     *
     * @param RequestHandlerInterface $finalRequestHandler
     */
    public function setFinalRequestHandler(RequestHandlerInterface $finalRequestHandler):void
    {
        $this->finalRequestHandler = $finalRequestHandler;
    }

    /**
     * Adds a middleware to the dispatcher.
     *
     * @param MiddlewareInterface $middleware
     */
    public function addMiddleware(MiddlewareInterface $middleware):void
    {
        $this->middleware[] = $middleware;
    }

    /**
     * @inheritdoc
     * @return MiddlewareInterface[]
     */
    public function getMiddleware():iterable
    {
        return $this->middleware;
    }
}