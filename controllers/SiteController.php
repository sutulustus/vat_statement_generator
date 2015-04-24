<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        list($incomes, $expenses, $corrections) = $this->getData();
        list($vatSum, $vatBaseSum, $vatDebitsSum) = $this->getDeclaration($incomes, $expenses, $corrections);

        $showMsg = Yii::$app->session['success'];
        unset(Yii::$app->session['success']);

        return $this->render('index', array(
            'incomes' => $incomes,
            'expenses' => $expenses,
            'corrections' => $corrections,
            'vatSum' => $vatSum,
            'vatBaseSum' => $vatBaseSum,
            'vatDebitsSum' => $vatDebitsSum + Yii::$app->session['billsDeduction'],
            'year' => Yii::$app->session['year'] ?: date('Y'),
            'quarter' => Yii::$app->session['quarter'] ?: ceil(date('m')/3),
            'bills' => Yii::$app->session['bills'] ?: '',
            'showMsg' => $showMsg
        ));
    }

    public function actionGenerate()
    {
        $selectedIncomes = Yii::$app->request->post('incomes');
        $selectedExpenses = Yii::$app->request->post('expenses');
        $selectedCorrections = Yii::$app->request->post('corrections');

        if(!$selectedIncomes) $selectedIncomes = [];
        if(!$selectedExpenses) $selectedExpenses = [];
        if(!$selectedCorrections) $selectedCorrections = [];

        list($incomes, $expenses, $corrections) = $this->getData();
        list($vatSum, $vatBaseSum, $vatDebitsSum) = $this->getDeclaration($incomes, $expenses, $corrections);

        $incomes = array_intersect_key($incomes, array_flip($selectedIncomes));
        $expenses = array_intersect_key($expenses, array_flip($selectedExpenses));
        $corrections = array_intersect_key($corrections, array_flip($selectedCorrections));

        $bills = (float)Yii::$app->request->post('bills');
        $billsDeduction = ($bills - $bills / 1.2) * 0.8;

        if(Yii::$app->request->post('calculate')) {
            Yii::$app->session['year'] = Yii::$app->request->post('year');
            Yii::$app->session['quarter'] = Yii::$app->request->post('quarter');
            Yii::$app->session['bills'] = Yii::$app->request->post('bills');
            Yii::$app->session['billsDeduction'] = $billsDeduction;
            return $this->goHome();
        }

        $xml = $this->renderPartial('control_statement', array(
            'incomes' => $incomes,
            'expenses' => $expenses,
            'corrections' => $corrections,
            'quarter' => Yii::$app->request->post('quarter'),
            'year' => Yii::$app->request->post('year'),
            'bills' => array(
                'vat' => $bills - $bills / 1.2,
                'vatBase' => $bills / 1.2, 2,
                'deduction' => $billsDeduction
            )
        ));

        file_put_contents("output/control_statement_".date('Y_m_d').'.xml', $xml);

        $xml = $this->renderPartial('vat_declaration', array(
            'vat' => $vatSum,
            'base' => $vatBaseSum,
            'debits' => $vatDebitsSum + $billsDeduction,
            'quarter' => ['I', 'II', 'III', 'IV'][Yii::$app->request->post('quarter') - 1],
            'year' => Yii::$app->request->post('year')
        ));
        file_put_contents("output/vat_declaration_".date('Y_m_d').'.xml', $xml);

        Yii::$app->session['success'] = true;

        return $this->goHome();
    }

    private function getData()
    {
        $clients = $this->getClients();
        $data = $this->getExpenses($clients);
        $expenses = $data['expenses'];
        $corrections = $data['corrections'];
        $incomes = $this->getIncomes();

        uasort($expenses, function($a, $b) { return strtotime($a['date']) - strtotime($b['date']); });
        uasort($corrections, function($a, $b) { return strtotime($a['date']) - strtotime($b['date']); });
        uasort($incomes, function($a, $b) { return strtotime($a['date']) - strtotime($b['date']); });

        return array($incomes, $expenses, $corrections);
    }

    private function getDeclaration($incomes, $expenses, $corrections)
    {
        $vatSum = $vatBaseSum = $vatDebitsSum = 0;

        foreach($incomes as $income) {
            $vatSum += $income['vat'];
            $vatBaseSum += $income['vatBase'];
        }

        foreach($expenses as $expense) {
            $vatDebitsSum += $expense['vat'];
        }

        foreach($corrections as $correction) {
            $vatDebitsSum += $correction['vat'];
        }

        return array($vatSum, $vatBaseSum, $vatDebitsSum);
    }

    private function getClients()
    {
        $data = $this->readCSV('klienti.csv');
        array_shift($data);

        $clients = [];
        foreach($data as $client) {
            if(!$client[6]) continue;
            $clients[$client[0]] = array(
                'ico' => preg_replace('/\s/', '', $client[4]),
                'dic' => $client[5],
                'icdph' => $client[6],
            );
        }

        return $clients;
    }

    private function getIncomes()
    {
        $data = $this->readCSV('vystavene_faktury.csv');
        array_shift($data);

        $incomes = [];
        foreach($data as $income) {
            $date = \DateTime::createFromFormat('d.m.Y', $income[9]);
            $vat = (float)str_replace(array(' ', '.', ','), array('', '', '.'), $income[11]);
            $client = preg_replace('/\s/', '', $income[6]);
            $invoice = $income[2];

            if(!$client || !$invoice) continue;

            $incomes[$invoice] = array(
                'client' => $client,
                'vat' => $vat,
                'vatBase' => (float)str_replace(array(' ', '.', ','), array('', '', '.'), $income[10]),
                'date' => $date->format('Y-m-d'),
                'invoice' => $invoice,
            );
        }

        return $incomes;
    }

    private function getExpenses($clients)
    {
        $data = $this->readCSV('vydavky.csv');
        array_shift($data);

        $expenses = ['expenses' => [], 'corrections' => []];
        foreach($data as $expense) {
            $clientName = $expense[3];
            $vat = (float)str_replace(array(' ', '.', ','), array('', '', '.'), $expense[12]);
            $invoice = $expense[22];
            $date = \DateTime::createFromFormat('d.m.Y', $expense[6]);

            if(!array_key_exists($clientName, $clients) || !$invoice) continue;

            $client = $clients[$clientName];

            $type = $vat > 0 ? 'expenses' : 'corrections';
            $expenses[$type][$invoice] = array(
                'name' => $expense[1],
                'client' => $client['icdph'],
                'vatBase' => (float)str_replace(array(' ', '.', ','), array('', '', '.'), $expense[11]),
                'vat' => $vat,
                'date' => $date->format('Y-m-d'),
                'invoice' => $invoice,
                'price' => (float)$expense[9]
            );

            if(strpos($expense[1], 'DOBROPIS') !== false) {
                $expenses[$type][$invoice]['baseInvoice'] = preg_replace('/DOBROPIS|[^A-z0-9]/', '', $expense[1]);
            }
        }

        return $expenses;
    }

    private function readCSV($fileName)
    {
        $file = "input/$fileName";
        if(!file_exists($file)) return [];

        $csvData = file_get_contents($file);
        $lines = explode(PHP_EOL, $csvData);

        $array = [];
        foreach ($lines as $line) {
            $array[] = str_getcsv($line);
        }
        return $array;
    }
}
