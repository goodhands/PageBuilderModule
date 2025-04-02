<?php
namespace Vapeuk\PageBuilderRewardsContent\Model\Config;

/**
 * Rewards Content Config
 *
 * Contains all the configuration for rewards tiers, qualification and remitance
 */
class RewardsContentConfig
{
    /**
     * @const array WAYS_TO_EARN_TYPES The types of ways to earn rewards and their respective reward types
     */
    const WAYS_TO_EARN_TYPES = [
        'ways_to_earn_create_an_account' => 'fixed',
        'ways_to_earn_place_order' => 'tiered',
        'ways_to_earn_join_mailing_list' => 'fixed',
        // Blog post reward currently disabled:
        //'ways_to_earn_read_a_blog_post' => 'fixed',
        'ways_to_earn_review_a_product' => 'fixed',
        'ways_to_earn_add_a_photo' => 'fixed',
        'ways_to_earn_reach_the_next_tier' => 'tiered',
        'ways_to_earn_account_anniversary' => 'tiered',
        'ways_to_earn_celebrate_your_birthday' => 'tiered',
        'ways_to_earn_double_points_e_liquids' => 'fixed',
        'ways_to_earn_monthly_double_points_products' => 'fixed',
        'ways_to_earn_double_points_days' => 'tiered',
        'ways_to_earn_new_year_bonus' => 'tiered',
        'ways_to_earn_bfcm_offers' => 'fixed',
        'ways_to_earn_loyalty_punch_card' => 'fixed'
    ];

    const REWARDS_TIERS = [
        'bronze'    => 'Bronze',
        'silver'    => 'Silver',
        'gold'      => 'Gold',
        'platinum'  => 'Platinum'
    ];

    const REWARDS_TIERS_ANNUAL_SPEND = [
        'bronze'    => ['min' => 1, 'max' => 499],
        'silver'    => ['min' => 500, 'max' => 999],
        'gold'      => ['min' => 1000, 'max' => 2499],
        'platinum'  => ['min' => 2500, 'max' => null]
    ];

    const REWARDS = [
        'fixed' => [
            'ways_to_earn_create_an_account' => [
                'rewards' => [50],
                'reward_label' => 'Points',
                'tiers' => ['bronze'],
                'icon' => '',
                'label' => 'Create a Vape UK account'
            ],
            'ways_to_earn_join_mailing_list' => [
                'rewards' => [10],
                'reward_label' => 'Points',
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'optin' => 'newsletter',
                'icon' => 'subscribe',
                'icon_width' => 200,
                'icon_height' => 168,
                'label' => 'Subscribe to our newsletter'
            ],
            'ways_to_earn_read_a_blog_post' => [
                'rewards' => [15],
                'reward_label' => 'Points',
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'icon' => 'read-blog',
                'icon_width' => 200,
                'icon_height' => 169,
                'label' => 'Read a post from our latest news'
            ],
            'ways_to_earn_review_a_product' => [
                'rewards' => [15],
                'reward_label' => 'Points',
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'icon' => 'review-products',
                'icon_width' => 200,
                'icon_height' => 159,
                'label' => 'Review your products'
            ],
            'ways_to_earn_add_a_photo' => [
                'rewards' => [25],
                'reward_label' => 'Points',
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'icon' => 'photo-review-products',
                'icon_width' => 200,
                'icon_height' => 158,
                'label' => 'Add a photo to your review'
            ],
            'ways_to_earn_bfcm_offers' => [
                'rewards' => [24],
                'reward_label' => 'Hours',
                'tiers' => ['gold', 'platinum'],
                'icon' => '24-hours-bfcm',
                'icon_width' => 200,
                'icon_height' => 193,
                'label' => '24 hours extra on BFCM offers'
            ],
            'ways_to_earn_loyalty_punch_card' => [
                'rewards' => [125],
                'reward_label' => 'Points',
                'tiers' => ['platinum'],
                'icon' => 'platinum-punchcard',
                'icon_width' => 220,
                'icon_height' => 150,
                'label' => 'Loyalty "Punch Card"'
            ],
            'ways_to_earn_double_points_e_liquids' => [
                'rewards' => ['2x'],
                'reward_label' => '',
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'icon' => 'vuk-e-liquids-cluster',
                'icon_width' => 200,
                'icon_height' => 191,
                'label' => 'Double points on VUK e-liquids'
            ],
            'ways_to_earn_monthly_double_points_products' => [
                'rewards' => ['2x'],
                'reward_label' => '',
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'icon' => 'double-points-products',
                'icon_width' => 200,
                'icon_height' => 194,
                'label' => 'Monthly double points products'
            ],
        ],
        'tiered' => [
            'ways_to_earn_place_order' => [
                'reward_label' => 'Point(s)',
                'rewards' => [1, 1.25, 1.5, 1.75],
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'modifier_classes' => ['value_points' => 'small'],
                'icon' => 'pounds-points',
                'icon_width' => 200,
                'icon_height' => 192,
                'label' => 'Points per £1 spent'
            ],
            'ways_to_earn_reach_the_next_tier' => [
                'reward_label' => 'Points',
                'rewards' => [25, 50, 100],
                'tiers' => ['silver', 'gold', 'platinum'],
                'icon' => 'new-tier-bonus',
                'icon_width' => 240,
                'icon_height' => 150,
                'label' => 'Reach the next VIP tier'
            ],
            'ways_to_earn_account_anniversary' => [
                'reward_label' => 'Points',
                'rewards' => [50, 60, 75, 100],
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'icon' => 'anniversary',
                'icon_width' => 200,
                'icon_height' => 191,
                'label' => 'Account anniversary'
            ],
            'ways_to_earn_celebrate_your_birthday' => [
                'reward_label' => 'Points',
                'rewards' => [100, 150, 200, 250],
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'optin' => 'birthday',
                'icon' => 'happy-birthday',
                'icon_width' => 200,
                'icon_height' => 191,
                'label' => 'Celebrate your birthday'
            ],
            'ways_to_earn_double_points_days' => [
                'reward_label' => 'Day(s)',
                'rewards' => [1, 2, 3, 4],
                'tiers' => ['bronze', 'silver', 'gold', 'platinum'],
                'icon' => 'double-reward-calendar',
                'icon_width' => 200,
                'icon_height' => 171,
                'label' => 'Double points days (per year)'
            ],
            'ways_to_earn_new_year_bonus' => [
                'reward_label' => 'Points',
                'rewards' => [25, 50, 100],
                'tiers' => ['silver', 'gold', 'platinum'],
                'icon' => 'new-year',
                'icon_width' => 200,
                'icon_height' => 198,
                'label' => 'New year bonus on next order'
            ],
        ]
    ];

    const REDEMPTION_REWARDS = [
        [
            'points' => 125,
            'value' => 5,
        ],
        [
            'points' => 250,
            'value' => 10,
        ],
        [
            'points' => 450,
            'value' => 20,
        ],
        [
            'points' => 600,
            'value' => 30,
        ]
    ];
}
