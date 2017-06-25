<?php

use yii\db\Migration;

class m170625_144905_transact extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%transact}}', [
            'id'=>$this->primaryKey(),
            'debet'=>$this->integer()->comment('to user.id'),
            'credit'=>$this->integer()->comment('from user.id'),
            'amount'=>$this->decimal(19,2)->defaultValue(0)->comment('amount of transaction'),
            'time'=>$this->integer()->defaultValue(0)->comment('time of transaction')
        ]);
        
        $this->addForeignKey('FK_to_user', '{{%transact}}', 'debet', '{{%user}}', 'id');
        $this->addForeignKey('FK_from_user', '{{%transact}}', 'credit', '{{%user}}', 'id');
        $this->createIndex('idx_debet', '{{%transact}}', ['debet']);
        $this->createIndex('idx_credit', '{{%transact}}', ['credit']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%transact}}');
    }
}
