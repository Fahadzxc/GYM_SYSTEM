<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGymMembersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'unique' => true,
            ],
            'first_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'middle_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'last_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
            ],
            'address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'phone_no' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'user_type' => [
                'type' => 'ENUM',
                'constraint' => ['staff', 'athlete', 'faculty'],
                'default' => 'athlete',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['active', 'inactive'],
                'default' => 'active',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('gym_members');
    }

    public function down()
    {
        $this->forge->dropTable('gym_members');
    }
}
