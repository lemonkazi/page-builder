<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_v1_3_0 extends CI_Migration {

    public function up()
    {
        // Add the columns to the frames table
        $frames_fields = array(
            'frames_popup' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'default' => '',
                'null' => TRUE,
                'after' => 'frames_global'
            ),
            'frames_embeds' => array(
                'type' => 'LONGTEXT',
                'default' => '',
                'null' => TRUE,
                'after' => 'frames_popup'
            ),
            'frames_settings' => array(
                'type' => 'TEXT',
                'default' => '',
                'null' => TRUE,
                'after' => 'frames_embeds'
            )
        );
        $this->dbforge->add_column('frames', $frames_fields);

        // Add column to the sites table
        $sites_fields = array(
            'global_js' => array(
                'type' => 'TEXT',
                'default' => '',
                'null' => TRUE,
                'after' => 'global_css'
            ),
        );
        $this->dbforge->add_column('sites', $sites_fields);

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255
            ),
            'configured' => array(
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => FALSE,
            ),
            'value' => array(
                'type' => 'TEXT',
                'default' => '',
                'null' => TRUE,
            ),
            'date' => array(
                'type' => 'DATETIME',
                'null' => FALSE,
            ),
        ));

        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('integrations');

        // Add column to the integrations table
        $token_field = array(
            'token' => array(
                'type' => 'TEXT',
                'default' => '',
                'null' => TRUE,
            ),
        );
        $this->dbforge->add_column('integrations', $token_field);
    }

    public function down()
    {
        // Drop column frames_popup from frames table
        $this->dbforge->drop_column('frames', 'frames_popup');
        // Drop column frames_embeds from frames table
        $this->dbforge->drop_column('frames', 'frames_embeds');
        // Drop column frames_settings from frames table
        $this->dbforge->drop_column('frames', 'frames_settings');
        // Drop integrations table
        $this->dbforge->drop_table('integrations');
        // Drop column global_js from sites table
        $this->dbforge->drop_column('sites', 'global_js');
    }
}