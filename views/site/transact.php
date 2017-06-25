<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Transact */
/* @var $form ActiveForm */
?>
<div class="site-transact">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'username') ?>
        <?= $form->field($model, 'amount') ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

    <div class="row">
        <div class="col-lg-4">

            <h2>Transactions</h2>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
        
                    [
                        'attribute'=>'debet',
                        'value'=>function($data){
                                return $data->debetUser->username;
                        }
                    ],
                    [
                        'attribute'=>'credit',
                        'value'=>function($data){
                                return $data->creditUser->username;
                        }
                    ],
                    'amount',
                    [
                        'attribute'=>'time',
                        'value'=>function($data){
                                return date('d.m.Y H:i',$data->time);
                        }
                    ],
        
                ],
            ]); ?>

        </div>
    </div>

</div><!-- site-transact -->
