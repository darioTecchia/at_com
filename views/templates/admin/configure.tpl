<div class="panel">
    <h3>{l s='@.com module' mod='at_com_module'}</h3>
    <p>
        <strong>{l s='Benvenuto nel modulo @.com!' mod='at_com_module'}</strong><br />
        {l s='In questa pagina puoi configurare i parametri necessari al funzionamento del modulo.'
        mod='at_com_module'}
    </p>
</div>

<form id="payment_price_rule_module_form" class="defaultForm form-horizontal"
    action="{$actionUrl}"
    method="post" enctype="multipart/form-data" novalidate="">

    <input type="hidden" name="submitAt_com_moduleCreateCron" value="1">

    <div class="panel" id="fieldset_0">
        <div class="panel-heading">
            <i class="icon-user"></i> {l s='Disable Expired Customer via Cron' mod='at_com_module'} <i
                class="icon-time"></i>
        </div>

        <div>
            <p>
                <strong>{l s='In questa sezione puoi attivare/disattivare la disattivazione automatizzata dei
                    customer temporanei.'
                    mod='at_com_module'}</strong> <br>
                {l s='Necessita del seguente modulo installato e attivato: ' mod='at_com_module'}
                <a href="https://github.com/darioTecchia/cronjobs" target="_blank">cronjobs</a>
            </p>

            <p>
                {l s='Stato installazione modulo: ' mod='at_com_module'}
                {if !$cronJobsModule}
                    <i class="icon-remove color_danger"></i>
                {else}
                    <i class="icon-ok process-icon-ok color_success"></i>
                {/if}
                <br>
                    {l s='Stato attivazione modulo: ' mod='at_com_module'}
                    {if !$cronJobsModule}
                    <i class="icon-remove color_danger"></i>
                {else}
                    {if $cronJobsModule->active}
                        <i class="icon-ok process-icon-ok color_success"></i>
                    {else}
                        <i class="icon-remove color_danger"></i>
                    {/if}
                {/if}
            </p>
            {if $cronJobsModule && $cronJobsModule->active}
                <p>
                    <button {if $exsistCustomerCron}disabled{/if} class="btn btn-primary" {if !$exsistCustomerCron}name="create_customer_cron" value="1"{/if}>
                        {if $exsistCustomerCron}
                            cron customer già creato
                        {else}
                            crea cron customer
                        {/if}
                    </button>
                </p>
            {else}
                <p>
                    <strong>{l s='Per poter procedere assicurati di aver installato e attivato il modulo cronjob!' mod='at_com_module'}</strong>
                </p>
            {/if}

        </div>

    </div>
</form>