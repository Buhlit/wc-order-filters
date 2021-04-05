<?php namespace BuhlLib\Classes\Rest;

defined( 'ABSPATH' ) || exit;

use WP_REST_Controller;

class PluginRest extends WP_REST_Controller
{

	public function __construct()
	{
		$this->addActions();
		$this->addFilters();
	}

	/**
	 * Method used to add actions
	 */
	protected function addActions()
	{

	}

	/**
	 * Method used to add filters
	 */
	protected function addFilters()
	{

	}

}

