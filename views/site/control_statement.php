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
            <A1 S="20" D="<?= nf($income['vat']) ?>" Z="<?= nf($income['vatBase']) ?>" Den="<?= $income['date'] ?>" F="<?= $income['invoice'] ?>" Odb="<?= $income['client'] ?>"/>
        <?php endforeach; ?>
        <?php foreach($expenses as $expense) : ?>
            <B2 O="<?= nf($expense['vat']) ?>" S="20" D="<?= nf($expense['vat']) ?>" Z="<?= nf($expense['vatBase']) ?>" Den="<?= $expense['date'] ?>" F="<?= $expense['invoice'] ?>" Dod="<?= $expense['client'] ?>"/>
        <?php endforeach; ?>
        <B3 O="<?= nf($bills['deduction']) ?>" D="<?= nf($bills['vat']) ?>" Z="<?= nf($bills['vatBase']) ?>"/>
        <?php foreach($corrections as $correction) : ?>
            <C2 OR="<?= nf($correction['vat']) ?>" S="20" DR="<?= nf($correction['vat']) ?>" ZR="<?= nf($correction['vatBase']) ?>" FP="<?= $correction['baseInvoice'] ?>" FO="<?= $correction['invoice'] ?>" Dod="<?= $correction['client'] ?>"/>
        <?php endforeach; ?>
    </Transakcie>
</KVDPH>
