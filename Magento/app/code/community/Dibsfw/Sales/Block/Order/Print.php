<?php

/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Mage
 * @package    Mage_Sales
 * @copyright  Copyright (c) 2008 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * Modifications copyrighted by Dibs A/S, (c) 2010.
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Order information for print
 *
 * @category   Mage
 * @package    Mage_Sales
 */
class Dibsfw_Sales_Block_Order_Print extends Mage_Sales_Block_Order_Print {

    protected function _prepareLayout() {
        if($headBlock = $this->getLayout()->getBlock('head')) {
            $headBlock->setTitle($this->__('Print Order # %s', $this->getOrder()->getRealOrderId()));
        }

        $this->setChild(
                'payment_info', $this->helper('payment')->getInfoBlock($this->getOrder()->getPayment())
        );
    }

    public function getPaymentInfoHtml() {
        $res = $this->getChildHtml('payment_info');

        $oPaymentObj = $this->getOrder()->getPayment()->getMethodInstance();
        $sPaymentClass = get_class($oPaymentObj);
        if(strpos($sPaymentClass, "Dibsfw") !== FALSE) {
            $oPaymentObj->dibsflex_api_checkTable();
            // Read info directly from the database   	
            $read = Mage::getSingleton('core/resource')->getConnection('core_read');
            $sTablePrefix = Mage::getConfig()->getTablePrefix();
            $row = $read->fetchRow("select * from " . $sTablePrefix . "dibs_orderdata where orderid = " . $this->getOrder()->getIncrementId());
            if(count($row) > 0) {
                if($row['status'] == '1') {
                    $res .= "<table border='0' width='100%'>";
                    if($row['transact'] != '0') {
                        $res .= "<tr><td>" . Mage::helper('dibsfw')->__('DIBSFW_LABEL_8') . "</td>";
                        $res .= "<td>" . $row['transact'] . "</td></tr>";
                    }
                    if($row['paytype'] != '0') {
                        $res .= "<tr><td>" . Mage::helper('dibsfw')->__('DIBSFW_LABEL_12') . "</td>";
                        $res .= "<td>" . $oPaymentObj->cms_dibs_printLogo($row['paytype']) . "</td></tr>";
                    }
                    if($row['fee'] != '0') {
                        $res .= "<tr><td>" . Mage::helper('dibsfw')->__('DIBSFW_LABEL_11') . "</td>";
                        $res .= "<td>" . $this->getOrder()->getOrderCurrencyCode() . "&nbsp;" . number_format(((int) $row['fee']) / 100, 2, ',', ' ') . "</td></tr>";
                    }
                    $res .= "</table><br />";
                }
                else {
                    $res .= "<br />" . Mage::helper('dibsfw')->__('DIBSFW_LABEL_19') . "<br />";
                }
            }
        }

        return $res;
    }

    public function getOrder() {
        return Mage::registry('current_order');
    }

    protected function _prepareItem(Mage_Core_Block_Abstract $renderer) {
        $renderer->setPrintStatus(true);

        return parent::_prepareItem($renderer);
    }

}