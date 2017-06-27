<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%transact}}".
 *
 * @property int $id
 * @property int $debet to user.id
 * @property int $credit from user.id
 * @property string $amount amount of transaction
 * @property int $time time of transaction
 *
 * @property User $credit0
 * @property User $debet0
 */
class Transact extends \yii\db\ActiveRecord
{

    public $username='';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['debet', 'credit', 'time'], 'integer'],
            [['username', 'amount'], 'required'],
            [['amount'], 'number','min' => 0.01],
            [['credit'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['credit' => 'id']],
            [['debet'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['debet' => 'id']],
            [['username'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'debet' => Yii::t('app', 'to user.id'),
            'credit' => Yii::t('app', 'from user.id'),
            'amount' => Yii::t('app', 'amount of transaction'),
            'time' => Yii::t('app', 'time of transaction'),
            'username' => Yii::t('app', 'username'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreditUser()
    {
        return $this->hasOne(User::className(), ['id' => 'credit']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDebetUser()
    {
        return $this->hasOne(User::className(), ['id' => 'debet']);
    }
}
