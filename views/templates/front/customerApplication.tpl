{**
* 2007-2020 PrestaShop and Contributors
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License 3.0 (AFL-3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* https://opensource.org/licenses/AFL-3.0
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* @author PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2020 PrestaShop SA and Contributors
* @license https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

{extends file='customer/page.tpl'}

{block name='page_title'}
{l s='Customer Application' mod='at_com_module'}
{/block}

{block name='page_content'}
<br>
<form class="js-customer-form" action="{$link->getAdminLink('CustomerApplication', [], true)|escape:'html'}"
    method="post">
    <section>
        <div class="form-group row ">
            <label for="fi_brands" class="col-md-3 form-control-label">
                Marchi Principali
            </label>
            <div class="col-md-6">
                <input id="fi_brands" class="form-control" name="brands" type="text" value="">
                <span class="form-control-comment">
                    Inserire i brand separati da virgola.
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
                Opzionale
            </div>
        </div>
        <div class="form-group row ">
            <label for="fi_tc_b2b" class="col-md-3 form-control-label">
            </label>
            <div class="col-md-6">
                <span class="custom-checkbox">
                    <label for="fi_tc_b2b">
                        <input id="fi_tc_b2b" name="tc_b2b" type="checkbox" value="1">
                        <span><i class="material-icons rtl-no-flip checkbox-checked"></i></span>
                        Grossista/B2B
                    </label>
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
            </div>
        </div>
        <div class="form-group row ">
            <label for="fi_tc_b2c" class="col-md-3 form-control-label">
            </label>
            <div class="col-md-6">
                <span class="custom-checkbox">
                    <label for="fi_tc_b2c">
                        <input id="fi_tc_b2c" name="tc_b2c" type="checkbox" value="1">
                        <span><i class="material-icons rtl-no-flip checkbox-checked"></i></span>
                        Negozio Fisico/B2C/off line store
                    </label>
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
            </div>
        </div>
        <div class="form-group row ">
            <label for="fi_website" class="col-md-3 form-control-label">
                Website
            </label>
            <div class="col-md-6">
                <input id="fi_website" class="form-control" name="website" type="text" value="www.a.com">
            </div>
            <div class="col-md-3 form-control-comment">
                Opzionale
            </div>
        </div>
        <div class="form-group row ">
            <label for="fi_tc_amazon" class="col-md-3 form-control-label">
                Amazon seller
            </label>
            <div class="col-md-6">
                <input id="fi_tc_amazon" class="form-control" name="tc_amazon" type="text" value="">
                <span class="form-control-comment">
                    Inserire il nome o il link del negozio amazon.
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
                Opzionale
            </div>
        </div>
        <div class="form-group row ">
            <label for="fi_tc_ebay" class="col-md-3 form-control-label">
                Amazon seller
            </label>
            <div class="col-md-6">
                <input id="fi_tc_ebay" class="form-control" name="tc_ebay" type="text" value="">
                <span class="form-control-comment">
                    Inserire il nome o il link del negozio ebay.
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
                Opzionale
            </div>
        </div>
        <div class="form-group row ">
            <label for="fi_tc_other" class="col-md-3 form-control-label">
                Altro
            </label>
            <div class="col-md-6">
                <input id="fi_tc_other" class="form-control" name="tc_other" type="text" value="">
            </div>
            <div class="col-md-3 form-control-comment">
                Opzionale
            </div>
        </div>

    </section>
    <footer class="form-footer clearfix">
        <input type="hidden" name="submitCreate" value="1">
        <button class="btn btn-primary form-control-submit float-xs-right" data-link-action="save-customer"
            type="submit">
            Salva
        </button>
    </footer>
</form>
{/block}