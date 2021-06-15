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
    <form class="js-customer-form" action="{$link->getAdminLink('CustomerApplication', [], true)|escape:'html'}" method="post">
        <section>
            <div class="form-group row">
                <label for="fi_sdi" class="col-md-3 form-control-label">
                    SDI
                </label>
                <div class="col-md-6">
                    <input id="fi_sdi" class="form-control" name="sdi" type="text" value="mmn">
                    <span class="form-control-comment">
                        Only for Italian Client.
                    </span>
                </div>

                <div class="col-md-3 form-control-comment">
                    Opzionale
                </div>
            </div>
        </section>
        <footer class="form-footer clearfix">
            <input type="hidden" name="submitCreate" value="1">
            <button class="btn btn-primary form-control-submit float-xs-right" data-link-action="save-customer" type="submit">
                Salva
            </button>
        </footer>
    </form>
    {/block}