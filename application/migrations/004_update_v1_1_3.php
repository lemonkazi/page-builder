<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_v1_1_3 extends CI_Migration {

    public function up()
    {

        // Add cloudflare_dns column to the "sites" table
        $sites_fields = array(
            'cloudflare_dns' => array(
                'type' => 'INT',
                'constraint' => '1',
                'default' => '0',
                'after' => 'sitethumb'
            )
        );
        $this->dbforge->add_column('sites', $sites_fields);

        // Add login_token column to the "users" table
        $users_fields = array(
            'login_token' => array(
                'type' => 'VARCHAR',
                'constraint' => '40',
                'default' => '',
                'null' => TRUE,
                'after' => 'modified_at'
            )
        );
        $this->dbforge->add_column('users', $users_fields);

        // Add entry for overwrite_blocks to the core_settings
        $insert_data = array(
            array(
                'name' => 'overwrite_blocks',
                'value' => 'yes',
                'default_value' => 'yes',
                'description' => '<h5>Overwrite blocks</h5><p>If turned on, the auto update will overwrite the blocks (when the update includes changes to the blocks). If you have made modifications to the default blocks and do not want to risk those changes being overwritten, you should turn this off.</p>',
                'required' => '1'
            )
        );
        $this->db->insert_batch('core_settings', $insert_data);
    }

    public function down()
    {

        // Drop column cloudflare_dns from sites table
        $this->dbforge->drop_column('sites', 'cloudflare_dns');

        // Drop column login_token from users table
        $this->dbforge->drop_column('users', 'login_token');

        // Remove entry for overwrite_blocks from core_settings
        $this->db->delete('core_settings', array('name' => 'overwrite_blocks'));
    }
}