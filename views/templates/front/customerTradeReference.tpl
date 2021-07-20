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
{l s='Customer Trade Reference' mod='at_com_module'}
{/block}

{block name='page_content'}
<br>
<form class="js-customer-form" action="{$link->getAdminLink('CustomerTradeReference', [], true)|escape:'html'}" method="post">
    <section>

        <div class="form-group row ">
            <label for="name" class="col-md-3 form-control-label">
                {l s='Name' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input required id="name" class="form-control" name="name" type="text"
                    value="{$customerTradeReference->name}">
            </div>
            <div class="col-md-3 form-control-comment">
            </div>
        </div>

        <div class="form-group row ">
            <label for="email" class="col-md-3 form-control-label">
                {l s='Email' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input required id="email" class="form-control" name="email" type="text"
                    value="{$customerTradeReference->email}">
            </div>
            <div class="col-md-3 form-control-comment">
            </div>
        </div>

        <div class="form-group row ">
            <label for="phone" class="col-md-3 form-control-label">
                {l s='Phone' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input required id="phone" class="form-control" name="phone" type="text"
                    value="{$customerTradeReference->phone}">
            </div>
            <div class="col-md-3 form-control-comment">
            </div>
        </div>

        <div class="form-group row ">
            <label for="phone_mobile" class="col-md-3 form-control-label">
                {l s='Mobile phone' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input required id="phone_mobile" class="form-control" name="phone_mobile" type="text"
                    value="{$customerTradeReference->phone_mobile}">
            </div>
            <div class="col-md-3 form-control-comment">
            </div>
        </div>

        <div class="form-group row ">
            <label for="buyer_group" class="col-md-3 form-control-label">
                {l s='Buyer group' mod='at_com_module'}
            </label>
            <div class="col-md-6">
                <input required id="buyer_group" class="form-control" name="buyer_group" type="text"
                    value="{$customerTradeReference->buyer_group}">
            </div>
            <div class="col-md-3 form-control-comment">
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