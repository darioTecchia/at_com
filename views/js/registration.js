/**
* 2007-2021 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2021 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/

// import CountryStateSelectionToggler from '@components/country-state-selection-toggler';
/**
 * Displays, fills or hides State selection block depending on selected country.
 *
 * Usage:
 *
 * <!-- Country select must have unique identifier & url for states API -->
 * <select name="id_country" id="id_country" states-url="path/to/states/api">
 *   ...
 * </select>
 *
 * <!-- If selected country does not have states, then this block will be hidden -->
 * <div class="js-state-selection-block">
 *   <select name="id_state">
 *     ...
 *   </select>
 * </div>
 *
 * In JS:
 *
 * new CountryStateSelectionToggler('#id_country', '#id_state', '.js-state-selection-block');
 */
class CountryStateSelectionToggler {
  constructor(countryInputSelector, countryStateSelector) {
    this.$countryStateSelector = $(countryStateSelector);
    this.$countryInput = $(countryInputSelector);

    this.$countryInput.on('change', () => this.change());

    this.toggle();

    return {};
  }

  /**
   * Change State selection
   *
   * @private
   */
  change() {
    const countryId = this.$countryInput.val();
    if (countryId === '') {
      return;
    }
    $.get({
      url: this.$countryInput.data('states-url'),
      dataType: 'json',
      data: {
        'filter[id_country]': '[' + countryId + ']',
        output_format: 'JSON',
        display: 'full',
        language: prestashop.language.id
      },
    }).then((response) => {
      this.$countryStateSelector.empty();

      if (response.states) {
        Object.keys(response.states).forEach((value) => {
          this.$countryStateSelector.append(
            $('<option></option>')
              .attr('value', response.states[value].id)
              .text(response.states[value].name)
          );
        });
      }

      this.toggle();
    }).catch((response) => {
      if (typeof response.responseJSON !== 'undefined') {
        showErrorMessage(response.responseJSON.message);
      }
    });
  }

  toggle() {
    if (this.$countryStateSelector.find('option').length > 0) {
      console.log('fadein');
      this.$countryStateSelector.attr('disabled', false);
      this.$countryStateSelector.closest('.form-group').fadeIn();
    } else {
      console.log('fadeout');
      this.$countryStateSelector.attr('disabled', 'disabled');
      this.$countryStateSelector.closest('.form-group').fadeOut();
    }
  }

}

// import CountryDniRequiredToggler from '@components/country-dni-required-toggler';
/**
 * Toggle DNI input requirement on country selection
 *
 * Usage:
 *
 * <!-- Country select options must have need_dni attribute when needed -->
 * <select name="id_country" id="id_country" states-url="path/to/states/api">
 *   ...
 *   <option value="6" need_dni="1">Spain</value>
 *   ...
 * </select>
 *
 * In JS:
 *
 * new CountryDniRequiredToggler('#id_country', '#id_country_dni', 'label[for="id_country_dni"]');
 */
class CountryDniRequiredToggler {
  constructor(countryInputSelector, countryDniInput) {
    this.$countryDniInput = $(countryDniInput);
    this.$countryInput = $(countryInputSelector);
    this.countryInputSelectedSelector = `${countryInputSelector}>option:selected`;
    this.commentLabel = this.$countryDniInput.closest('.form-group').find('.form-control-comment');

    // If field is required regardless of the country
    // keep it required
    if (this.$countryDniInput.attr('required')) {
      return;
    }

    this.$countryInput.on('change', () => this.toggle());

    // toggle on page load
    this.toggle();
  }

  /**
   * Toggles DNI input required
   *
   * @private
   */
  toggle() {
    if (1 === parseInt($(this.countryInputSelectedSelector).attr('need_dni'), 10)) {
      $(this.commentLabel).fadeOut();
      this.$countryDniInput.prop('required', true);
    } else {
      this.$countryDniInput.prop('required', false);
      $(this.commentLabel).fadeIn();
    }
  }
}

// import CountryPostcodeRequiredToggler from '@components/country-postcode-required-toggler';
/**
 * Toggle Postcode input requirement on country selection
 *
 * Usage:
 *
 * <!-- Country select options must have need_postcode attribute when needed -->
 * <select name="id_country" id="id_country" states-url="path/to/states/api">
 *   ...
 *   <option value="6" need_postcode="1">Spain</value>
 *   ...
 * </select>
 *
 * In JS:
 *
 * new CountryPostcodeRequiredToggler('#id_country', '#id_country_postcode', 'label[for="id_country_postcode"]');
 */
class CountryPostcodeRequiredToggler {
  constructor(countryInputSelector, countryPostcodeInput) {
    this.$countryPostcodeInput = $(countryPostcodeInput);
    this.$countryInput = $(countryInputSelector);
    this.countryInputSelectedSelector = `${countryInputSelector}>option:selected`;
    this.commentLabel = this.$countryPostcodeInput.closest('.form-group').find('.form-control-comment');

    // If field is required regardless of the country
    // keep it required
    if (this.$countryPostcodeInput.attr('required')) {
      return;
    }

    this.$countryInput.on('change', () => this.toggle());

    // toggle on page load
    this.toggle();
  }

  /**
   * Toggles Postcode input required
   *
   * @private
   */
  toggle() {
    if (1 === parseInt($(this.countryInputSelectedSelector).attr('need_postcode'), 10)) {
      $(this.commentLabel).fadeOut();
      this.$countryPostcodeInput.prop('required', true);
    } else {
      this.$countryPostcodeInput.prop('required', false);
      $(this.commentLabel).fadeIn();
    }
  }
}

$(document).ready(() => {
  new CountryStateSelectionToggler('#fi_id_country', '#fi_id_state');
  new CountryDniRequiredToggler('#fi_id_country', '#fi_dni');
  new CountryPostcodeRequiredToggler('#fi_id_country', '#fi_id_country_postcode');

  new CountryStateSelectionToggler('#fi_op_id_country', '#fi_op_id_state');
  new CountryDniRequiredToggler('#fi_op_id_country', '#fi_op_dni');
  new CountryPostcodeRequiredToggler('#fi_op_id_country', '#fi_op_id_country_postcode');
});