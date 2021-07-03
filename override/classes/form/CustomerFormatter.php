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
            ->setType('title');

        $format['firstname'] = (new FormField())
            ->setName('firstname')
            ->setLabel(
                $this->translator->trans(
                    'First name',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true)
            ->addAvailableValue(
                'comment',
                $this->translator->trans('Only letters and the dot (.) character, followed by a space, are allowed.', [], 'Shop.Forms.Help')
            );

        $format['lastname'] = (new FormField())
            ->setName('lastname')
            ->setLabel(
                $this->translator->trans(
                    'Last name',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true)
            ->addAvailableValue(
                'comment',
                $this->translator->trans('Only letters and the dot (.) character, followed by a space, are allowed.', [], 'Shop.Forms.Help')
            );

        $format['company'] = (new FormField())
            ->setName('company')
            ->setType('text')
            ->setRequired(true)
            ->setLabel($this->translator->trans(
                'Company',
                [],
                'Shop.Forms.Labels'
            ));

        $format['vat'] = (new FormField())
            ->setName('vat')
            ->setType('text')
            ->setLabel($this->translator->trans(
                'VAT Number',
                [],
                'Shop.Forms.Labels'
            ));

        $format['siret'] = (new FormField())
            ->setName('siret')
            ->setType('text')
            ->setLabel($this->translator->trans(
                'Identification number',
                [],
                'Shop.Forms.Labels'
            ));

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

        $format['email'] = (new FormField())
            ->setName('email')
            ->setType('email')
            ->setLabel(
                $this->translator->trans(
                    'PEC/Email',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired(true);

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

        if (Context::getContext()->controller->php_self == "authentication") {
            $countries = Country::getCountries((int) Context::getContext()->language->id, true);
            $mapped_countries = array();
            foreach ($countries as $id => $country) {
                $mapped_countries[$id] = $country['name'];
            }

            $format['title_legal'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Sede Legale',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

            $format['address1'] = (new FormField())
                ->setName('address1')
                ->setType('text')
                ->setLabel(
                    $this->translator->trans(
                        'Address',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired(true);

            $format['address2'] = (new FormField())
                ->setName('address2')
                ->setType('text')
                ->setLabel(
                    $this->translator->trans(
                        'Address Complement',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired(true);

            $format['postcode'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Zip/Postal Code',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('id_country_postcode')
                ->setType('text');

            $format['city'] = (new FormField())
                ->setName('city')
                ->setType('text')
                ->setLabel(
                    $this->translator->trans(
                        'City',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired(true);

            $format['legal_state'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'State',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('id_state')
                ->setAvailableValues(array())
                ->setType('state');

            $format['legal_country'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Country',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('id_country')
                ->setAvailableValues($countries)
                ->setType('country');

            $format['dni'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Identification number',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('dni')
                ->setType('text');

            $format['phone'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Phone',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('phone')
                ->setType('text');

            $format['title_operative'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Sede Operativa',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

            $format['op_address1'] = (new FormField())
                ->setName('op_address1')
                ->setType('text')
                ->setLabel(
                    $this->translator->trans(
                        'Address',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired(true);

            $format['op_address2'] = (new FormField())
                ->setName('op_address2')
                ->setType('text')
                ->setLabel(
                    $this->translator->trans(
                        'Address Complement',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired(true);

            $format['op_postcode'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Zip/Postal Code',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('op_id_country_postcode')
                ->setType('text');

            $format['op_city'] = (new FormField())
                ->setName('op_city')
                ->setType('text')
                ->setLabel(
                    $this->translator->trans(
                        'City',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired(true);

            $format['op_state'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'State',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('op_id_state')
                ->setAvailableValues(array())
                ->setType('state');

            $format['op_country'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Country',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('op_id_country')
                ->setAvailableValues($countries)
                ->setType('country');

            $format['op_phone'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Phone',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('op_phone')
                ->setType('text');

            $format['title_1'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Responsabile Acquisti',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

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

            $format['tr_group'] = (new FormField())
                ->setName('tr_group')
                ->setLabel(
                    $this->translator->trans(
                        'Aderenti a catene',
                        [],
                        'Shop.Forms.Labels'
                    )
                );

            $format['title_2'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Informazioni Bancarie',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

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

            $format['title_3'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Marchi Principali',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

            $format['brands'] = (new FormField())
                ->setName('brands')
                ->setLabel(
                    $this->translator->trans(
                        'Marchi Principali',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
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
                ->setType('title');

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

            $format['tc_ebay'] = (new FormField())
                ->setName('tc_ebay')
                ->setType('text')
                ->setLabel(
                    $this->translator->trans(
                        'Ebay seller',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->addAvailableValue(
                    'comment',
                    $this->translator->trans('Inserire il nome o il link del negozio ebay.', [], 'Shop.Forms.Help')
                )
                ->setRequired(false);

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
        }

        if ($this->ask_for_password) {
            $format['title_5'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Credenziali',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired(false)
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
