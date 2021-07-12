<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Kirill\Cash\Controller\Bonus;

use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;


use Magento\Framework\View\Result\PageFactory;

class History implements \Magento\Framework\App\ActionInterface,  HttpGetActionInterface {
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
      * @param PageFactory $resultPageFactory
     */
    public function __construct(PageFactory $resultPageFactory) {
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Customer order history
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Cashback'));

        return $resultPage;
    }



}
