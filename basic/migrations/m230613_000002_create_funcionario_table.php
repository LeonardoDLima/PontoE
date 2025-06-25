<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%funcionario}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%usuario}}`
 */
class m230613_000002_create_funcionario_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%funcionario}}', [
            'id' => $this->primaryKey(),
            'id_usuario' => $this->integer()->notNull(),
            'nome' => $this->string(100)->notNull(),
            'cpf' => $this->string(20)->notNull()->unique(),
            'senha' => $this->string(255)->notNull(),
            'criado_em' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Foreign key constraint
        $this->addForeignKey(
            'fk-funcionario-id_usuario',
            '{{%funcionario}}',
            'id_usuario',
            '{{%usuario}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-funcionario-id_usuario', '{{%funcionario}}');
        $this->dropTable('{{%funcionario}}');
    }
}
