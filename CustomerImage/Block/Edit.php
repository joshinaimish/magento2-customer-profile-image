<?php
/**
* Copyright © Magento, Inc. All rights reserved.
* See COPYING.txt for license details.
*/

/**
 * Created By : Naimish Joshi
 */
declare (strict_types = 1);

namespace Nxtech\CustomerImage\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Asset\Repository;

class Edit extends Template
{
    protected $customerSession;
    protected $customerRepository;
    protected $customerFactory;
    public $_storeManager;
    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $viewFileUrl;

    public function __construct(
        Context $context,
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        CustomerFactory $customerFactory,
        StoreManagerInterface $storeManager,
        Repository $viewFileUrl,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->_storeManager = $storeManager;
        $this->viewFileUrl = $viewFileUrl;
    }

    public function getCustomerImage()
    { 
        $customerId = $this->customerSession->getCustomer()->getId();
        $customer = $this->customerFactory->create()->load($customerId);
        if($customer->getCustomImage()){
            return $customerImageUrl = $this->getUrl('profile/index/view/', ['image' => base64_encode($customer->getCustomImage())]);
        }
        return;
    }
    public function getCustomerId()
    {
        return $this->customerSession->getCustomer()->getId();    
    }

    public function getCustomDeleteUrl(){
        return $this->_storeManager->getStore()->getUrl('profile/index/delete');
    }

    public function getProfileImage(){
        return $this->getViewFileUrl('Nxtech_CustomerImage::images/profile.png');
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

