<form id="payment_price_rule_module_form" class="defaultForm form-horizontal"
  action="http://localhost/shop-admin/index.php?controller=AdminModules&configure=at_com_module&tab_module=administration&module_name=at_com_module&token=91d19d8f611d6b94e6c895065ce48451"
  method="post" enctype="multipart/form-data" novalidate="">

  <input type="hidden" name="submitAt_com_modulePaymentRule" value="1">

  <div class="panel" id="fieldset_0">
    <div class="panel-heading">
      <i class="icon-credit-card"></i> {l s='Payment Methods Settings' mod='at_com_module'}
    </div>
    <p>
      {l s='In questa sezione puoi configurare la politica di sconto/sovrapprezzo per i diversi metodi di pagamento.'
      mod='at_com_module'}
    </p>
    <div class="form-wrapper">
      <div class="form-group">
        <div class="choice-table table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>{l s='Payment Method' mod='at_com_module'}</th>
                <th class="text-center">{l s='Active' mod='at_com_module'}</th>
                <th class="text-center">{l s='Price Rule' mod='at_com_module'}</th>
              </tr>
            </thead>
            <tbody>
              {foreach from=$payment_methods item=payment_method}
                <tr>
                  <td>
                    {$payment_method->displayName}
                    <input type="hidden" name="form[price_rules][{$payment_method->id}][id_payment_method]" value="{$payment_method->id}" />
                  </td>
                  <td class="text-center">
                    <div class="checkbox">
                      <div class="md-checkbox md-checkbox-inline">
                        <label>
                          <input type="checkbox"
                            {if isset($payment_method->rule_active) && $payment_method->rule_active}
                              checked="checked"
                            {/if}
                            id="form_payment_module_preferences_group_restrictions_{$payment_method->id}"
                            name="form[price_rules][{$payment_method->id}][active]" value="1">
                          <i class="md-checkbox-control"></i>
                        </label>
                      </div>
                    </div>
                  </td>
                  <td class="text-center">
                    <div class="input-group">
                      <input class="input form-control" type="number" step="0.1"
                        {if isset($payment_method->rule)}
                          value="{$payment_method->rule}"
                        {else}
                          value="0"
                        {/if}
                        name="form[price_rules][{$payment_method->id}][rule]" />
                      <span class="input-group-addon">%</span>
                    </div>
                  </td>
                </tr>
              {/foreach}
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="panel-footer">
      <button type="submit" value="1" id="module_form_submit_btn" name="submitAt_com_modulePaymentRule"
        class="btn btn-default pull-right">
        <i class="process-icon-save"></i> Salva
      </button>
    </div>

  </div>
</form>