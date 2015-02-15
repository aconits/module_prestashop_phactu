<?php

if (!defined('_PS_VERSION_'))
	exit;

include_once(dirname(__FILE__).'/classes/Actualite.php');

class PhActu extends Module
{

	private $_html;
	private $_display;
	private $_toolbar_btn;

	public function __construct()
	{
		$this->name = 'phactu';
		$this->tab = 'front_office_features';
		$this->version = '1.0';
		$this->author = 'Favre Pierre-Henry';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Actualité');
		$this->description = $this->l('Ajouter un bloc actualité');
		$this->secure_key = Tools::encrypt($this->name);

		/* Champs d'affichage pour la liste */
		$this->fields_list = array(
			'id_actualite' => array('title' => $this->l('ID'), 'align' => 'center', 'size' => 50, 'class' => 'fixed-width-xs'),
			'title' => array('title' => $this->l('Titre')),
			'date_creation' => array('title' => $this->l('Date de création'), 'align' => 'center'),
			'active' => array('title' => $this->l('Activé'), 'align' => 'center', 'active' => 'status', 'type' => 'bool', 'class' => 'fixed-width-sm'),
			'show_home' => array('title' => $this->l('Bloc page d\'accueil'), 'align' => 'center', 'active' => 'statushome', 'type' => 'bool', 'class' => 'fixed-width-sm'),
			'show_column' => array('title' => $this->l('Blocs gauche et droite'), 'align' => 'center', 'active' => 'statuscolumn', 'type' => 'bool', 'class' => 'fixed-width-sm'),
		);

		$this->fields_options = array(
			'general' => array(
				'title' => $this->l('Options'),
				'image' => _PS_IMG_.'t/AdminPreferences.gif',
				'fields' => array(
					'COLOR_PHACTU' => array(
						'title' => $this->l('Titre bloc : '),
						'desc' => $this->l('Définir la couleur du titre sur les blocs (vide pour laisser le style par défaut).'),
						'type' => 'color',
						'size' => 6,
						'name' => 'COLOR_PHACTU'
					),
					'COLOR_HV_PHACTU' => array(
						'title' => $this->l('Titre bloc : '),
						'desc' => $this->l('Définir la couleur du titre sur les blocs lors du survol avec la souris (vide pour laisser le style par défaut).'),
						'type' => 'color',
						'size' => 6,
						'name' => 'COLOR_HV_PHACTU'
					),
					'BG_COLOR_PHACTU' => array(
						'title' => $this->l('Titre bloc : '),
						'desc' => $this->l('Définir la couleur de fond du titre sur les blocs (vide pour laisser le style par défaut).'),
						'type' => 'color',
						'size' => 6,
						'name' => 'BG_COLOR_PHACTU'
					),
					'BG_COLOR_HV_PHACTU' => array(
						'title' => $this->l('Titre bloc : '),
						'desc' => $this->l('Définir la couleur de fond du titre sur les blocs lors du survol avec la souris (vide pour laisser le style par défaut).'),
						'type' => 'color',
						'size' => 6,
						'name' => 'BG_COLOR_HV_PHACTU'
					),
					'BR_COLOR_PHACTU' => array(
						'title' => $this->l('Titre bloc : '),
						'desc' => $this->l('Définir la couleur des bordures du titre sur les blocs (vide pour laisser le style par défaut).'),
						'type' => 'color',
						'size' => 6,
						'name' => 'BR_COLOR_PHACTU'
					),
					'BR_COLOR_HV_PHACTU' => array(
						'title' => $this->l('Titre bloc : '),
						'desc' => $this->l('Définir la couleur des bordures du titre sur les blocs lors du survol avec la souris (vide pour laisser le style par défaut).'),
						'type' => 'color',
						'size' => 6,
						'name' => 'BR_COLOR_HV_PHACTU'
					),
					'NB_HOME_PHACTU' => array(
						'title' => $this->l('Bloc central : '),
						'desc' => $this->l('Définir le nombre d\'actualités en page d\'accueil.'),
						'type' => 'text',
						'cast' => 'intval',
					),
					'NB_CARAC_PHACTU' => array(
						'title' => $this->l('Bloc central : '),
						'desc' => $this->l('Définir le nombre de caractères pour la prévisualisation (0 pour désactiver).'),
						'type' => 'text',
						'cast' => 'intval',
					),
					'NB_COLUMN_PHACTU' => array(
						'title' => $this->l('Bloc gauche et droit : '),
						'desc' => $this->l('Définir le nombre d\'actualites sur les colonnes gauche et droite.'),
						'type' => 'text',
						'cast' => 'intval',
					),
					'NB_LEFT_CARAC_PHACTU' => array(
						'title' => $this->l('Bloc gauche et droit : '),
						'desc' => $this->l('Définir le nombre de caractères pour l\'affichage des colonnes (0 pour désactiver).'),
						'type' => 'text',
						'cast' => 'intval',
					),
					'SPEED_PHACTU' => array(
						'title' => $this->l('Bloc gauche et droit : '),
						'desc' => $this->l('Définir le temps de transition (en milliseconds).'),
						'type' => 'text',
						'cast' => 'intval',
					),
					'PAUSE_PHACTU' => array(
						'title' => $this->l('Bloc gauche et droit : '),
						'desc' => $this->l('Définir le temps avant de passer à l\'actualité suivante (en milliseconds).'),
						'type' => 'text',
						'cast' => 'intval',
					),
					'NB_PER_PAGE_PHACTU' => array(
						'title' => $this->l('Page liste : '),
						'desc' => $this->l('Définir le nombre d\'actualités par page sur le listing.'),
						'type' => 'text',
						'cast' => 'intval',
					)
				),
				'submit' => array(
					'name' => 'submitOptions',
					'title' => $this->l('Save')
				)
			),a
		);
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayHome') || !$this->registerHook('displayLeftColumn') || !$this->installSQL() || !Configuration::updateValue('NB_HOME_PHACTU', 3) || !Configuration::updateValue('NB_PER_PAGE_PHACTU', 5) || !$this->_installTestCases() || !$this->registerHook('ModuleRoutes') || !Configuration::updateValue('NB_CARAC_PHACTU', 250) || !Configuration::updateValue('NB_LEFT_CARAC_PHACTU', 150) || !Configuration::updateValue('NB_COLUMN_PHACTU', 3) || !Configuration::updateValue('SPEED_PHACTU', 800) || !Configuration::updateValue('PAUSE_PHACTU', 5000)
				|| !Configuration::updateValue('COLOR_PHACTU', '#555454') || !Configuration::updateValue('COLOR_HV_PHACTU', '#333333') || !Configuration::updateValue('BG_COLOR_PHACTU', '#f6f6f6') || !Configuration::updateValue('BG_COLOR_HV_PHACTU', '#f6f6f6') || !Configuration::updateValue('BR_COLOR_PHACTU', '') || !Configuration::updateValue('BR_COLOR_HV_PHACTU'))
			return false;

