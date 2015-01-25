<?php
/* @var $this yii\web\View */

use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Generator kontrolneho vykazu DPH';
?>
<div class="site-index">

    <div class="body-content">
        <?php
            $form = ActiveForm::begin([
                'method' => 'post',
                'action' => ['site/generate'],
            ]);
        ?>

        <?php if($showMsg) : ?>
            <div class="row">
                <div class="alert alert-success" role="alert">
                  <strong>Uspech!</strong> Kontrolny vykaz bol vygenerovany.
                </div>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-4">
                <label>Kvartal:</label>
                <?= Html::textInput('quarter', ceil(date('m')/3)) ?>
            </div>
            <div class="col-lg-4">
                <label>Rok:</label>
                <?= Html::textInput('year', date('Y')) ?>
            </div>
            <div class="col-lg-4">
                <label>Blocky:</label>
                <?= Html::textInput('bills') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php
                    $incomesProvider = new ArrayDataProvider([
                        'allModels' => $incomes,
                    ]);
                    echo GridView::widget([
                        'dataProvider' => $incomesProvider,
                        'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                        'caption' => 'Prijmy',
                        'summary' => '',
                        'showOnEmpty' => false,
                        'rowOptions' => function($model) {
                            return ['class' => $model['vat'] ? '' : 'danger'];
                        },
                        'columns' => array(
                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'name' => 'incomes',
                                'checkboxOptions' => function($model) {
                                        return [
                                            'value' => $model['invoice'],
                                            'checked' => $model['vat'] ? 'checked' : ''
                                        ];
                                    },
                            ],
                            'client', 'vat', 'vatBase', 'date', 'invoice'
                        )
                    ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php
                $expensesProvider = new ArrayDataProvider([
                    'allModels' => $expenses,
                ]);
                echo GridView::widget([
                    'dataProvider' => $expensesProvider,
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                    'caption' => 'Naklady',
                    'summary' => '',
                    'showOnEmpty' => false,
                    'rowOptions' => function($model) {
                        return ['class' => $model['vat'] ? '' : 'danger'];
                    },
                    'columns' => array(
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'expenses',
                            'checkboxOptions' => function($model) {
                                return [
                                    'value' => $model['invoice'],
                                    'checked' => $model['vat'] ? 'checked' : ''
                                ];
                            },
                        ],
                        'name', 'client', 'vatBase', 'vat', 'date', 'invoice', 'price'
                    )
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <?php
                $correctionsProvider = new ArrayDataProvider([
                    'allModels' => $corrections,
                ]);
                echo GridView::widget([
                    'dataProvider' => $correctionsProvider,
                    'tableOptions' => ['class' => 'table table-striped table-bordered table-hover'],
                    'caption' => 'Dobropisy',
                    'summary' => '',
                    'showOnEmpty' => false,
                    'rowOptions' => function($model) {
                        return ['class' => $model['vat'] ? '' : 'danger'];
                    },
                    'columns' => array(
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'corrections',
                            'checkboxOptions' => function($model) {
                                    return [
                                        'value' => $model['invoice'],
                                        'checked' => $model['vat'] ? 'checked' : ''
                                    ];
                                },
                        ],
                        'name', 'client', 'vatBase', 'vat', 'date', 'invoice', 'price'
                    )
                ]);
                ?>
            </div>
        </div>

        <?= Html::submitButton('Submit', ['class' => 'btn btn-success']) ?>

        <?php $form->end(); ?>
    </div>
</div>
