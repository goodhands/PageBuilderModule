<?php

namespace Vapeuk\PageBuilderRewardsContent\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * Save the page reference,
 * so that it can be accessed by the LayoutCacheablePlugin
 */
class SavePageReference implements ObserverInterface
{
    /**
     * @var \Magento\Cms\Model\Page|null
     */
    protected $page = null;

    /**
     * Save the page reference to the session
     *
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $page = $observer->getEvent()->getData('page');
        $this->page = $page;
    }

    /**
     * Get the page reference
     *
     * @return \Magento\Cms\Model\Page|null
     */
    public function getPage()
    {
        return $this->page;
    }
}