		return true;
	}

	private function _installTestCases()
	{
		$languages = Language::getLanguages();
		$show_home = array(0, 1, 4, 6, 7, 8);
		$show_column = array(2, 3, 4, 5, 6);
		for ($i = 1; $i < 10; $i++)
		{
			Db::getInstance()->insert('actualite', array(
				'id_actualite' => $i,
				'date_creation' => date('Y-m-d H:i:s', strtotime('-1 days +'.$i.' hours')),
				'active' => $i !== 6 ? 1 : 0,
				'show_home' => in_array($i, $show_home) ? 1 : 0,
				'show_column' => in_array($i, $show_column) ? 1 : 0,
			));

			foreach ($languages as $la)
			{
				Db::getInstance()->insert('actualite_lang', array(
					'id_actualite' => $i,
					'id_lang' => $la['id_lang'],
					'title' => 'Lorem ipsum '.$i.' ['.$la['name'].']',
					'short_description' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus consectetur, nisl id rutrum auctor, ante magna sagittis neque, non hendrerit neque quam vel lorem. Vivamus nec mattis diam. Quisque placerat dolor id magna consectetur egestas. Quisque consequat magna ligula, ac vestibulum nunc lacinia nec. Maecenas ullamcorper, ante non luctus tristique, lacus ligula rhoncus augue, eu lobortis lacus tellus id ipsum. Suspendisse varius ante tortor, at aliquet erat mattis at. Integer sed ullamcorper felis. Donec ultrices orci quis consequat feugiat. In consectetur sem tempus eleifend tempus. Nam imperdiet facilisis enim, et consequat tellus tempus at. Quisque sit amet eleifend sem. Vestibulum neque mi, rhoncus nec lacus tempus, pellentesque pharetra nisl. Donec sed scelerisque metus. Aenean eget dolor a massa lobortis pretium eget quis enim. Integer ac libero ut lorem ornare ornare vel vitae nulla.</p>',
					'description' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus consectetur, nisl id rutrum auctor, ante magna sagittis neque, non hendrerit neque quam vel lorem. Vivamus nec mattis diam. Quisque placerat dolor id magna consectetur egestas. Quisque consequat magna ligula, ac vestibulum nunc lacinia nec. Maecenas ullamcorper, ante non luctus tristique, lacus ligula rhoncus augue, eu lobortis lacus tellus id ipsum. Suspendisse varius ante tortor, at aliquet erat mattis at. Integer sed ullamcorper felis. Donec ultrices orci quis consequat feugiat. In consectetur sem tempus eleifend tempus. Nam imperdiet facilisis enim, et consequat tellus tempus at. Quisque sit amet eleifend sem. Vestibulum neque mi, rhoncus nec lacus tempus, pellentesque pharetra nisl. Donec sed scelerisque metus. Aenean eget dolor a massa lobortis pretium eget quis enim. Integer ac libero ut lorem ornare ornare vel vitae nulla.</p><p>Sed sollicitudin orci eget turpis aliquet pellentesque a adipiscing nibh. Suspendisse ut tincidunt nisi. Duis consequat, libero ut egestas facilisis, diam arcu dignissim quam, sit amet elementum augue dolor ultrices lorem. Integer id dignissim dui, sed mattis leo. Mauris at pretium nulla, ut porta enim. Sed semper lorem ut sagittis sagittis. Integer mollis, orci id tincidunt malesuada, eros leo mollis eros, eget vestibulum sapien felis eu urna. Phasellus eu purus vehicula sem eleifend pulvinar. Nunc a semper eros, at vehicula libero. Maecenas euismod accumsan sodales. Proin hendrerit felis non risus malesuada faucibus non quis odio. Cras hendrerit tincidunt pellentesque.</p><p>In hac habitasse platea dictumst. Quisque ullamcorper pretium libero ac imperdiet. Quisque rutrum fermentum laoreet. Proin tempus euismod tortor, sed porta nisi sagittis ut. Nunc lacinia, ligula id dignissim ultricies, nibh dui consequat dolor, vel bibendum lorem arcu eu odio. In a facilisis felis, sed porttitor nunc. Etiam vitae ipsum tempor, euismod nibh non, euismod nunc. Fusce congue massa ut enim ultricies aliquet. Curabitur a lectus tempus, euismod nulla sed, dictum tortor. In aliquam orci a lorem dapibus tincidunt. Donec vulputate rutrum orci eget gravida. Sed consectetur rhoncus dui, non viverra nisi volutpat quis. Suspendisse urna urna, tincidunt sed lorem ut, malesuada posuere tortor. Proin congue odio nulla, id suscipit risus convallis vitae. Aliquam lacus nibh, venenatis quis mollis eu, tempus non ligula.</p><p>Sed nec ligula at turpis tempor viverra eu quis diam. Vivamus ut condimentum tortor. Aenean a sapien rutrum, malesuada felis sit amet, dictum sapien. Fusce molestie ligula a eros bibendum, vitae dapibus nunc pellentesque. Praesent et elementum elit. Morbi euismod massa neque, nec adipiscing est dictum in. Donec quis tristique arcu. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Cras vitae eleifend risus. Praesent dictum mauris a risus iaculis, nec laoreet nisi tristique. Integer commodo luctus rutrum. Nulla facilisi. Nam vitae dui sit amet nisl iaculis imperdiet.</p><p>Nam nec varius mi. Nulla sit amet tortor dui. Etiam quis purus nunc. Sed porttitor lacus ut dui sodales elementum. Pellentesque a luctus ipsum. Fusce blandit non leo id venenatis. Etiam varius ultrices ipsum quis varius. Morbi eleifend risus ac metus iaculis, eget euismod felis egestas. Cras in est lacinia, sodales diam id, cursus diam. Donec sit amet erat non erat congue auctor non ut urna. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi nunc turpis, mattis nec turpis id, sagittis placerat ante.</p>'
				));
			}
		}

		return true;
	}

	public function uninstall()
	{
		if (!parent::uninstall() || !$this->uninstallSQL() || !Configuration::deleteByName('NB_HOME_PHACTU') || !Configuration::deleteByName('NB_PER_PAGE_PHACTU') || !Configuration::deleteByName('NB_CARAC_PHACTU') || !Configuration::deleteByName('NB_LEFT_CARAC_PHACTU') || !Configuration::deleteByName('NB_COLUMN_PHACTU') || !Configuration::deleteByName('SPEED_PHACTU') || !Configuration::deleteByName('PAUSE_PHACTU'))
			return false;

		return true;
	}

	public function installSQL()
	{
		return Db::getInstance()->execute('
                        CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'actualite (
                            id_actualite int UNSIGNED AUTO_INCREMENT,
                            date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP(),
                            active BOOLEAN DEFAULT 1,
                            show_home BOOLEAN DEFAULT 1,
                            show_column BOOLEAN DEFAULT 1,

                            PRIMARY KEY (id_actualite)
                        );

                        CREATE TABLE IF NOT EXISTS '._DB_PREFIX_.'actualite_lang (
                            id_actualite int UNSIGNED,
                            id_lang int,
                            title VARCHAR(255),
                            short_description text,
                            description text,

                            PRIMARY KEY (id_actualite, id_lang)
                        );
                ');
	}

	public function uninstallSQL()
	{
		return Db::getInstance()->execute('
			DROP TABLE IF EXISTS '._DB_PREFIX_.'actualite;
			DROP TABLE IF EXISTS '._DB_PREFIX_.'actualite_lang;
		');
	}

	public function initToolbar()
	{
		$current_index = AdminController::$currentIndex;
		$token = Tools::getAdminTokenLite('AdminModules');

		$back = Tools::safeOutput(Tools::getValue('back', ''));

		if (!isset($back) || empty($back))
			$back = urldecode($current_index.'&amp;configure='.$this->name.'&amp;token='.$token);

		switch ($this->_display)
		{
			case 'add':
				$this->_toolbar_btn['cancel'] = array(
					'href' => $back,
					'desc' => $this->l('Cancel')
				);
				break;
			case 'edit':
				$this->_toolbar_btn['cancel'] = array(
					'href' => $back,
					'desc' => $this->l('Cancel')
				);
				break;
			default :
				$this->_toolbar_btn['new'] = array(
					'href' => urldecode($current_index.'&configure='.$this->name.'&token='.$token.'&addActualite=1'),
					'desc' => $this->l('Add new')
				);
				break;
		}

		return $this->_toolbar_btn;
	}

	private function _displayList()
	{
		$this->context->controller->addJqueryPlugin('colorpicker');
		
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

		$helper_options = new HelperOptions();
		$helper_options->module = $this;
		$helper_options->toolbar_btn['save'] = array('href' => AdminController::$currentIndex.'&amp;configure='.$this->name.'&amp;token='.Tools::getAdminTokenLite('AdminModules').'&amp;submitOptions=1&amp;tab_module='.$this->tab.'&amp;module_name='.$this->name, 'desc' => 'Save');

		$helper_options->id = $this->id;
		$helper_options->tpl_vars = array(
			'icon' => 'icon-cogs',
			//'fields_value' => $this->getOptionsValue()
		);

		$helper_options->title = $this->l('Options');
		$helper_options->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper_options->identifier = 'id_configuration';
		$helper_options->token = Tools::getAdminTokenLite('AdminModules');
		$this->_html .= $helper_options->generateOptions($this->fields_options);

		$helper = new HelperList();
		$helper->title = $this->l('Liste de vos actualités');
		$helper->identifier = 'id_actualite';
		$helper->show_toolbar = true;
		$helper->table = $this->table;
		$helper->tpl_vars = array('icon' => 'icon-list');
		$helper->actions = array('edit', 'delete');
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->shopLinkType = '';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');

		$this->_html .= $helper->generateList(Actualite::getAll(Context::getContext()->language->id), $this->fields_list);

		return $this->_html;
	}
	
	/*
	 * A voir si je garde cette fonction
	 */
	public function getOptionsValue()
	{
		return array(
			'NB_HOME_PHACTU' => Configuration::get('NB_HOME_PHACTU'),
			'NB_PER_PAGE_PHACTU' => Configuration::get('NB_PER_PAGE_PHACTU')
		);
	}

	private function _displayForm()
	{
		if (Tools::isSubmit('updateactualite') && Tools::getValue('id_actualite'))
		{
			$this->_display = 'edit';
			$id_actualite = (int)Tools::getValue('id_actualite');
			$actu = new Actualite($id_actualite);
		}
		else
			$this->_display = 'add';

		$this->fields_form = array(
			'form' => array(
				'tinymce' => true,
				'legend' => array(
					'title' => isset($actu) ? $this->l('Modification') : $this->l('Ajout'),
					'image' => isset($actu) ? _PS_ADMIN_IMG_.'edit.gif' : _PS_ADMIN_IMG_.'add.gif'
				),
				'input' => array(
					array(
						'type' => 'hidden',
						'name' => 'id_actualite'
					),
					array(
						'type' => 'text',
						'label' => $this->l('Titre'),
						'name' => 'title',
						'lang' => true,
						'col' => 9,
						'size' => 134
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('Contenu de l\'actualité en page d\'accueil'),
						'name' => 'short_description',
						'autoload_rte' => 'rte', //Enable TinyMCE editor for short description
						'lang' => true,
						'cols' => 60,
						'rows' => 10,
						'col' => 9
					),
					array(
						'type' => 'textarea',
						'label' => $this->l('Contenu de l\'actualité en détail'),
						'name' => 'description',
						'autoload_rte' => 'rte', //Enable TinyMCE editor for description
						'lang' => true,
						'cols' => 60,
						'rows' => 20,
						'col' => 9
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Activé'),
						'name' => 'active',
						'class' => 't',
						'required' => true,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'active_on',
								'value' => 1,
								'label' => $this->l('Yes')),
							array(
								'id' => 'active_off',
								'value' => 0,
								'label' => $this->l('No')),
						)
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Mise en avant sur la page d\'accueil'),
						'name' => 'show_home',
						'class' => 't',
						'required' => true,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'show_home_on',
								'value' => 1,
								'label' => $this->l('Yes')),
							array(
								'id' => 'show_home_off',
								'value' => 0,
								'label' => $this->l('No')),
						)
					),
					array(
						'type' => 'radio',
						'label' => $this->l('Mise en avant sur les blocs gauche et droite'),
						'name' => 'show_column',
						'class' => 't',
						'required' => true,
						'is_bool' => true,
						'values' => array(
							array(
								'id' => 'show_column_on',
								'value' => 1,
								'label' => $this->l('Yes')),
							array(
								'id' => 'show_column_off',
								'value' => 0,
								'label' => $this->l('No')),
						)
					)
				),
				'submit' => array(
					'name' => 'submitActualite',
					'title' => $this->l('Save')
				)
			)
		);

		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$languages = $this->context->controller->getLanguages();

		$helper = new HelperForm();
		$helper->show_toolbar = true;
		$helper->table = $this->table;
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => isset($actu) && Validate::isLoadedObject($actu) ? $this->getFieldsValues($languages, $actu) : $this->getFieldsValues($languages),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		$this->_html .= $helper->generateForm(array($this->fields_form));
	}

	public function getFieldsValues($languages, $actu = false)
	{
		$fields_values = array();
		$fields_values['title'] = array();
		$fields_values['short_description'] = array();

		if ($actu)
		{
			$fields_values['id_actualite'] = $actu->id_actualite;
			foreach ($languages as $language)
			{
				$fields_values['title'][$language['id_lang']] = $actu->title[$language['id_lang']];
				$fields_values['short_description'][$language['id_lang']] = $actu->short_description[$language['id_lang']];
				$fields_values['description'][$language['id_lang']] = $actu->description[$language['id_lang']];
			}

			$fields_values['active'] = $actu->active;
			$fields_values['show_home'] = $actu->show_home;
			$fields_values['show_column'] = $actu->show_column;
		}
		else
		{
			$fields_values['id_actualite'] = 0;
			foreach ($languages as $language)
			{
				$fields_values['title'][$language['id_lang']] = '';
				$fields_values['short_description'][$language['id_lang']] = '';
				$fields_values['description'][$language['id_lang']] = '';
			}

			$fields_values['active'] = 1;
			$fields_values['show_home'] = 1;
			$fields_values['show_column'] = 1;
		}

		return $fields_values;
	}

	public function getContent()
	{
		$this->_clearCache($this->id);
		$this->_html = '';
		$this->table = 'actualite';
		$this->_postProcess();

		return $this->_html;
	}

	private function _postProcess()
	{
		if (Tools::getValue('submitActualite'))
		{
			$this->addActualite(); /* add or edit it's same (save the Actualite object) */
			$this->clearCache();
		}
		elseif (Tools::isSubmit('addActualite') || Tools::isSubmit('updateactualite'))
			return $this->_displayForm();
		elseif (Tools::getIsset('deleteactualite'))
		{
			$this->_deleteActualite();
			$this->clearCache();
		}
		elseif (Tools::getIsset('statusactualite'))
		{
			$this->changeStatusActualite();
			$this->clearCache();
		}
		elseif (Tools::getIsset('statuscolumnactualite'))
		{
			$this->changeStatusColumnActualite();
			$this->clearCache();
		}
		elseif (Tools::getIsset('statushomeactualite'))
		{
			$this->changeStatusHomeActualite();
			$this->clearCache();
		}
		elseif (Tools::getIsset('submitOptions'))
		{
			$this->updateConfiguration();
			$this->clearCache();
		}

		return $this->_displayList();
	}

	public function addActualite()
	{
		$languages = $this->context->controller->getLanguages();
		$id_actualite = Tools::getValue('id_actualite', 0);

		if ((int)$id_actualite > 0)
			$actualite = new Actualite((int)$id_actualite);
		else
			$actualite = new Actualite();

		$actualite->active = Tools::getValue('active');
		$actualite->show_home = Tools::getValue('show_home');
		$actualite->show_column = Tools::getValue('show_column');
		$actualite->date_creation = date('Y-m-d H:i:s');
		foreach ($languages as $language)
		{
			$id_lang = $language['id_lang'];
			$actualite->title[$id_lang] = Tools::getValue('title_'.$id_lang);
			$actualite->short_description[$id_lang] = Tools::getValue('short_description_'.$id_lang);
			$actualite->description[$id_lang] = Tools::getValue('description_'.$id_lang);
		}

		return $actualite->save();
	}

	private function _deleteActualite()
	{
		$actualite = new Actualite((int)Tools::getValue('id_actualite'));
		if (Validate::isLoadedObject($actualite))
			$actualite->delete();
	}

	public function changeStatusActualite()
	{
		$actualite = new Actualite((int)Tools::getValue('id_actualite'));
		if (Validate::isLoadedObject($actualite))
		{
			switch ($actualite->active)
			{
				case 0 : $actualite->active = 1;
					break;
				case 1 : $actualite->active = 0;
					break;
				default : $actualite->active = 0;
					break;
			}

			$actualite->save();
		}
	}

	public function changeStatusHomeActualite()
	{
		$actualite = new Actualite((int)Tools::getValue('id_actualite'));
		if (Validate::isLoadedObject($actualite))
		{
			switch ($actualite->show_home)
			{
				case 0 : $actualite->show_home = 1;
					break;
				case 1 : $actualite->show_home = 0;
					break;
				default : $actualite->show_home = 0;
					break;
			}

			$actualite->save();
		}
	}

	public function changeStatusColumnActualite()
	{
		$actualite = new Actualite((int)Tools::getValue('id_actualite'));
		if (Validate::isLoadedObject($actualite))
		{
			switch ($actualite->show_column)
			{
				case 0 : $actualite->show_column = 1;
					break;
				case 1 : $actualite->show_column = 0;
					break;
				default : $actualite->show_column = 0;
					break;
			}

			$actualite->save();
		}
	}

	public function updateConfiguration()
	{
		$nb_actu_home_page = (int)Tools::getValue('NB_HOME_PHACTU', 3);
		if ($nb_actu_home_page)
			Configuration::updateValue('NB_HOME_PHACTU', $nb_actu_home_page);
		
		$nb_carac_home_page = (int)Tools::getValue('NB_CARAC_PHACTU');
		Configuration::updateValue('NB_CARAC_PHACTU', $nb_carac_home_page);

		$nb_actu_column = (int)Tools::getValue('NB_COLUMN_PHACTU', 3);
		if ($nb_actu_column)
			Configuration::updateValue('NB_COLUMN_PHACTU', $nb_actu_column);

		$nb_left_carac_page = (int)Tools::getValue('NB_LEFT_CARAC_PHACTU');
		Configuration::updateValue('NB_LEFT_CARAC_PHACTU', $nb_left_carac_page);

		$speed = (int)Tools::getValue('SPEED_PHACTU');
		Configuration::updateValue('SPEED_PHACTU', $speed);

		$pause = (int)Tools::getValue('PAUSE_PHACTU');
		Configuration::updateValue('PAUSE_PHACTU', $pause);

		$nb_per_page = (int)Tools::getValue('NB_PER_PAGE_PHACTU', 5);
		if ($nb_per_page)
			Configuration::updateValue('NB_PER_PAGE_PHACTU', $nb_per_page);
	}

	public function getStyle()
	{
		return Configuration::getMultiple(array('COLOR_PHACTU', 'COLOR_HV_PHACTU', 'BG_COLOR_PHACTU', 'BG_COLOR_HV_PHACTU', 'BR_COLOR_PHACTU', 'BR_COLOR_HV_PHACTU'));
	}
	
	public function hookDisplayHome()
	{
		$ph_style = $this->getStyle();
		$actualites = Actualite::getHomeActualites((int)$this->context->language->id, (int)Configuration::get('NB_HOME_PHACTU'));
		$this->context->smarty->assign(array(
			'actualites' => $actualites,
			'nb_actu' => count($actualites),
			'ph_style' => $ph_style,
			'nb_carac' => (int)Configuration::get('NB_CARAC_PHACTU'),
			'phactu_date_format' => $this->context->language->date_format_lite
		));

		$this->context->controller->addCSS(($this->_path).'css/phactu.css', 'all');
		$this->context->controller->addJS(($this->_path).'js/phactu.js');

		return $this->display(($this->_path), 'home_actualite.tpl');
	}

	public function hookDisplayLeftColumn()
	{
		$ph_style = $this->getStyle();
		$actualites = Actualite::getColumnActualites((int)$this->context->language->id, (int)Configuration::get('NB_COLUMN_PHACTU'));
		$this->context->smarty->assign(array(
			'actualites' => $actualites,
			'nb_actu' => count($actualites),
			'ph_style' => $ph_style,
			'nb_carac' => (int)Configuration::get('NB_LEFT_CARAC_PHACTU'),
			'phactu_speed' => (int)Configuration::get('SPEED_PHACTU'),
			'phactu_pause' => (int)Configuration::get('PAUSE_PHACTU'),
			'phactu_date_format' => $this->context->language->date_format_lite
		));

		$this->context->controller->addCSS(($this->_path).'css/phactu.css', 'all');
		//$this->context->controller->addJS(($this->_path).'js/phactu_column.js');
		$this->context->controller->addJqueryPlugin('bxslider');

		return $this->display(($this->_path), 'left_actualite.tpl');
	}

	public function hookDisplayRightColumn()
	{
		return $this->hookDisplayLeftColumn();
	}

	public function clearCache()
	{
		$this->_clearCache('home_actualite.tpl');
		$this->_clearCache('left_actualite.tpl');
	}
}
