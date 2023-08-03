<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_v1_0_5 extends CI_Migration {

    public function up()
    {
        // Add home_page and ftp_type column in sites table for set home page
        $sites_fields = array(
            'home_page' => array(
                'type' => 'TINYINT',
                'null' => FALSE,
                'default' => 0,
                'after' => 'sub_folder'
            ),
            'ftp_type' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => TRUE,
                'after' => 'sites_lastupdate_on'
            )
        );
        $this->dbforge->add_column('sites', $sites_fields);

        // Add favourite column in frames table
        $frames_fields = array(
            'favourite' => array(
                'type' => 'INT',
                'constraint' => '1',
                'null' => FALSE,
                'default' => 0,
                'after' => 'frames_global'
            )
        );
        $this->dbforge->add_column('frames', $frames_fields);

        // Add ftp_publish column in package table
        $packages_fields = array(
            'ftp_publish' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => FALSE,
                'default' => 'no',
                'after' => 'export_site'
            )
        );
        $this->dbforge->add_column('packages', $packages_fields);

        // Update rows in apps_settings table for Export improvements
        $this->db->query("UPDATE `apps_settings` SET `value` = 'elements/bundles|images' WHERE id = 9");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'elements/bundles|images' WHERE id = 9");

        // Create blocks_categories table
        $this->dbforge->add_field(array(
            'blocks_categories_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'category_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'list_order' => array(
                'type' => 'INT',
                'constraint' => '4',
            )
        ));
        $this->dbforge->add_key('blocks_categories_id', TRUE);
        $this->dbforge->create_table('blocks_categories');

        // Create blocks table
        $this->dbforge->add_field(array(
            'blocks_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'blocks_category' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'blocks_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'blocks_height' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            ),
            'blocks_thumb' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            )
        ));
        $this->dbforge->add_key('blocks_id', TRUE);
        $this->dbforge->create_table('blocks');

        // Create components_categories table
        $this->dbforge->add_field(array(
            'components_categories_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'category_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'list_order' => array(
                'type' => 'INT',
                'constraint' => '4',
            )
        ));
        $this->dbforge->add_key('components_categories_id', TRUE);
        $this->dbforge->create_table('components_categories');

        // Create components table
        $this->dbforge->add_field(array(
            'components_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'components_category' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'components_thumb' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'components_height' => array(
                'type' => 'INT',
                'constraint' => '4',
            ),
            'components_markup' => array(
                'type' => 'LONGTEXT'
            )
        ));
        $this->dbforge->add_key('components_id', TRUE);
        $this->dbforge->create_table('components');

        // Create blocks_fav table
        $this->dbforge->add_field(array(
            'blocks_id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'blocks_url' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'blocks_height' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            ),
            'blocks_thumb' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            )
        ));
        $this->dbforge->add_key('blocks_id', TRUE);
        $this->dbforge->create_table('blocks_fav');
    }

    public function down()
    {
        // Drop column home_page from sites table
        $this->dbforge->drop_column('sites', 'home_page');

        // Drop column ftp_type from sites table
        $this->dbforge->drop_column('sites', 'ftp_type');

        // Drop column favourite from frames table
        $this->dbforge->drop_column('frames', 'favourite');

        // Drop column ftp_publish from packages table
        $this->dbforge->drop_column('packages', 'ftp_publish');

        // Revert back rows in apps_settings table for Export improvements
        $this->db->query("UPDATE `apps_settings` SET `value` = 'elements/bundles|elements/css|images' WHERE id = 9");
        $this->db->query("UPDATE `apps_settings` SET `default_value` = 'elements/bundles|elements/css|images' WHERE id = 9");

        // Drop blocks_categories table
        $this->dbforge->drop_table('blocks_categories', TRUE);
        // Drop blocks table
        $this->dbforge->drop_table('blocks', TRUE);
        // Drop components_categories table
        $this->dbforge->drop_table('components_categories', TRUE);
        // Drop components table
        $this->dbforge->drop_table('components', TRUE);
        // Drop blocks_fav table
        $this->dbforge->drop_table('blocks_fav', TRUE);
    }
}