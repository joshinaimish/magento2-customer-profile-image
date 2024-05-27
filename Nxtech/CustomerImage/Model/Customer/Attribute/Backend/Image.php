<?php
/**
* Copyright © Magento, Inc. All rights reserved.
* See COPYING.txt for license details.
*/

/**
 * Created By : Naimish Joshi
 */
declare (strict_types = 1);

namespace Nxtech\CustomerImage\Model\Customer\Attribute\Backend;

use Magento\Framework\DataObject;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class Image extends AbstractBackend
{
    protected $filesystem;

    public function __construct(
        Filesystem $filesystem
    ) {
        $this->filesystem = $filesystem;
    }

    public function beforeSave($object)
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $value = $object->getData($attributeCode);

        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($attributeCode, null);
            $this->deleteImage($value['value']);
        }

        return parent::beforeSave($object);
    }

    private function deleteImage($fileName)
    {
        $mediaPath = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
        $filePath = $mediaPath . 'customer/' . $fileName;

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
