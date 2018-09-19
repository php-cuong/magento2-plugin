<?php
/**
 * GiaPhuGroup Co., Ltd.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GiaPhuGroup.com license that is
 * available through the world-wide-web at this URL:
 * https://www.giaphugroup.com/LICENSE.txt
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    PHPCuong
 * @package     PHPCuong_TextLink
 * @copyright   Copyright (c) 2018-2019 GiaPhuGroup Co., Ltd. All rights reserved. (http://www.giaphugroup.com/)
 * @license     https://www.giaphugroup.com/LICENSE.txt
 */

namespace PHPCuong\TextLink\Plugin\Catalog\Helper;

class Output
{
    /**
     * Eav config
     *
     * @var \Magento\Eav\Model\Config
     */
    protected $_eavConfig;

    /**
     * Url
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlInterface;

    /**
     * @param \Magento\Eav\Model\Config $eavConfig
     * @param \Magento\Framework\UrlInterface $_urlInterface
     */
    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Framework\UrlInterface $urlInterface
    ) {
        $this->_eavConfig = $eavConfig;
        $this->_urlInterface = $urlInterface;
    }

    /**
     * @param \Magento\Catalog\Helper\Output $output
     * @param callable $proceed
     * @param \Magento\Catalog\Model\Product $product
     * @param string $attributeHtml
     * @param string $attributeName
     * @return string
     */
    public function aroundProductAttribute(
        \Magento\Catalog\Helper\Output $output,
        callable $proceed,
        \Magento\Catalog\Model\Product $product,
        $attributeHtml,
        $attributeName
    ) {
        $result = $proceed($product, $attributeHtml, $attributeName);
        $attribute = $this->_eavConfig->getAttribute(\Magento\Catalog\Model\Product::ENTITY, $attributeName);
        if ($attribute &&
            $attribute->getId() &&
            ($attribute->getAttributeCode() == 'description' || $attribute->getAttributeCode() == 'short_description')
        ) {
            $textLink = 'black';
            $textLinkUrl = $this->_urlInterface->getUrl('catalogsearch/result', ['q' => $textLink]);
            $result = preg_replace('/'.$textLink.'/i', '<a href="'.$textLinkUrl.'"><b>'.$textLink.'</b></a>', $result);
        }

        return $result;
    }
}
