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
                <?= Html::textInput('quarter', $quarter) ?>
            </div>
            <div class="col-lg-4">
                <label>Rok:</label>
                <?= Html::textInput('year', $year) ?>
            </div>
            <div class="col-lg-4">
                <label>Blocky:</label>
                <?= Html::textInput('bills', $bills) ?>
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
                        'name', 'client', 'vatBase', 'vat', 'date', 'baseInvoice', 'invoice', 'price'
                    )
                ]);
                ?>
            </div>
        </div>

        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Priznanie k DPH</h3>
                </div>
                <ul class="list-group">
                    <li class="list-group-item">Zaklad DPH:</li>
                    <li class="list-group-item">
                        <strong><?= nf($vatBaseSum) ?></strong>
                    </li>
                    <li class="list-group-item">Fakturovana DPH:</li>
                    <li class="list-group-item">
                        <strong><?= nf($vatSum) ?></strong>
                    </li>
                    <li class="list-group-item">Odpocet DPH:</li>
                    <li class="list-group-item">
                        <strong><?= nf($vatDebitsSum) ?></strong>
                    </li>
                    <li class="list-group-item">Vysledna DPH na zaplatenie:</li>
                    <li class="list-group-item">
                        <strong><?= nf($vatSum - $vatDebitsSum) ?></strong>
                    </li>
                </ul>
            </div>
        </div>

        <?= Html::submitButton('Prepocitat', ['name' => 'calculate', 'value' => 1, 'class' => 'btn btn-primary']) ?>
        <?= Html::submitButton('Vygenerovat XML', ['class' => 'btn btn-success']) ?>

        <?php $form->end(); ?>
    </div>
</div>
