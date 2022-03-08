<?php

namespace Wireit\Commandline\Helper;
use \Magento\Framework\App\Helper\Context;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\App\State;
use \Magento\Customer\Model\CustomerFactory;
use \Symfony\Component\Console\Input\Input;

class Customer extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $storeManager;
    protected $state;
    protected $customerFactory;
  //  protected $data;
    protected $customerId;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        State $state,
        CustomerFactory $customerFactory
    ) {
        $this->storeManager = $storeManager;
        $this->state = $state;
        $this->customerFactory = $customerFactory;

        parent::__construct($context);
    }

    public function newCustomer($newCustomer)
    {
        $currentWebsiteId = $this->storeManager->getStore()->getWebsiteId();
        $customer = $this->customerFactory->create();
        $customer
            ->setWebsiteId($currentWebsiteId)
            ->setEmail($newCustomer['email'])
            ->setFirstname($newCustomer['firstname'])
            ->setLastname($newCustomer['lastname']);
        $customer->save();
    }
}

