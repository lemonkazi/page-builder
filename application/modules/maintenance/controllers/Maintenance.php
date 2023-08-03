<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Maintenance extends MY_Controller {

    /**
     * Class constructor
     *
     * Loads required models, loads the hook class and add a hook point
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();

        $model_list = [
            'sites/Frames_model' => 'MFrames',
        ];
        $this->load->model($model_list);

        $this->hooks = load_class('Hooks', 'core');

        /** Hook point */
        $this->hooks->call_hook('home_construct');
    }


    public function revisionHistory() {
        $delete_limited_frames = $this->MFrames->delete_limited_frames();

        if ($delete_limited_frames) {
            echo 'Old revisions have been cleared successfully';
        }
    }

    public function index () {

    }

}
