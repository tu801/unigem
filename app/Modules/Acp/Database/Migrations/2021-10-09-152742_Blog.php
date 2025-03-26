<?php

namespace Modules\Acp\Database\Migrations;

use CodeIgniter\Database\Migration;

class Blog extends Migration
{
	public function up()
	{
		/*
         * Categories
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'int', 'unsigned' => true, 'auto_increment' => true],
            'user_init'	      	=> ['type' => 'bigint', 'unsigned' => true],
			'user_type'  		=> ['type' => 'varchar', 'constraint' => 255, 'null' => true], //store user model namespace
            'parent_id'	      	=> ['type' => 'bigint', 'unsigned' => true],
            'cat_type'          => ['type' => 'varchar', 'constraint' => 32, 'default' => 'post'],
            'cat_status'        => ['type' => 'varchar', 'constraint' => 32, 'null' => true],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
            'deleted_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_init');
        $this->forge->createTable('category', true);

        $this->forge->addField([
            'ct_id'             => ['type' => 'bigint',  'unsigned' => true, 'auto_increment' => true],
            'cat_id'           	=> ['type' => 'int',  'unsigned' => true],
            'lang_id'          	=> ['type' => 'int',  'unsigned' => true],
            'title'		        => ['type' => 'varchar', 'constraint' => 255],
            'slug'				=> ['type' => 'varchar', 'constraint' => 255],
            'description'		=> ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'seo_meta'		    => ['type' => 'json', 'null' => true],
        ]);
        $this->forge->addKey('ct_id', true);
        $this->forge->addKey('title');
        $this->forge->addKey('slug');
        $this->forge->addForeignKey('cat_id', 'category', 'id', 'CASCADE', 'CASCADE', 'category_content_cat_id');
        $this->forge->createTable('category_content', true);

		/*
         * Menu
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'lang_id'          	=> ['type' => 'int',  'unsigned' => true],
            'user_init'	      	=> ['type' => 'bigint', 'unsigned' => true],
			'user_type'  		=> ['type' => 'varchar', 'constraint' => 255, 'null' => true], //store user model namespace
            'name'				=> ['type' => 'varchar', 'constraint' => 120],
            'slug'				=> ['type' => 'varchar', 'constraint' => 120],
            'status'			=> ['type' => 'varchar', 'constraint' => 64, 'null' => true],
            'location'			=> ['type' => 'text', 'null' => true],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug');
        $this->forge->addKey('user_init');
        $this->forge->createTable('menu', true);

        /*
         * Menu items
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_init'	      	=> ['type' => 'bigint', 'unsigned' => true],
			'user_type'  		=> ['type' => 'varchar', 'constraint' => 255, 'null' => true], //store user model namespace
            'menu_id'			=> ['type' => 'int', 'unsigned' => true],
            'parent_id'         => ['type' => 'int', 'default' => 0],
            'related_id'		=> ['type' => 'int', 'unsigned' => true],
            'type'				=> ['type' => 'varchar', 'constraint' => 64],
            'title'	            => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'url'			    => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'icon_font'			=> ['type' => 'varchar', 'constraint' => 64, 'null' => true],
            'css_class'			=> ['type' => 'varchar', 'constraint' => 64, 'null' => true],
            'target'			=> ['type' => 'varchar', 'constraint' => 32, 'default' => '_self'],
            'order'             => ['type' => 'tinyint', 'constraint' => 2, 'null' => 0, 'default' => 0],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_init', 'related_id', 'parent_id']);
        $this->forge->addForeignKey('menu_id', 'menu', 'id', 'CASCADE', 'CASCADE', 'menu_items_menu_id');
        $this->forge->createTable('menu_items', true);

        /*
         * Post
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'bigint',  'unsigned' => true, 'auto_increment' => true],
            'user_init'	      	=> ['type' => 'bigint', 'unsigned' => true],
			'user_type'  		=> ['type' => 'varchar', 'constraint' => 255, 'null' => true], //store user model namespace
            'post_status'		=> ['type' => 'varchar', 'constraint' => 20],
            'post_type'			=> ['type' => 'varchar', 'constraint' => 20, 'default' => 'post'],
            'post_views'        => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'post_position'     => ['type' => 'varchar', 'constraint' => 30, 'null' => true],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
            'deleted_at'       	=> ['type' => 'datetime', 'null' => true],
            'publish_date'      => ['type' => 'datetime', 'null' => true]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_init');
        $this->forge->addKey('post_status');
        $this->forge->createTable('post', true);

        $this->forge->addField([
            'ct_id'             => ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'post_id'	      	=> ['type' => 'bigint',  'unsigned' => true],
            'lang_id'	      	=> ['type' => 'int',  'unsigned' => true],
            'title'		        => ['type' => 'varchar', 'constraint' => 255],
            'slug'				=> ['type' => 'varchar', 'constraint' => 255],
            'image'				=> ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'description'		=> ['type' => 'varchar', 'constraint' => 1000, 'null' => true],
            'content'			=> ['type' => 'longtext'],
            'tags'  			=> ['type' => 'json', 'null' => true],
            'seo_meta'		    => ['type' => 'json', 'null' => true],
        ]);
        $this->forge->addKey('ct_id', true);
        $this->forge->addKey('title');
        $this->forge->addKey('slug');
        $this->forge->addKey('lang_id');
        $this->forge->addForeignKey('post_id', 'post', 'id', 'CASCADE', 'CASCADE', 'post_content_post_id');
        $this->forge->createTable('post_content', true);

        /*
         * Post Category
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'cat_id'	      	=> ['type' => 'int',  'unsigned' => true],
            'post_id'	      	=> ['type' => 'bigint',  'unsigned' => true],
            'is_primary'	    => ['type' => 'tinyint', 'constraint' => 2, 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['cat_id', 'post_id']);
        $this->forge->createTable('post_categories', true);


        /*
         * Tags
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'int', 'constraint' => 11, 'auto_increment' => true],
            'lang_id'	      	=> ['type' => 'int',  'unsigned' => true],
            'title'	            => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'slug'      		=> ['type' => 'varchar', 'constraint' => 255, 'null' => false],
			'tag_type'          => ['type' => 'varchar', 'constraint' => 32, 'default' => 'post'],
            'user_init'	      	=> ['type' => 'bigint', 'unsigned' => true],
			'user_type'  		=> ['type' => 'varchar', 'constraint' => 255, 'null' => true], //store user model namespace
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
            'deleted_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('slug');
        $this->forge->addKey('tag_type');
        $this->forge->createTable('tags', true);

        /**
         * Add new table Meta Data
         */
        $this->forge->addField([
            'id'              	=> ['type' => 'bigint', 'unsigned' => true, 'auto_increment' => true],
            'lang_id'	      	=> ['type' => 'int',  'unsigned' => true],
            'mod_name'	        => ['type' => 'varchar', 'constraint' => 32, 'null' => false],
            'mod_id'      		=> ['type' => 'int', 'constraint' => 11, 'null' => false],
            'meta_key'      	=> ['type' => 'varchar', 'constraint' => 32, 'null' => false],
            'meta_value'		=> ['type' => 'text', 'null' => true],
            'created_at'       	=> ['type' => 'datetime', 'null' => true],
            'updated_at'       	=> ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('meta_key');
        $this->forge->addKey('mod_name');
        $this->forge->addKey('mod_id');
        $this->forge->createTable('meta_data', true);

	}

	public function down()
	{
	    // drop foreign key
        $this->forge->dropForeignKey('category_content','category_content_cat_id');
        $this->forge->dropForeignKey('menu_items','menu_items_menu_id');
        $this->forge->dropForeignKey('post_content','post_content_post_id');

		//drop table
		$this->forge->dropTable('category', true);
		$this->forge->dropTable('category_content', true);
        $this->forge->dropTable('menu', true);
        $this->forge->dropTable('menu_items', true);
        $this->forge->dropTable('post', true);
        $this->forge->dropTable('post_content', true);
        $this->forge->dropTable('post_categories', true);
        $this->forge->dropTable('tags', true);
        $this->forge->dropTable('meta_data', true);
	}
}
