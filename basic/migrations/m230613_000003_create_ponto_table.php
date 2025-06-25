<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ponto}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%funcionario}}`
 */
class m230613_000003_create_ponto_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%ponto}}', [
            'id' => $this->primaryKey(),
            'id_funcionario' => $this->integer()->notNull(),
            'data' => $this->date()->notNull(),
            'horario' => $this->time()->notNull(),
            'tipo' => "ENUM('entrada', 'saida') NOT NULL",
            'intervalo_num' => $this->integer()->defaultValue(1),
            'criado_em' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Foreign key constraint
        $this->addForeignKey(
            'fk-ponto-id_funcionario',
            '{{%ponto}}',
            'id_funcionario',
            '{{%funcionario}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-ponto-id_funcionario', '{{%ponto}}');
        $this->dropTable('{{%ponto}}');
    }
}
