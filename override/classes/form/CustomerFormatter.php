<?php
use Symfony\Component\Translation\TranslatorInterface;

class CustomerFormatter extends CustomerFormatterCore
{
    private $translator;
    private $language;

    private $ask_for_birthdate = true;
    private $ask_for_partner_optin = true;
    private $partner_optin_is_required = true;
    private $ask_for_password = true;
    private $password_is_required = true;
    private $ask_for_new_password = false;

    public function __construct(
        TranslatorInterface $translator,
        Language $language
    ) {
        $this->translator = $translator;
        $this->language = $language;
    }

    public function setAskForBirthdate($ask_for_birthdate)
    {
        $this->ask_for_birthdate = $ask_for_birthdate;

        return $this;
    }

    public function setAskForPartnerOptin($ask_for_partner_optin)
    {
        $this->ask_for_partner_optin = $ask_for_partner_optin;

        return $this;
    }

    public function setPartnerOptinRequired($partner_optin_is_required)
    {
        $this->partner_optin_is_required = $partner_optin_is_required;

        return $this;
    }

    public function setAskForPassword($ask_for_password)
    {
        $this->ask_for_password = $ask_for_password;

        return $this;
    }

    public function setAskForNewPassword($ask_for_new_password)
    {
        $this->ask_for_new_password = $ask_for_new_password;

        return $this;
    }

    public function setPasswordRequired($password_is_required)
    {
        $this->password_is_required = $password_is_required;

        return $this;
    }

