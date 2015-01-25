<?xml version="1.0" encoding="utf-8"?>
<KVDPH xmlns="https://ekr.financnasprava.sk/Formulare/XSD/kv_dph_2014.xsd">
    <Identifikacia>
        <IcDphPlatitela>SK1082215475</IcDphPlatitela>
        <Druh>R</Druh>
        <Obdobie>
            <Rok><?= $year ?></Rok>
            <Stvrtrok><?= $quarter ?></Stvrtrok>
        </Obdobie>
        <Nazov>Michal Juras</Nazov>
        <Stat></Stat>
        <Obec>Banská Bystrica I</Obec>
        <PSC>97411</PSC>
        <Ulica>Javornícka</Ulica>
        <Cislo>6169/31</Cislo>
        <Tel></Tel>
        <Email></Email>
    </Identifikacia>
    <Transakcie>
        <?php foreach($incomes as $income) : ?>
            <A1 S="20" D="<?= number_format($income['vat'], 2, '.', '') ?>" Z="<?= number_format($income['vatBase'], 2, '.', '') ?>" Den="<?= $income['date'] ?>" F="<?= $income['invoice'] ?>" Odb="<?= $income['client'] ?>"/>
        <?php endforeach; ?>
        <?php foreach($expenses as $expense) : ?>
            <B2 O="<?= number_format($expense['vat'], 2, '.', '') ?>" S="20" D="<?= number_format($expense['vat'], 2, '.', '') ?>" Z="<?= number_format($expense['vatBase'], 2, '.', '') ?>" Den="<?= $expense['date'] ?>" F="<?= $expense['invoice'] ?>" Dod="<?= $expense['client'] ?>"/>
        <?php endforeach; ?>
        <?php /*foreach($corrections as $correction) : ?>
            <A1 S="20" D="480.00" Z="2400.00" Den="2014-07-31" F="201407" Odb="SK2023735901"/>
        <?php endforeach; */?>
        <B3 O="<?= number_format($bills['deduction'], 2, '.', '') ?>" D="<?= number_format($bills['vat'], 2, '.', '') ?>" Z="<?= number_format($bills['vatBase'], 2, '.', '') ?>"/>
    </Transakcie>
</KVDPH>
