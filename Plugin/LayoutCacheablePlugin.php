<?php

namespace Vapeuk\PageBuilderRewardsContent\Plugin;

use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Framework\View\Layout;
use Vapeuk\PageBuilderRewardsContent\Observer\SavePageReference;

/**
 * For logged-in users the rewards block needs to know the logged in user,
 * in order to output the correct reward data.
 *
 * Therefore the Rewards block is not cacheable, for logged-in users,
 * but is cached for guests.
 * Furthermore, as this block is included in the page builder,
 * we can't set cacheable="false" in the layout XML.
 *
 * Without this plugin the block would be cached for all users,
 * and the user data would not be available due to
 * Magento\PageCache\Model\Layout\DepersonalizePlugin
 *
 * This plugin will prevent the block (and page) from being cached,
 * by returning false from the isCacheable method,
 * if the user is logged in.
 */
class LayoutCacheablePlugin
{
    /**
     * Class constructor
     *
     * @param HttpContext $httpContext
     * @param SavePageReference $savePageReference
     * @return void
     */
    public function __construct(
        protected HttpContext $httpContext,
        protected SavePageReference $savePageReference
    ) {
    }

    /**
     * Prevent the PageBuilderRewardsContent block (and page) from being cached
     *
     * @param Layout $subject
     * @param callable $proceed
     * @return bool
     */
    public function aroundIsCacheable(\Magento\Framework\View\Layout $subject, callable $proceed): bool
    {
        $loggedIn = $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
        $page = $this->savePageReference->getPage();
        if ($loggedIn && $page) {
            $containsRewardsContent = str_contains($page->getContent(), \Vapeuk\PageBuilderRewardsContent\Block\Widget\RewardsContent::class);
            if ($containsRewardsContent) {
                return false;
            }
        }

        return $proceed();
    }
}