    public function getFormat()
    {
        $format = [];

        $format['client_title'] = (new FormField())
            ->setName(
                $this->translator->trans(
                    'Scheda Cliente',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true)
            ->setType('title');

        /** Company Name */
        $format['company'] = (new FormField())
            ->setName('company')
            ->setType('text')
            ->setRequired(true)
            ->setLabel($this->translator->trans(
                'Company',
                [],
                'Shop.Forms.Labels'
            ));

        /** VAT Number */
        $format['vat'] = (new FormField())
            ->setName('vat')
            ->setType('text')
            ->setRequired(true)
            ->setLabel($this->translator->trans(
                'VAT Number',
                [],
                'Shop.Forms.Labels'
            ));

        /** Registration Number */
        $format['siret'] = (new FormField())
            ->setName('siret')
            ->setType('text')
            ->setRequired(true)
            ->setLabel($this->translator->trans(
                'Identification number',
                [],
                'Shop.Forms.Labels'
            ));

        /** SDI */
        $format['sdi'] = (new FormField())
            ->setName('sdi')
            ->setType('text')
            ->setLabel($this->translator->trans(
                'SDI',
                [],
                'Shop.Forms.Labels'
            ))
            ->addAvailableValue(
                'comment',
                $this->translator->trans('Only for Italian Client.', [], 'Shop.Forms.Help')
            );

        /** PEC */
        $format['email'] = (new FormField())
            ->setName('email')
            ->setType('email')
            ->setLabel(
                $this->translator->trans(
                    'PEC',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /** Website */
        $format['website'] = (new FormField())
            ->setName('website')
            ->setType('text')
            ->setRequired(false)
            ->setLabel(
                $this->translator->trans(
                    'Website',
                    [],
                    'Shop.Forms.Labels'
                )
            );

        /**
         * CUSTOMER APPLICATION SPECIFIC FIELDS
         */
        $format['title_1'] = (new FormField())
            ->setName(
                $this->translator->trans(
                    'Responsabile Acquisti',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true)
            ->setType('title');

        /** trade reference name */
        $format['tr_name'] = (new FormField())
            ->setName('tr_name')
            ->setLabel(
                $this->translator->trans(
                    'Nome e Cognome',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /** trade reference email */
        $format['tr_email'] = (new FormField())
            ->setName('tr_email')
            ->setLabel(
                $this->translator->trans(
                    'Email',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /** trade reference phone */
        $format['tr_phone'] = (new FormField())
            ->setName('tr_phone')
            ->setLabel(
                $this->translator->trans(
                    'Telefono',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /** trade reference cellular */
        $format['tr_cell'] = (new FormField())
            ->setName('tr_cell')
            ->setLabel(
                $this->translator->trans(
                    'Cellulare',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /** trade reference buyer group */
        $format['tr_group'] = (new FormField())
            ->setName('tr_group')
            ->setLabel(
                $this->translator->trans(
                    'Aderenti a catene',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /**
         * BANK INFORMATIONS SPECIFIC FIELDS
         */
        $format['title_2'] = (new FormField())
            ->setName(
                $this->translator->trans(
                    'Informazioni Bancarie',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true)
            ->setType('title');

        /** bank name */
        $format['bank_name'] = (new FormField())
            ->setName('bank_name')
            ->setLabel(
                $this->translator->trans(
                    'Nome Banca',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /** bank address */
        $format['bank_address'] = (new FormField())
            ->setName('bank_address')
            ->setLabel(
                $this->translator->trans(
                    'Indirizzo Agenzia Banca',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /** bank IBAN */
        $format['iban'] = (new FormField())
            ->setName('iban')
            ->setLabel(
                $this->translator->trans(
                    'IBAN',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /** bank swift */
        $format['swift'] = (new FormField())
            ->setName('swift')
            ->setLabel(
                $this->translator->trans(
                    'SWIFT',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

        /**
         * BRANDS AND TRADE CHANNELS
         */
        $format['title_3'] = (new FormField())
            ->setName(
                $this->translator->trans(
                    'Marchi Principali',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true)
            ->setType('title');

        /** brand names */
        $format['brands'] = (new FormField())
            ->setName('brands')
            ->setLabel(
                $this->translator->trans(
                    'Marchi Principali',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true)
            ->addAvailableValue(
                'comment',
                $this->translator->trans('Inserire i brand separati da virgola.', [], 'Shop.Forms.Help')
            );

        $format['title_4'] = (new FormField())
            ->setName(
                $this->translator->trans(
                    'Canali di vendita',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true)
            ->setType('title');

        /** b2b checkbox */
        $format['tc_b2b'] = (new FormField())
            ->setName('tc_b2b')
            ->setType('checkbox')
            ->setLabel(
                $this->translator->trans(
                    'Grossista/B2B',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(false);

        /** b2c checkbox */
        $format['tc_b2c'] = (new FormField())
            ->setName('tc_b2c')
            ->setType('checkbox')
            ->setLabel(
                $this->translator->trans(
                    'Negozio Fisico/B2C/off line store',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(false);

        /** amazon shop name/url */
        $format['tc_amazon'] = (new FormField())
            ->setName('tc_amazon')
            ->setType('text')
            ->setLabel(
                $this->translator->trans(
                    'Amazon seller',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->addAvailableValue(
                'comment',
                $this->translator->trans('Inserire il nome o il link del negozio amazon.', [], 'Shop.Forms.Help')
            )
            ->setRequired(false);

        /** ebay shop name/url */
        $format['tc_ebay'] = (new FormField())
            ->setName('tc_ebay')
            ->setType('text')
            ->setLabel(
                $this->translator->trans(
                    'Amazon seller',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->addAvailableValue(
                'comment',
                $this->translator->trans('Inserire il nome o il link del negozio ebay.', [], 'Shop.Forms.Help')
            )
            ->setRequired(false);

        /** other field */
        $format['tc_other'] = (new FormField())
            ->setName('tc_other')
            ->setType('text')
            ->setLabel(
                $this->translator->trans(
                    'Altro',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(false);

        if ($this->ask_for_password) {
            $format['title_5'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Credenziali',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired(true)
                ->setType('title');

            $format['password'] = (new FormField())
                ->setName('password')
                ->setType('password')
                ->setLabel(
                    $this->translator->trans(
                        'Password',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->password_is_required);
        }

        if ($this->ask_for_new_password) {
            $format['new_password'] = (new FormField())
                ->setName('new_password')
                ->setType('password')
                ->setLabel(
                    $this->translator->trans(
                        'New password',
                        [],
                        'Shop.Forms.Labels'
                    )
                );
        }

        if ($this->ask_for_partner_optin) {
            $format['optin'] = (new FormField())
                ->setName('optin')
                ->setType('checkbox')
                ->setLabel(
                    $this->translator->trans(
                        'Receive offers from our partners',
                        [],
                        'Shop.Theme.Customeraccount'
                    )
                )
                ->setRequired($this->partner_optin_is_required);
        }

        // ToDo, replace the hook exec with HookFinder when the associated PR will be merged
        $additionalCustomerFormFields = Hook::exec('additionalCustomerFormFields', ['fields' => &$format], null, true);

        if (is_array($additionalCustomerFormFields)) {
            foreach ($additionalCustomerFormFields as $moduleName => $additionnalFormFields) {
                if (!is_array($additionnalFormFields)) {
                    continue;
                }

                foreach ($additionnalFormFields as $formField) {
                    $formField->moduleName = $moduleName;
                    $format[$moduleName . '_' . $formField->getName()] = $formField;
                }
            }
        }

        // TODO: TVA etc.?

        return $this->addConstraints($format);
    }

    private function addConstraints(array $format)
    {
        $constraints = Customer::$definition['fields'];

        foreach ($format as $field) {
            if (!empty($constraints[$field->getName()]['validate'])) {
                $field->addConstraint(
                    $constraints[$field->getName()]['validate']
                );
            }
        }

        return $format;
    }
}
