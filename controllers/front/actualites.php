<?php

include_once dirname(__FILE__).'/../../classes/Actualite.php';

class PhActuActualitesModuleFrontController extends ModuleFrontController
{

	private $_errors;

	public function setMedia()
	{
		parent::setMedia();
		$this->addCSS(_MODULE_DIR_.'phactu/css/phactu.css');
	}

	public function init()
	{
		parent::init();

		$this->_errors = array();
	}

	public function initContent()
	{
		parent::initContent();

		if ((int)Tools::isSubmit('actualite'))
			$this->assignOne();
		else
			$this->assignAll();

		$this->context->smarty->assign('phactu_date_format', $this->context->language->date_format_lite);
		$this->setTemplate('front_actualite.tpl');
	}

	public function assignAll()
	{
		$nb_per_page = Configuration::get('NB_PER_PAGE_PHACTU');
		$actualites = Actualite::getAllActive($this->context->language->id);

		$nb_actu = count($actualites);
		if ($nb_actu > $nb_per_page)
		{
			$nb_max_page = Tools::ceilf($nb_actu / $nb_per_page);
			$num_page = (int)Tools::getValue('page', 1);
			($num_page <= 0) ? $num_page = 1 : (($num_page > $nb_max_page) ? $nb_max_page : '');

			$this->context->smarty->assign(array(
				'page' => $num_page,
				'nbPage' => $nb_max_page,
				'prevPage' => ($num_page == 1 ? 1 : ($num_page >= $nb_max_page ? $nb_max_page - 1 : $num_page - 1)),
				'nextPage' => ($num_page >= $nb_max_page ? $nb_max_page : ($num_page <= 1 ? 2 : $num_page + 1)),
			));

			$actualites = Actualite::getAllActive($this->context->language->id, (($num_page - 1) * $nb_per_page), $nb_per_page);
		}
		$this->context->smarty->assign(array(
			'actualites' => $actualites,
			'nbActu' => $nb_actu
		));
	}

	public function assignOne()
	{
		$page = Tools::getValue('page', 1);
		$actualite = new Actualite((int)Tools::getValue('actualite'), $this->context->language->id);
		if (!Validate::isLoadedObject($actualite) || !$actualite->active)
			$this->_errors[] = Tools::displayError('Impossible d\'afficher le contenu de cette actualitée.');

		if (count($this->_errors) > 0)
		{
			$this->context->smarty->assign('pherrors', $this->_errors);
			return;
		}

		$this->context->smarty->assign(array(
			'actualite' => $actualite,
			'page' => $page
		));
	}
}
