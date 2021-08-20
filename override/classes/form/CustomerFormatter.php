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

    private $required_map = array(
        "firstname" => 1,
        "lastname" => 1,
        "company" => 1,
        "vat" => 0,
        "siret" => 0,
        "sdi" => 0,
        "email" => 1,
        "pec" => 0,
        "website" => 0,
        "attachment" => 0,
        "address1" => 0,
        "address2" => 0,
        "id_country_postcode" => 0,
        "id_state" => 0,
        "id_country" => 0,
        "city" => 0,
        "dni" => 0,
        "phone" => 0,
        "op_address1" => 0,
        "op_address2" => 0,
        "op_id_country_postcode" => 0,
        "op_id_state" => 0,
        "op_id_country" => 0,
        "op_city" => 0,
        "op_phone" => 0,
        "tr_name" => 0,
        "tr_email" => 0,
        "tr_phone" => 0,
        "tr_cell" => 0,
        "tr_group" => 0,
        "bank_name" => 0,
        "bank_address" => 0,
        "iban" => 0,
        "swift" => 0,
        "brands" => 0,
        "tc_b2b" => 0,
        "tc_b2c" => 0,
        "tc_amazon" => 0,
        "tc_ebay" => 0,
        "tc_other" => 0,
        "notes" => 0
    );

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
                    'Customer Application',
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
            ->setRequired($this->required_map['firstname'])
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
            ->setRequired($this->required_map['lastname'])
            ->addAvailableValue(
                'comment',
                $this->translator->trans('Only letters and the dot (.) character, followed by a space, are allowed.', [], 'Shop.Forms.Help')
            );

        $format['company'] = (new FormField())
            ->setName('company')
            ->setType('text')
            ->setRequired($this->required_map['company'])
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
            ->setRequired($this->required_map['siret'])
            ->setLabel($this->translator->trans(
                'Identification number',
                [],
                'Shop.Forms.Labels'
            ));

        $format['sdi'] = (new FormField())
            ->setName('sdi')
            ->setType('text')
            ->setRequired($this->required_map['sdi'])
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
                    'Email',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired($this->required_map['email']);

        $format['pec'] = (new FormField())
            ->setName('pec')
            ->setType('email')
            ->setLabel(
                $this->translator->trans(
                    'PEC',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired($this->required_map['pec']);

        $format['website'] = (new FormField())
            ->setName('website')
            ->setType('text')
            ->setRequired($this->required_map['website'])
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

            $format['atcom_webservice_key'] = (new FormField())
                ->setName('atcom_webservice_key')
                ->setValue(Configuration::get('ATCOM_STATES_KEY'))
                ->setType('hidden');

            $format['title_attachment'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Chamber of Commerce view',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

            $format['attachment'] = (new FormField())
                ->setName('attachment')
                ->setType('file')
                ->setValue('NA')
                ->setLabel(
                    $this->translator->trans(
                        'Chamber of Commerce view',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['attachment']);

            $format['title_legal'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Headquarter',
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
                ->setRequired($this->required_map['address1']);

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
                ->setRequired($this->required_map['address2']);

            $format['postcode'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Zip/Postal Code',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('id_country_postcode')
                ->setType('text')
                ->setRequired($this->required_map['id_country_postcode']);

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
                ->setRequired($this->required_map['city']);

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
                ->setType('state')
                ->setRequired($this->required_map['id_state']);

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
                ->setType('country')
                ->setRequired($this->required_map['id_country']);

            $format['dni'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Identification number',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('dni')
                ->setType('text')
                ->setRequired($this->required_map['dni']);

            $format['phone'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Phone',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('phone')
                ->setType('text')
                ->setRequired($this->required_map['phone']);

            $format['title_operative'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Operating Headquarters',
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
                ->setRequired($this->required_map['op_address1']);

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
                ->setRequired($this->required_map['op_address2']);

            $format['op_postcode'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Zip/Postal Code',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('op_id_country_postcode')
                ->setType('text')
                ->setRequired($this->required_map['op_id_country_postcode']);

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
                ->setRequired(true)
                ->setRequired($this->required_map['op_city']);

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
                ->setType('state')
                ->setRequired($this->required_map['op_id_state']);

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
                ->setType('country')
                ->setRequired($this->required_map['op_id_country']);

            $format['op_phone'] = (new FormField())
                ->setLabel(
                    $this->translator->trans(
                        'Phone',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setName('op_phone')
                ->setType('text')
                ->setRequired($this->required_map['op_phone']);

            $format['title_1'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Purchasing Manager',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

            $format['tr_name'] = (new FormField())
                ->setName('tr_name')
                ->setLabel(
                    $this->translator->trans(
                        'First and Last Name',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['tr_name']);

            $format['tr_email'] = (new FormField())
                ->setName('tr_email')
                ->setLabel(
                    $this->translator->trans(
                        'Email',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['tr_email']);

            $format['tr_phone'] = (new FormField())
                ->setName('tr_phone')
                ->setLabel(
                    $this->translator->trans(
                        'Phone',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['tr_phone']);

            $format['tr_cell'] = (new FormField())
                ->setName('tr_cell')
                ->setLabel(
                    $this->translator->trans(
                        'Mobile Phone',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['tr_cell']);

            $format['tr_group'] = (new FormField())
                ->setName('tr_group')
                ->setLabel(
                    $this->translator->trans(
                        'Buyer group',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['tr_group']);

            $format['title_2'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Bank Information',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

            $format['bank_name'] = (new FormField())
                ->setName('bank_name')
                ->setLabel(
                    $this->translator->trans(
                        'Bank Name',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['bank_name']);

            $format['bank_address'] = (new FormField())
                ->setName('bank_address')
                ->setLabel(
                    $this->translator->trans(
                        'Bank Agency Address',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['bank_address']);

            $format['iban'] = (new FormField())
                ->setName('iban')
                ->setLabel(
                    $this->translator->trans(
                        'IBAN',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['iban']);

            $format['swift'] = (new FormField())
                ->setName('swift')
                ->setLabel(
                    $this->translator->trans(
                        'SWIFT',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['swift']);

            $format['title_3'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Brands',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setType('title');

            $format['brands'] = (new FormField())
                ->setName('brands')
                ->setLabel(
                    $this->translator->trans(
                        'Brands',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->addAvailableValue(
                    'comment',
                    $this->translator->trans('Inserire i brand separati da virgola.', [], 'Shop.Forms.Help')
                )
                ->setRequired($this->required_map['brands']);

            $format['title_4'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Sales channels',
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
                        'Wholesaler/B2B',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['tc_b2b']);

            $format['tc_b2c'] = (new FormField())
                ->setName('tc_b2c')
                ->setType('checkbox')
                ->setLabel(
                    $this->translator->trans(
                        'Physical store/B2C/off line store',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['tc_b2c']);

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
                ->setRequired($this->required_map['tc_amazon']);

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
                ->setRequired($this->required_map['tc_ebay']);

            $format['tc_other'] = (new FormField())
                ->setName('tc_other')
                ->setType('text')
                ->setLabel(
                    $this->translator->trans(
                        'Other marketplaces',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
                ->setRequired($this->required_map['tc_other']);
        }

        $format['title_5'] = (new FormField())
            ->setName(
                $this->translator->trans(
                    'Additional notes',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setType('title');

        $format['notes'] = (new FormField())
            ->setName('notes')
            ->setType('text')
            ->setMaxLength(256)
            ->setLabel(
                $this->translator->trans(
                    'Additional notes',
                    [],
                    'Shop.Forms.Labels'
                )
            )
            ->setRequired($this->required_map['notes']);

        if ($this->ask_for_password) {
            $format['title_6'] = (new FormField())
                ->setName(
                    $this->translator->trans(
                        'Credentials',
                        [],
                        'Shop.Forms.Labels'
                    )
                )
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
