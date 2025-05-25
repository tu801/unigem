<?php

namespace App\Database\Migrations;

use App\Enums\ContactEnum;
use CodeIgniter\Database\Migration;

class AddContactTable extends Migration
{
    public function up()
    {
        /**
         * Add new table contact
         */
        $this->forge->addField([
            'id'                 => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'contact_type'       => ['type' => 'varchar', 'constraint' => 32, 'default' => ContactEnum::FORM_CONTACT_TYPE],
            'fullname'           => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'email'              => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'phone'              => ['type' => 'varchar', 'constraint' => 255, 'null' => false],
            'subject'            => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
            'message'            => ['type' => 'text', 'null' => true],
            'status'             => ['type' => 'varchar', 'constraint' => 32, 'default' => ContactEnum::STATUS_NEW],
            'ip_address'            => ['type' => 'varchar', 'constraint' => 255],
            'created_at'            => ['type' => 'datetime', 'null' => true],
            'updated_at'            => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('contact_type');
        $this->forge->addKey('email');
        $this->forge->addKey('phone');
        $this->forge->addKey(['contact_type', 'status']);
        $this->forge->addKey(['contact_type', 'ip_address']);
        $this->forge->createTable('contacts', true);
    }

    public function down()
    {
        //drop tables
        $this->forge->dropTable('contacts', true);
    }
}