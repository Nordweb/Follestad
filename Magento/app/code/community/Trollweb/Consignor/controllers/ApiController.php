<?php
/**
 * Magento Consignor Integration
 *
 * LICENSE AND USAGE INFORMATION
 * It is NOT allowed to modify, copy or re-sell this file or any
 * part of it. Please contact us by email at support@trollweb.no or
 * visit us at www.trollweb.no if you have any questions about this.
 * Trollweb is not responsible for any problems caused by this file.
 *
 * Visit us at http://www.trollweb.no today!
 *
 * @category   Trollweb
 * @package    Trollweb_Consignor
 * @copyright  Copyright (c) 2011 Trollweb (http://www.trollweb.no)
 * @license    Single-site License
 *
 */
class Trollweb_Consignor_ApiController extends Mage_Core_Controller_Front_Action
{

	public function indexAction()
	{
		$soapModel = Mage::getModel('consignor/soap');
		$soapModel->setController($this);
		$soapModel->run();
	}
}