<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

/**
 * Class IndexController
 *
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Business\OneAndOneMailConnectorFacade getFacade()
 * @method \OneAndOne\Zed\OneAndOneMailConnector\Persistence\OneAndOneMailConnectorRepositoryInterface getRepository()
 *
 * @package OneAndOne\Zed\OneAndOneMailConnector\Communication\Controller
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
