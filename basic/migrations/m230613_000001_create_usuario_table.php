<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%usuario}}`.
 */
class m230613_000001_create_usuario_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%usuario}}', [
            'id' => $this->primaryKey(),
            'nome' => $this->string(100)->notNull(),
            'email' => $this->string(100)->notNull()->unique(),
            'senha' => $this->string(255)->notNull(),
            'criado_em' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%usuario}}');
    }
}
