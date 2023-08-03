<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_v1_4_0 extends CI_Migration {

    public function up()
    {
        // Add favicon column to the sites table
        $token_field = array(
            'favicon' => array(
                'type' => 'LONGTEXT',
                'default' => '',
                'null' => TRUE,
            ),
        );
        $this->dbforge->add_column('sites', $token_field);
		//update blocks height
		$blocks_names = array(
			'elements/Yummy/contact5.html', 
			'elements/Yummy/contact6.html',
			'elements/Yummy/header3.html',
			'elements/Yummy/header4.html',
			'elements/Yummy/header5.html',
			'elements/Yummy/header7.html',
			'elements/Yummy/header8.html',
			'elements/Yummy/header9.html',
			'elements/Yummy/header10.html',
			'elements/Yummy/header11.html',
			'elements/Yummy/header14.html',
			'elements/Yummy/header15.html',
			'elements/Yummy/header16.html',
			'elements/Yummy/header17.html',
			'elements/Yummy/header19.html',
			'elements/Yummy/header24.html',
			'elements/Yummy/header25.html'
		);
		$this->db->set('blocks_height','567')
			->where_in('blocks_url',$blocks_names)
			->update('blocks',$data);
    }

    public function down()
    {
        // Drop column favicon from sites table
        $this->dbforge->drop_column('sites', 'favicon');
		// update old blocks height
		$blocks_names = array(
			'elements/Yummy/contact5.html', 
			'elements/Yummy/contact6.html',
			'elements/Yummy/header3.html',
			'elements/Yummy/header4.html',
			'elements/Yummy/header5.html',
			'elements/Yummy/header7.html',
			'elements/Yummy/header8.html',
			'elements/Yummy/header9.html',
			'elements/Yummy/header10.html',
			'elements/Yummy/header11.html',
			'elements/Yummy/header14.html',
			'elements/Yummy/header15.html',
			'elements/Yummy/header16.html',
			'elements/Yummy/header17.html',
			'elements/Yummy/header19.html',
			'elements/Yummy/header24.html',
			'elements/Yummy/header25.html'
		);
		$this->db->set('blocks_height','90vh')
			->where_in('blocks_url',$blocks_names)
			->update('blocks',$data);
    }
}