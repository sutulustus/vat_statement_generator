<?xml version="1.0" encoding="utf-8"?>
<dokument>
    <hlavicka>
        <identifikacneCislo>
            <kodStatu>SK</kodStatu>
            <cislo>1082215475</cislo>
        </identifikacneCislo>
        <dic></dic>
        <danovyUrad>Banská Bystrica</danovyUrad>
        <nevzniklaPov>0</nevzniklaPov>
        <typDP>
            <rdp>1</rdp>
            <odp>0</odp>
            <ddp>0</ddp>
            <datumZisteniaDdp></datumZisteniaDdp>
        </typDP>
        <osoba>
            <platitel>1</platitel>
            <registrovana>0</registrovana>
            <inaPovinna>0</inaPovinna>
            <zdanitelna>0</zdanitelna>
            <zastupca>0</zastupca>
        </osoba>
        <zdanObd>
            <mesiac></mesiac>
            <stvrtrok><?= $quarter ?></stvrtrok>
            <rok><?= $year ?></rok>
        </zdanObd>
        <meno>
            <riadok>Michal Juras</riadok>
            <riadok></riadok>
            <riadok></riadok>
        </meno>
        <adresa>
            <ulica>Javornícka</ulica>
            <cislo>6169/31</cislo>
            <psc>97411</psc>
            <obec>Banská Bystrica I</obec>
            <tel>
                <predcislie></predcislie>
                <cislo></cislo>
            </tel>
            <fax>
                <predcislie></predcislie>
                <cislo></cislo>
            </fax>
        </adresa>
        <opravnenaOsoba>
            <menoPriezvisko></menoPriezvisko>
            <tel>
                <predcislie></predcislie>
                <cislo></cislo>
            </tel>
        </opravnenaOsoba>
        <datumVyhlasenia><?= date('d.m.Y') ?></datumVyhlasenia>
    </hlavicka>
    <telo>
        <r01></r01>
        <r02></r02>
        <r03><?= nf($base) ?></r03>
        <r04><?= nf($vat) ?></r04>
        <r05></r05>
        <r06></r06>
        <r07></r07>
        <r08></r08>
        <r09></r09>
        <r10></r10>
        <r11></r11>
        <r12></r12>
        <r13></r13>
        <r14></r14>
        <r15></r15>
        <r16></r16>
        <r17></r17>
        <r18></r18>
        <r19><?= nf($vat) ?></r19>
        <r20></r20>
        <r21><?= nf($debits) ?></r21>
        <r22></r22>
        <r23><?= nf($debits) ?></r23>
        <r24></r24>
        <r25></r25>
        <r26></r26>
        <r27></r27>
        <r28></r28>
        <r29></r29>
        <r30></r30>
        <r31><?= nf($vat - $debits) ?></r31>
        <splneniePodmienok>0</splneniePodmienok>
        <r32></r32>
        <r33></r33>
        <r34><?= nf($vat - $debits) ?></r34>
        <r35></r35>
        <r36></r36>
        <r37></r37>
        <r38></r38>
    </telo>
</dokument>
