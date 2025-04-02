function _inheritsLoose(subClass, superClass) { subClass.prototype = Object.create(superClass.prototype); subClass.prototype.constructor = subClass; _setPrototypeOf(subClass, superClass); }
function _setPrototypeOf(o, p) { _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) { o.__proto__ = p; return o; }; return _setPrototypeOf(o, p); }

define([
    "Magento_PageBuilder/js/mass-converter/widget-directive-abstract",
    "Magento_PageBuilder/js/utils/object"
], function (widgetDirectiveAbstract, object) {
    
    /**
     * Enables the settings of the content type to be stored as a widget directive.
    *
    * @api
    */
    var WidgetDirective = /*#__PURE__*/function (_widgetDirectiveAbstr) {
        "use strict";
    
        _inheritsLoose(WidgetDirective, _widgetDirectiveAbstr);
    
        function WidgetDirective() {
          return _widgetDirectiveAbstr.apply(this, arguments) || this;
        }
    
        var _proto = WidgetDirective.prototype;

        /**
         * Convert value to internal format
         *
         * @param {object} data
         * @param {object} config
         * @returns {object}
         */
        _proto.fromDom = function fromDom(data, config) {
            console.log('Data:', data);
            var attributes = _widgetDirectiveAbstr.prototype.fromDom.call(this, data, config);
            data.join_the_club_content = attributes.join_the_club_content;
            data.the_club_content = attributes.the_club_content;
            data.points_mean_discounts_content = attributes.points_mean_discounts_content;
            data.not_logged_in_content = attributes.not_logged_in_content;
            data.logged_in_content_no_tier_status = attributes.logged_in_content_no_tier_status;
            data.ways_to_earn_place_order = attributes.ways_to_earn_place_order;
            data.ways_to_earn_join_mailing_list = attributes.ways_to_earn_join_mailing_list;
            data.ways_to_earn_read_a_blog_post = attributes.ways_to_earn_read_a_blog_post;
            data.ways_to_earn_review_a_product = attributes.ways_to_earn_review_a_product;
            data.ways_to_earn_add_a_photo = attributes.ways_to_earn_add_a_photo;
            data.ways_to_earn_reach_the_next_tier = attributes.ways_to_earn_reach_the_next_tier;
            data.ways_to_earn_account_anniversary = attributes.ways_to_earn_account_anniversary;
            data.ways_to_earn_celebrate_your_birthday = attributes.ways_to_earn_celebrate_your_birthday;
            data.ways_to_earn_double_points_e_liquids = attributes.ways_to_earn_double_points_e_liquids;
            data.ways_to_earn_monthly_double_points_products = attributes.ways_to_earn_monthly_double_points_products;
            data.ways_to_earn_double_points_days = attributes.ways_to_earn_double_points_days;
            data.ways_to_earn_new_year_bonus = attributes.ways_to_earn_new_year_bonus;
            data.ways_to_earn_bfcm_offers = attributes.ways_to_earn_bfcm_offers;
            data.ways_to_earn_loyalty_punch_card = attributes.ways_to_earn_loyalty_punch_card;
            data.middle_banner_content = attributes.middle_banner_content;
            data.footer_banner_content = attributes.footer_banner_content;
            data.yotpo_widget_instance_id = attributes.yotpo_widget_instance_id;
            return data;
        }

        /**
         * Convert value to knockout format
         *
         * @param {object} data
         * @param {object} config
         * @returns {object}
         */
        _proto.toDom = function toDom(data, config) {
            var attributes = {
                type: "Vapeuk\\PageBuilderRewardsContent\\Block\\Widget\\RewardsContent",
                join_the_club_content: data.join_the_club_content,
                the_club_content: data.the_club_content,
                points_mean_discounts_content: data.points_mean_discounts_content,
                not_logged_in_content: data.not_logged_in_content,
                logged_in_content_no_tier_status: data.logged_in_content_no_tier_status,
                ways_to_earn_place_order: data.ways_to_earn_place_order,
                ways_to_earn_join_mailing_list: data.ways_to_earn_join_mailing_list,
                ways_to_earn_read_a_blog_post: data.ways_to_earn_read_a_blog_post,
                ways_to_earn_review_a_product: data.ways_to_earn_review_a_product,
                ways_to_earn_add_a_photo: data.ways_to_earn_add_a_photo,
                ways_to_earn_reach_the_next_tier: data.ways_to_earn_reach_the_next_tier,
                ways_to_earn_account_anniversary: data.ways_to_earn_account_anniversary,
                ways_to_earn_celebrate_your_birthday: data.ways_to_earn_celebrate_your_birthday,
                ways_to_earn_double_points_e_liquids: data.ways_to_earn_double_points_e_liquids,
                ways_to_earn_monthly_double_points_products: data.ways_to_earn_monthly_double_points_products,
                ways_to_earn_double_points_days: data.ways_to_earn_double_points_days,
                ways_to_earn_new_year_bonus: data.ways_to_earn_new_year_bonus,
                ways_to_earn_bfcm_offers: data.ways_to_earn_bfcm_offers,
                ways_to_earn_loyalty_punch_card: data.ways_to_earn_loyalty_punch_card,
                footer_banner_content: data.footer_banner_content,
                middle_banner_content: data.middle_banner_content,
                yotpo_widget_instance_id: data.yotpo_widget_instance_id,
                type_name: "CMS Rewards Content Block"
            };

            if (!attributes.not_logged_in_content
                || !attributes.join_the_club_content
                || !attributes.the_club_content
                || !attributes.points_mean_discounts_content
                || !attributes.logged_in_content_no_tier_status
                || !attributes.ways_to_earn_place_order
                || !attributes.ways_to_earn_join_mailing_list
                || !attributes.ways_to_earn_read_a_blog_post
                || !attributes.ways_to_earn_review_a_product
                || !attributes.ways_to_earn_add_a_photo
                || !attributes.ways_to_earn_reach_the_next_tier
                || !attributes.ways_to_earn_account_anniversary
                || !attributes.ways_to_earn_celebrate_your_birthday
                || !attributes.ways_to_earn_double_points_e_liquids
                || !attributes.ways_to_earn_monthly_double_points_products
                || !attributes.ways_to_earn_double_points_days
                || !attributes.ways_to_earn_new_year_bonus
                || !attributes.ways_to_earn_bfcm_offers
                || !attributes.ways_to_earn_loyalty_punch_card
                || !attributes.footer_banner_content
                || !attributes.middle_banner_content
                || !attributes.yotpo_widget_instance_id
            ) {
                return data;
            }

            (0, object.set)(data, config.html_variable, this.buildDirective(attributes));

            return data;
        };

    return WidgetDirective;
  }(widgetDirectiveAbstract);

  return WidgetDirective;
});