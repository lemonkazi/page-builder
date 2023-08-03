<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends MX_Controller {
    function __construct()
    {
        parent::__construct();

        /** load custom settings from apps_settings */
        $this->load->model(array('settings/Apps_settings_model' => 'MApps'));
        $apps = $this->MApps->get_all();

		foreach ($apps as $app)
		{
			$this->config->set_item($app->name, $app->value);
		}

        /** load custom settings from settings */
        $this->load->model(array('settings/Settings_model' => 'MSettings'));
        $apps = $this->MSettings->get_all();

        foreach ($apps as $app)
        {
            $this->config->set_item($app->name, $app->value);
        }

        CI::$APP->controller = $this;
    }

}