<?php

namespace At_com;

/**
 * Class ModuleCronsManager
 */
class ModuleCronsManager
{
    private $context;

    private $module_path;

    public function __construct($module_path)
    {
        $this->context = \Context::getContext();
        $this->module_path = $module_path;
    }

    /**
     * @return string
     */
    public function getCronUrl($cron_task)
    {
        $protocol = \Tools::getShopProtocol();
        $shopBaseLink = $this->context->link->getBaseLink();
        $cronFileLink = sprintf(
            '%s?secure_key=%s',
            $cron_task,
            md5(_COOKIE_KEY_ . \Configuration::get('PS_SHOP_NAME'))
        );

        return $shopBaseLink . $this->module_path . 'cron/' . $cronFileLink;
    }

    /**
     * @param string $cron_task
     *
     * @return bool
     *
     * @throws Exception
     */
    public function createCronJob($cron_task)
    {
        /** @var CronJobs $cronJobsModule */
        $cronJobsModule = \Module::getInstanceByName('cronjobs');

        $isCronAdded = $cronJobsModule->addOneShotTask(
            $this->getCronUrl($cron_task),
            'Cron per la disattivazione automatica dei customer temporanei.'
        );

        return $isCronAdded;
    }

    public function exsistCron($cron_task)
    {
        $query = new \DbQuery();
        $query
            ->select('*')
            ->from('cronjobs')
            ->where('`task`=\'' . urlencode($this->getCronUrl($cron_task)) . '\'')
        ;

        /** @var array $row */
        $row = \Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);

        return !!$row;
    }

}
