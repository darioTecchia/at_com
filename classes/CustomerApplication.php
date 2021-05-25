<?php

/**
 * Class CustomerApplicationCore
 */
class CustomerApplicationCore extends ObjectModel
{
  /** @var int Customer Application ID */
  public $id;

  /** @var int Customer ID which application belongs to */
  public $id_customer = null;

  /** @var string Address first line */
  public $brands;

  /** @var bool True if customer is B2B */
  public $b2b;

  /** @var bool True if customer is B2C */
  public $b2c;

  /** @var string website */
  public $website;

  /** @var string Amazon seller name */
  public $amazon;

  /** @var string Ebay seller name */
  public $ebay;

  /** @var string Other marketplace names */
  public $other;

  /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
      'table' => 'customer_application',
      'primary' => 'id_customer_application',
      'fields' => [
        'id_customer' => ['type' => self::TYPE_INT, 'validate' => 'isNullOrUnsignedId', 'copy_post' => false],
        'brands' => ['type' => self::TYPE_STRING, 'required' => true, 'size' => 255],
        'b2b' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false],
        'b2c' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'copy_post' => false],
        'website' => ['type' => self::TYPE_STRING, 'validate' => 'isUrl'],
        'amazon' => ['type' => self::TYPE_STRING, 'size' => 128],
        'ebay' => ['type' => self::TYPE_STRING, 'size' => 128],
        'other' => ['type' => self::TYPE_STRING, 'size' => 128]
      ],
  ];


}