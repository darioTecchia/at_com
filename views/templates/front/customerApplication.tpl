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
    method="post" enctype="multipart/form-data">
    <section>
        <div class="form-group row ">
            <label for="attachment" class="col-md-3 form-control-label required">
                {l s='Chamber of Commerce View' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input id="attachment" class="form-control" name="attachment" type="file" value="{$customerApplication->attachment}" {if $customerApplication->attachment == ""}required{/if}>
                {if $customerApplication->attachment != ""}
                    <span class="form-control-comment">
                        <a target="_blank" href="/upload/{$customerApplication->attachment}">{l s='View' mod='at_com_module'} {l s='Attachment' mod='at_com_module'}</a>
                        <br>
                        <a download target="_blank" href="/upload/{$customerApplication->attachment}">{l s='Download' mod='at_com_module'} {l s='Attachment' mod='at_com_module'}</a>
                    </span>
                {/if}
            </div>
        </div>
        <div class="form-group row ">
            <label for="brands" class="col-md-3 form-control-label">
                {l s='Brands' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input id="brands" class="form-control" name="brands" type="text" required value="{$customerApplication->brands}">
                <span class="form-control-comment">
                    {l s='Enter brands separated by commas.' mod='at_com_module'}
                </span>
            </div>
        </div>
        <div class="form-group row ">
            <label for="b2b" class="col-md-3 form-control-label">
            </label>
            <div class="col-md-6">
                <span class="custom-checkbox">
                    <label for="b2b">
                        <input id="b2b" name="b2b" type="checkbox" value="1" {if $customerApplication->b2b eq 1}checked{/if}>
                        <span><i class="material-icons rtl-no-flip checkbox-checked"></i></span>
                        {l s='Wholesaler/B2B' mod='at_com_module'}
                    </label>
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
            </div>
        </div>
        <div class="form-group row ">
            <label for="b2c" class="col-md-3 form-control-label">
            </label>
            <div class="col-md-6">
                <span class="custom-checkbox">
                    <label for="b2c">
                        <input id="b2c" name="b2c" type="checkbox" value="1" {if $customerApplication->b2c eq 1}checked{/if}>
                        <span><i class="material-icons rtl-no-flip checkbox-checked"></i></span>
                        {l s='Physical store/B2C/off line store' mod='at_com_module'}
                    </label>
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
            </div>
        </div>
        <div class="form-group row ">
            <label for="website" class="col-md-3 form-control-label">
                {l s='Website' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input id="website" class="form-control" name="website" type="text" value="{$customerApplication->website}">
            </div>
            <div class="col-md-3 form-control-comment">
                {l s='Optional' mod='at_com_module'}
            </div>
        </div>
        <div class="form-group row ">
            <label for="amazon" class="col-md-3 form-control-label">
                {l s='Amazon seller' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input id="amazon" class="form-control" name="amazon" type="text" value="{$customerApplication->amazon}">
                <span class="form-control-comment">
                    {l s='Enter the name or link to the amazon store.' mod='at_com_module'}
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
                {l s='Optional' mod='at_com_module'}
            </div>
        </div>
        <div class="form-group row ">
            <label for="ebay" class="col-md-3 form-control-label">
                {l s='Ebay seller' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input id="ebay" class="form-control" name="ebay" type="text" value="{$customerApplication->ebay}">
                <span class="form-control-comment">
                    {l s='Enter the name or link to the ebay store.' mod='at_com_module'}
                </span>
            </div>
            <div class="col-md-3 form-control-comment">
                {l s='Optional' mod='at_com_module'}
            </div>
        </div>
        <div class="form-group row ">
            <label for="other" class="col-md-3 form-control-label">
                {l s='Other marketplace' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input id="other" class="form-control" name="other" type="text" value="{$customerApplication->other}">
            </div>
            <div class="col-md-3 form-control-comment">
                {l s='Optional' mod='at_com_module'}
            </div>
        </div>

    </section>
    <footer class="form-footer clearfix">
        <input type="hidden" name="submitCreate" value="1">
        <button class="btn btn-primary form-control-submit float-xs-right" data-link-action="save-customer"
            type="submit">
            {l s='Save' mod='at_com_module'}
        </button>
    </footer>
</form>
{/block}