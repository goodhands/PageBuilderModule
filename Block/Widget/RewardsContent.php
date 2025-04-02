<?php

namespace Vapeuk\PageBuilderRewardsContent\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory as OrderCollectionFactory;
use Magento\Newsletter\Model\SubscriberFactory;
use Vapeuk\PageBuilderRewardsContent\Model\Config\RewardsContentConfig;
use Magento\Framework\Url;
use Magento\Customer\Model\Url as CustomerUrl;
use Vapeuk\PageBuilderEmailSignup\Model\Config\Source\Data;

/**
 * Rewards Content Pagebuilder Widget
 */
class RewardsContent extends Template implements BlockInterface
{
    /**
     * @const WIDGET_TEMPLATE The template to use for the widget
     */
    public const WIDGET_TEMPLATE = "widget/rewards_content/default.phtml";

    /**
     * @var string The template to use for the widget
     */
    protected $_template = self::WIDGET_TEMPLATE;

    /**
     * Class constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param HttpContext $httpContext
     * @param RewardsContentConfig $rewardsConfig
     * @param CustomerSession $customerSession
     * @param OrderCollectionFactory $orderCollectionFactory
     * @param SubscriberFactory $subscriberFactory
     * @param Url $urlHelper
     * @param CustomerUrl $customerUrl
     * @param Data $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        protected HttpContext $httpContext,
        protected RewardsContentConfig $rewardsConfig,
        protected CustomerSession $customerSession,
        protected OrderCollectionFactory $orderCollectionFactory,
        protected SubscriberFactory $subscriberFactory,
        protected Url $urlHelper,
        protected CustomerUrl $customerUrl,
        protected Data $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Gets the content for the given way to earn section
     *
     * @param string $contentKey
     *
     * @return string|null
     */
    public function getContent(string $contentKey): ?string
    {
        return $this->decodeHtmlEntities($this->getData($contentKey));
    }

    /**
     * Gets the ways to earn types as array
     *
     * @param boolean $includeAll
     * @return array
     */
    public function getWaysToEarnTypes($includeAll = false): array
    {
        if ($includeAll) {
            return $this->rewardsConfig::WAYS_TO_EARN_TYPES;
        }

        $wayToEarnTypes = $this->rewardsConfig::WAYS_TO_EARN_TYPES;
        unset($wayToEarnTypes['ways_to_earn_create_an_account']);
        return $wayToEarnTypes;
    }

    /**
     * Gets the rewards for the given way to earn and type
     *
     * @param string $wayToEarnKey
     * @param string $wayToEarnType
     *
     * @return array
     */
    public function getRewardsForWayToEarn(string $wayToEarnKey, string $wayToEarnType): array
    {
        return $this->rewardsConfig::REWARDS[$wayToEarnType][$wayToEarnKey] ?? [];
    }

    /**
     * Gets the reward tiers as array
     *
     * @return array
     */
    public function getRewardTiers(): array
    {
        return $this->rewardsConfig::REWARDS_TIERS;
    }

    /**
     * Gets the annual spend for the given reward tier
     *
     * @param string $tier
     *
     * @return array
     */
    public function getAnnualSpendForTier(string $tier): array
    {
        return $this->rewardsConfig::REWARDS_TIERS_ANNUAL_SPEND[$tier] ?? [];
    }

    /**
     * Gets the reward type label for the given quantity and type
     *
     * Essentially provides day or days, pt or pts
     *
     * @param int|float $qty
     * @param string $type
     *
     * @return string
     */
    public function getRewardLabelByQtyAndType(int|float $qty, string $type): string
    {
        $label = '';
        if (strpos($type, 'Point') !== false) {
            $label = 'pt';
        }

        if (strpos($type, 'Day') !== false) {
            $label = 'day';
        }

        return empty($label) ? $label : $label . ($qty > 1 ? 's' : '');
    }

    /**
     * Gets the redemption rewards
     *
     * @return array
     */
    public function getRedemptionRewards(): array
    {
        return $this->rewardsConfig::REDEMPTION_REWARDS;
    }

    /**
     * Return true if customer is logged in
     *
     * @return boolean
     */
    public function isLoggedIn(): bool
    {
        return $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    /**
     * Decode HTML entities.
     *
     * Pagebuilder stores quotes as "&quote;" instead of "&quot;" which isn't recognised by html_entity_decode
     * so we need to replace those strings ourselves.
     *
     * @param null|string $str
     * @return null|string
     */
    private function decodeHtmlEntities(?string $str): ?string
    {
        if ($str === null) {
            return null;
        }

        $str = html_entity_decode($str); // phpcs:ignore Magento2.Functions.DiscouragedFunction.Discouraged
        return str_replace('&quote;', '"', $str);
    }

    /**
     * Has the user made at least one purchase
     *
     * @return boolean
     */
    public function hasCustomerMadeAPurchase(): bool
    {
        // Check if customer is logged in
        if (!$this->isLoggedIn()) {
            return false;
        }

        $orders = $this->orderCollectionFactory->create()
            ->addFieldToFilter('customer_id', $this->customerSession->getCustomerId())
            ->addFieldToFilter('status', ['in' => ['complete', 'processing', 'pending', 'delivered']]);

        return (bool) $orders->getSize();
    }

    /**
     * Has the user provided their birthday
     *
     * @return boolean
     */
    public function hasCustomerProvidedBirthday(): bool
    {
        // Check if customer is logged in
        if (!$this->isLoggedIn()) {
            return false;
        }

        return $this->customerSession->getCustomer()->getDob() !== null;
    }

    /**
     * Get the logged-in customer's email
     *
     * @return string
     */
    public function getCustomerEmail(): string
    {
        return $this->customerSession->getCustomer()->getEmail();
    }

    /**
     * Is the customer on the mailing list
     *
     * @return boolean
     */
    public function isCustomerOnMailingList(): bool
    {
        // Check if customer is logged in
        if (!$this->isLoggedIn()) {
            return false;
        }

        $email = $this->customerSession->getCustomer()->getEmail();

        if ($email === null) {
            return false;
        }

        return (bool) $this->subscriberFactory->create()->loadBySubscriberEmail($this->customerSession->getCustomer()->getEmail(), 1)->getId();
    }

    /**
     * Get the subscribe form URL
     *
     * @return string|null
     */
    public function getSubscribeFormUrl(): ?string
    {
        return $this->urlHelper->getUrl('V1/vapeuk/newsletter');
    }

    /**
     * Get recaptcha
     *
     * @return string|null
     */
    public function getRecaptcha(): ?string
    {
        if ($this->isCustomerOnMailingList()) {
            return null;
        }

        return $this->config->get('captcha');
    }

    /**
     * Get recaptcha key
     *
     * @return string|null
     */
    public function getRecaptchaKey(): ?string
    {
        return $this->config->get('captcha_key');
    }

    /**
     * Get the login URL
     *
     * @return string
     */
    public function getLoginUrl(): string
    {
        return $this->customerUrl->getLoginUrl();
    }

    /**
     * Get the register URL
     *
     * @return string
     */
    public function getRegisterUrl(): string
    {
        return $this->customerUrl->getRegisterUrl();
    }

    /**
     * Get the Yotpo widget instance ID
     *
     * @return string|null
     */
    public function getYotpoWidgetInstanceId(): ?string
    {
        return $this->getData('yotpo_widget_instance_id');
    }
}
