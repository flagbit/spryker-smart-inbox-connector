<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Business\OneAndOneMailConnectorFacade getFacade()
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return [
            'foo' => 'hello worlkd',
        ];
    }
}
