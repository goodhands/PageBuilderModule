define([
    'jquery',
    'splide',
    'moment',
], function (jQuery, Splide, moment) {
    'use strict';

    let storedConfig = {};
    let dobError = 'Sorry, we could not save your date of birth. Please try again later.';

    function checkSwellApi() {
        if (typeof swellAPI !== 'undefined') {
            afterSwellApiSetup();
        } else {
            jQuery(document).on("swell:setup", function() {
                afterSwellApiSetup();
            });
        }
    }

    function afterSwellApiSetup(retriesLeft = 20) {
        const customerDetails = swellAPI.getCustomerDetails();
        if ((!customerDetails || !customerDetails.vipTier) && retriesLeft > 0) {
            setTimeout(() => {
                afterSwellApiSetup(retriesLeft - 1);
            }, 500);
            return;
        }

        let vipTierLevel = customerDetails.vipTier.name;

        const allowedMocks = ['bronze', 'silver', 'gold', 'platinum'];
        const mockLevel = new URL(window.location).searchParams.get('mock');
        // Allow mocking for testing purposes, if not on production
        if (allowedMocks.includes(mockLevel) && window.origin !== 'https://www.vapeuk.co.uk') {
            vipTierLevel = mockLevel;
        }

        setupTierRewards(vipTierLevel);
        setupVipCard(customerDetails, vipTierLevel);
    }

    function setupTierRewards(tierLevel) {
        if (tierLevel.toLowerCase() !== 'base') {
            // Activate tier level rewards for each way to earn depending on the users tier
            jQuery('.js-loyalty-value-tier--' + tierLevel).addClass('loyalty-value-active');
            jQuery('.js-loyalty-value-tier--all-' + tierLevel).addClass('loyalty-value-active');
            jQuery('.loyalty-ways__reward-tier--' + tierLevel).addClass('loyalty-ways__reward-tier--active');
            jQuery('.loyalty-rewards-grid--desktop').addClass('loyalty-rewards-grid--' + tierLevel);
            jQuery('.loyalty-rewards-grid--mobile.loyalty-rewards-grid--' + tierLevel).addClass('loyalty-rewards-grid--active');
            goToRewardsTierSlide(tierLevel);
        }

        // Lock the reward ways and iterate through the tiers to unlock until the user's tier is reached
        var tierLevels = ['base', 'bronze', 'silver', 'gold', 'platinum'];
        jQuery('.loyalty-ways__row:not(.loyalty-ways__account-panel)').addClass('loyalty-ways__row--locked');
        var unlockIndex = tierLevels.indexOf(tierLevel);
        tierLevels.forEach(function(tier, index) {
            if (index <= unlockIndex) {
                jQuery('.loyalty-ways__reward-tier--' + tier).closest('.loyalty-ways__row').removeClass('loyalty-ways__row--locked');
            }
        });

        // Check if the customer has already submitted their birthday, if logged in and not already submitted, set up the form submission
        if (typeof swellConfig.customer.email !== 'undefined') {
            if (typeof swellConfig.customer.birthday !== 'undefined') {
                if (!jQuery('.loyalty-ways__form-optin--birthday').hasClass('loyalty-ways__form-optin--complete')) {
                    jQuery('.loyalty-ways__form-optin--birthday').addClass('loyalty-ways__form-optin--complete');
                }
            } else {
                // set up the birthday form submission for logged in users that have not submitted their birthday
                jQuery('.loyalty-ways__form-optin--birthday form').on('submit', submitDob);
                jQuery('#dob-day, #dob-month').on('change', removeDobValidation);
            }

            if (!jQuery('.loyalty-ways__form-optin--newsletter').hasClass('loyalty-ways__form-optin--complete')) {
                jQuery('.loyalty-ways__form-optin--newsletter form').on('submit', onSubmitEmailSignup);
            }
        }
    }

    function displayError(error) {
        jQuery('input#subscribe-email')
            .after(jQuery("<div/>")
            .addClass('mage-error')
            .html(error));
        jQuery('.loyalty-ways__form-optin--newsletter form').css({'align-items': 'center'});
    }

    function onSubmitEmailSignup(e) {
        e.preventDefault();
        jQuery('input#subscribe-email').next('.mage-error').remove();
        jQuery('.loyalty-ways__form-optin--newsletter form').css({'align-items': 'flex-end'});
        if (storedConfig.recaptcha) {
            grecaptcha.execute();
        } else {
            onCallback('no-recaptcha');
        }
    }

    function onCallback(token) {
        jQuery.ajax({
            type:'PUT',
            contentType: 'application/json',
            data: JSON.stringify({
                email: jQuery('#subscribe-email').val(),
                token: token
            }),
            url:'/rest/V1/vapeuk/newsletter'
        }).done(function (response) {
            response = JSON.parse(response);
            if(response.error) {
                displayError(response.error);
            }
            else if(response.success) {
                jQuery('.loyalty-ways__form-optin--newsletter').addClass('loyalty-ways__form-optin--success');
            }
        }).fail(function (response) {
            displayError(response.responseJSON.message);
        })

        return false;
    }

    function submitDob(e) {
        e.preventDefault();
        var day = jQuery('#dob-day');
        var month = jQuery('#dob-month');
        var dayValue = day.val();
        var monthValue = month.val();
        var valid = true;

        if (dayValue == '') {
            day.addClass('is-invalid');
            valid = false;
        }
        if (monthValue == '') {
            month.addClass('is-invalid');
            valid = false;
        }

        if (valid) {
            var button = jQuery('.loyalty-ways__form-optin--birthday button');
            button.prop('disabled', true);
            button.css('min-width', button.width());
            button.text('Saving...');

            jQuery.ajax('/vuk_yotpovip/index/updateDob', {
                data: {
                    day: dayValue,
                    month: monthValue,
                },
                type: 'POST'
            }).done(function(data) {
                if (data.success) {
                    jQuery('.loyalty-ways__form-optin--birthday').addClass('loyalty-ways__form-optin--complete');
                } else {
                    alert(dobError);
                }
            }).fail(function() {
                alert(dobError);
            });
        }
    }

    function removeDobValidation() {
        jQuery(this).removeClass('is-invalid');
    }

    function setupRewardsGridMobileSplide() {
        var rewardsGrid = jQuery('.rewards-grid-mobile-container');
        if (rewardsGrid.length) {
            var rewardsGridSplide = new Splide(rewardsGrid[0], {
                type: 'slide',
                perPage: 1,
                perMove: 1,
                pagination: false,
                gap: '1rem'
            }).mount();
            rewardsGrid.data('splideInstance', rewardsGridSplide);
        }
    }

    function goToRewardsTierSlide(tierLevel) {
        var rewardsGrid = jQuery('.rewards-grid-mobile-container');
        var rewardsGridSplide = rewardsGrid.data('splideInstance');

        if (rewardsGridSplide) {
            var targetSlideIndex = null;
            jQuery('.loyalty-rewards-grid--mobile').each(function(index) {
                if (jQuery(this).hasClass('loyalty-rewards-grid--' + tierLevel)) {
                    jQuery(this).addClass('loyalty-rewards-grid--active');
                    targetSlideIndex = index;
                    return false;
                }
            });
            if (targetSlideIndex !== null) {
                rewardsGridSplide.go(targetSlideIndex);
            }
        }
    }


    function setupVipCard(customerDetails, tierName) {
        jQuery('.vip-rewards__card-svg').hide();
        jQuery('.js-vip-badge-' + tierName.toLowerCase()).show();
        const rewardPoints = customerDetails.pointsBalance;
        jQuery('.js-vip-points-current').text(rewardPoints.toLocaleString());
        const totalPoints = customerDetails.pointsEarned;
        jQuery('.js-vip-points-total').text(totalPoints.toLocaleString());
        const memberSince = moment(customerDetails.created_at).format('DD/MM/YY');
        jQuery('.js-vip-member-since').text(memberSince);

        jQuery('.js-vip-rewards-status-header-tier').text(tierName.toUpperCase()).addClass('tier-colour--' + tierName.toLowerCase());
    }

    function setUpYotpoWidgetObserver() {
        const targetNode = document.querySelector('.rewards-status-widget');

        if (targetNode) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        const yotpoDescription = targetNode.querySelector('.yotpo-description');
                        if (yotpoDescription) {
                            const innerTextElement = yotpoDescription.querySelector('.yotpo-inner-text');

                            if (innerTextElement) {
                                observer.disconnect();
                                handleTextUpdate(innerTextElement);
                                observer.observe(targetNode, {
                                    childList: true,
                                    subtree: true,
                                });
                            }
                        }
                    }
                });
            });

            observer.observe(targetNode, {
                childList: true,
                subtree: true,
            });
        }
    }

    function handleTextUpdate(element) {
        const numberRegex = /\d+/;
        const currentText = element.textContent;
        const match = currentText.match(numberRegex);

        if (match) {
            const number = match[0];
            const newText = currentText.replace(number, `<span class="highlight-number">${number}</span>`);
            element.innerHTML = newText;
        }
    }

    return function (config, element) {
        storedConfig = config;
        window.emailSignupRecaptchaCallback = onCallback;
        setupRewardsGridMobileSplide();
        checkSwellApi();
        setUpYotpoWidgetObserver();
    };
});
