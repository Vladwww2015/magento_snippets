<?php

class Namespace_Modulename_Model_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     * @throws Mage_Core_Model_Store_Exception
     */
    public function clearCartLoggedCustomer(Varien_Event_Observer $observer)
    {
        $customerQuote = Mage::getModel('sales/quote')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->loadByCustomer(Mage::getSingleton('customer/session')->getCustomerId());
        $itemsCustomer = $customerQuote->getItemsCollection();
        $checkoutSession = $observer->getEvent()->getCheckoutSession();
        $quote = $checkoutSession->getQuote();
        $items = $quote->getItemsCollection();
        try {
            if ($items->getSize() > 0) {
                foreach ($itemsCustomer as $item) {
                    $customerQuote->removeItem($item->getId());
                }
                $customerQuote->save();
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }
}
