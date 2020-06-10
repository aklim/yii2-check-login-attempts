<?php

namespace aklim\yii2CheckLoginAttempts\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "login_attempt".
 *
 * @property integer $id
 * @property string  $key
 * @property integer $amount
 * @property integer $reset_at
 * @property integer $updated_at
 * @property integer $created_at
 */
class LoginAttempt extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%login_attempt}}';
    }
    
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['amount', 'reset_at', 'updated_at', 'created_at'], 'integer'],
            [['key'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'key'        => Yii::t('app', 'Key'),
            'amount'     => Yii::t('app', 'Amount'),
            'reset_at'   => Yii::t('app', 'Reset At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
    
    /**
     * Delete all user attempts by given user name.
     *
     * @param string $userName
     */
    public static function deleteByUserName(string $userName): void
    {
        $key = self::getKey($userName);
        $attempts = self::findAll(['key' => $key]);
        foreach ($attempts as $attempt) {
            $attempt->delete();
        }
    }
    
    /**
     * Return unique key for the given user name.
     *
     * @param string $userName
     *
     * @return string
     */
    protected static function getKey(string $userName): string
    {
        return sha1($userName);
    }
}
