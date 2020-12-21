<?php

namespace OneAndOne\Zed\OneAndOneMailConnector\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;

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
