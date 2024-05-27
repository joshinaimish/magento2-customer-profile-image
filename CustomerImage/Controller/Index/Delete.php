<?php
 
namespace Nxtech\CustomerImage\Controller\Index;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\Action; 
use Magento\Framework\DataObject;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Customer\Model\CustomerFactory;
use Nxtech\CustomerImage\Block\Edit;

class Delete extends Action
{

    const CUSTOMER_DIR = 'customer';
    const PROFILE_IMAGE = 'wysiwyg/profile.png';
    /**
    * @var \Magento\Framework\App\Config\ScopeConfigInterface
    */
    protected $scopeConfig;

    /** @var \Magento\Framework\Controller\Result\Json $result */

    protected $resultJsonFactory;

    /** @var \Magento\Framework\Filesystem\Driver\File $file */

    protected $file;

    /** @var \Magento\Customer\Model\CustomerFactory $customerFactory */

    protected $customerFactory;

    protected $_storeManager;

    protected $noProfileImage;

     /**
     * @var Filesystem
     */
    private $filesystem;

     /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $mediaDirectory;


    public function __construct(
        Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\Filesystem\Driver\File $file,
        CustomerFactory $customerFactory,
        Filesystem $filesystem,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Edit $noProfileImage
    ){
    
        $this->scopeConfig = $scopeConfig;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->file = $file;
        $this->customerFactory = $customerFactory;
        $this->_storeManager = $storeManager;
        $this->noProfileImage = $noProfileImage;
        $this->filesystem = $filesystem;
        $this->mediaDirectory = $this->filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        parent::__construct($context);
    }

    public function execute()
    {
        $resultData = $this->resultJsonFactory->create();
        $customerId = $this->getRequest()->getPost('customer_id');
        
        //$mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $destinationPath = $this->mediaDirectory->getAbsolutePath();
        $userImage = $this->noProfileImage->getProfileImage();
        $response = [];
        if($customerId) {
             $model = $this->customerFactory->create()->load($customerId);
            try {
                    $fileName = $model->getCustomImage();
                    $model->setCustomImage('');
                    $model->save();
                    $existFilePath = $destinationPath.SELF::CUSTOMER_DIR.$fileName;
        
                    if ($this->file->isExists($existFilePath))  {
                        $this->file->deleteFile($existFilePath);
                    }
                    $response['success'] = __('Profile Image Deleted!!');
                    $response['profile'] = $userImage;
                    $this->messageManager->addSuccess(__('Profile Image Deleted!!'));
                 
                } catch (\RuntimeException $e) {
                    $this->messageManager->addError($e->getMessage());
                }
            return $resultData->setData(['data' => $response]);
        }
    }
}