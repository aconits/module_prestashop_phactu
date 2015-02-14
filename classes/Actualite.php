<?php

class Actualite extends ObjectModel
{

	public $id_actualite;
	public $title;
	public $short_description;
	public $description;
	public $date_creation;
	public $active;
	public $show_home;
	public $show_column;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'actualite',
		'primary' => 'id_actualite',
		'multilang' => true,
		'fields' => array(
			'date_creation' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'show_home' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			'show_column' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
			/* lang */
			'title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
			'short_description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
			'description' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml'),
		)
	);

	public function __construct($id_actualite = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_actualite, $id_lang, $id_shop);
	}

	public function add($autodate = true, $null_values = false)
	{
		return parent::add($autodate, $null_values);
	}

	public function delete()
	{
		return parent::delete();
	}

	public static function getAll($id_lang)
	{
		return Db::getInstance()->executeS('SELECT a.*, al.* FROM '._DB_PREFIX_.'actualite a LEFT JOIN '._DB_PREFIX_.'actualite_lang al ON (a.id_actualite = al.id_actualite AND al.id_lang = '.(int)$id_lang.')');
	}

	public static function getActualites($id_lang, $nb = 3)
	{
		return Db::getInstance()->executeS('SELECT a.*, al.* FROM '._DB_PREFIX_.'actualite a INNER JOIN '._DB_PREFIX_.'actualite_lang al ON (a.id_actualite = al.id_actualite AND al.id_lang = '.(int)$id_lang.') WHERE a.active = 1 ORDER BY a.date_creation DESC LIMIT '.(int)$nb);
	}

	public static function getHomeActualites($id_lang, $nb = 3)
	{
		return Db::getInstance()->executeS('SELECT a.*, al.* FROM '._DB_PREFIX_.'actualite a INNER JOIN '._DB_PREFIX_.'actualite_lang al ON (a.id_actualite = al.id_actualite AND al.id_lang = '.(int)$id_lang.') WHERE a.active = 1 AND a.show_home = 1 ORDER BY a.date_creation DESC LIMIT '.(int)$nb);
	}

	public static function getColumnActualites($id_lang, $nb = 3)
	{
		return Db::getInstance()->executeS('SELECT a.*, al.* FROM '._DB_PREFIX_.'actualite a INNER JOIN '._DB_PREFIX_.'actualite_lang al ON (a.id_actualite = al.id_actualite AND al.id_lang = '.(int)$id_lang.') WHERE a.active = 1 AND a.show_column = 1 ORDER BY a.date_creation DESC LIMIT '.(int)$nb);
	}

	public static function getAllActive($id_lang, $limit = null, $nb = 3)
	{
		return Db::getInstance()->executeS('SELECT a.*, al.* FROM '._DB_PREFIX_.'actualite a INNER JOIN '._DB_PREFIX_.'actualite_lang al ON (a.id_actualite = al.id_actualite AND al.id_lang = '.(int)$id_lang.') WHERE a.active = 1 ORDER BY a.date_creation DESC '.($limit !== null ? ('LIMIT '.(int)$limit.', '.(int)$nb) : '' ));
	}

}