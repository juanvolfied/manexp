<!DOCTYPE html>
<html>
<head>
    <title>PDF Consulta de Escritos por Fiscal</title>
    <style>
        body { font-family: sans-serif; }
        h1 { color: navy; }
    </style>
<style>
    @page {
        margin-left: 15mm;
        margin-right: 8mm;
    }
</style>    
</head>
<body>

<?php
function numeroAOrdinal($numero) {
    $ordinales = [
        0 => '',
        1 => '1er',
        2 => '2do',
        3 => '3er',
        4 => '4to',
        5 => '5to',
        6 => '6to',
        7 => '7mo',
        8 => '8vo',
        9 => '9no',
        10 => '10mo',
        11 => '11er',
    ];    
    return $ordinales[$numero] ?? $numero . 'º';
}
?>
@php
    $tipos = [
    'E' => 'Escrito',
    'O' => 'Oficio',
    'S' => 'Solicitud',
    'C' => 'Carta',
    'I' => 'Invitación',
    'F' => 'Informe',
    'Z' => 'OTROS',
    ];
@endphp

<div style='position: absolute; top:  25px; right: 20px; width: 220px; height: 70px; overflow: hidden;'>
    <img src="data:image/png;base64,{{ $barcode }}" alt="Código de barras" style="width: 220px; height: 100px;">
    <!--<img src="data:image/png;base64,{{ $barcode }}" alt="Código de barras" style="width: 50px; height: 200px;">-->
</div>

<table width=100%><tr><td align="center" style="font-size: 18px;"><b>ENTREGA DE DOCUMENTOS</b></td></tr></table>
<div style="font-size:12px;">    

<b>FISCAL :</b> {{ $datosfiscal->apellido_paterno ?? '' }} {{ $datosfiscal->apellido_materno ?? '' }} {{ $datosfiscal->nombres ?? '' }}
<br>{{ $abreviado ?? '' }} - {{ numeroAOrdinal($despacho) ?? '' }} DESPACHO
<br><b>FECHA :</b> {{ $fechareg }} <br><br>
    <table id="scanned-list" class="table table-striped table-sm" border=1 style="width:100%; border-collapse: collapse;">
        <thead class="thead-dark" style="font-size:10px;">
            <tr>
            <th align="left" style="padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;">#</th>			      
            <th align="left" style="padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;">ID ESCRITO</th>			      
            <th align="left" style="padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;">TIPO</th>			      
            <th align="left" style="padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;" width="20%">DESCRIPCI&Oacute;N</th>
            <th align="left" style="padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;">DEPENDENCIA ORIGEN</th>
            <th align="left" style="padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;">REMITENTE</th>
            <th align="left" style="padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;">CARPETA FISCAL</th>
            <th align="left" style="padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;">FOLIOS</th>
            </tr>
        </thead>
        <tbody style="font-size:10px;" >
        @foreach ($segdetalle as $detalle)
            <tr>
            <td style='padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;' >{{ $loop->iteration }}</td>
            <td style='padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;' >{{ $detalle->codescrito }}</td>
            <td style='padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;' >{{ $tipos[$detalle->tipo] ?? $detalle->tipo }}</td>
            <td style='padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;' >{{ $detalle->descripcion ?? '' }}</td>
            <td style='padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;' >{{ $detalle->dependenciapolicial ?? '' }}</td>
            <td style='padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;' >{{ $detalle->remitente ?? '' }}</td>
            <td style='padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;' >{{ $detalle->carpetafiscal ?? '' }}</td>
            <td style='padding-right:5px; padding-top:3px; padding-bottom:3px; font-size:9px;' >{{ $detalle->folios ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
    </table>

</div>
</body>
</html>