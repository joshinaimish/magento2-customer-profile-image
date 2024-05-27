<?php

namespace Nxtech\CustomerImage\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\CustomerFactory;
use \Magento\Store\Model\StoreManagerInterface;
class Customer implements ArgumentInterface
{
    protected $customerSession;
    protected $customerFactory;
    protected $_storeManager;

    public function __construct(
        Session $customerSession,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->customerSession = $customerSession;
        $this->customerFactory = $customerFactory;
        $this->_storeManager = $storeManager;
    }

    public function getCustomerImage()
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        $customer = $this->customerFactory->create()->load($customerId);
        if($customer->getCustomImage()){
            return $customerImageUrl = $this->_storeManager->getStore()->getUrl('profile/index/view/', ['image' => base64_encode($customer->getCustomImage())]);
        }
        return;
    }
    public function getCustomerName()
    {
        $customerId = $this->customerSession->getCustomer()->getId();
        $customer = $this->customerFactory->create()->load($customerId);
        if($customer){
            $fname = $lname = '';
            if($customer->getFirstname()){
                $fname = substr($customer->getFirstname(), 0, 1);    
            }
            if($customer->getLastname()){
                $lname = substr($customer->getLastname(), 0, 1);    
            }            
            return $fname . $lname; 
        }
        return;
    }
}
