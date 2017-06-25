<?php
use yii\grid\GridView;

$this->title = 'My Yii Application';
?>

<div class="site-index">

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">

                <h2>Users</h2>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
            
                        'username',
                        'balance',
            
                    ],
                ]); ?>

            </div>
        </div>

    </div>
</div>
