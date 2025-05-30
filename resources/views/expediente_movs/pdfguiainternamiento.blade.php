<!DOCTYPE html>
<html>
<head>
    <title>PDF Guia de Intermaniento</title>
    <style>
        body { font-family: sans-serif; }
        h1 { color: navy; }
    </style>
<style>
    @page {
        margin-left: 20mm;
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
    ];    
    return $ordinales[$numero] ?? $numero . 'º';
}    
?>
<div style='position: absolute; top:  250px; left: 20px;' >
<!--<div style="float: left; transform: rotate(-90deg); transform-origin: left top; width: 200px; margin-right: 20px;">-->
    <img src="data:image/png;base64,{{ $barcode }}" alt="Código de barras" style="width: 50px; height: 350px;">
</div>

@php
    $total = count($regdet);
    $primerBloque = $regdet->take(6);
    $resto = $regdet->slice(6)->chunk(10);
@endphp

<div>    

	<br><br><div style='padding-left:30px;'><font style='font-size:22pt;'>{{ $regcab->abreviado }}</font><br>
	<font style='font-size:30pt;'>{{ str_pad($regcab->nro_mov, 5, '0', STR_PAD_LEFT) }}-{{ $regcab->ano_mov }}-{{ $regcab->tipo_mov == 'GI' ? 'I' : $regcab->tipo_mov }}</font><br></div>

    <div style='padding-left:30px;'><br><table align='center'><tr><td><b>INVENTARIO DE TRANSFERENCIA DE CARPETAS FISCALES </b></td></tr></table>
	<table style='font-size:9pt;'><tr><td><b>1. PROCEDENCIA:</b></td><td>{{ $regcab->descripcion }}</td></tr>
	<tr><td><b>2. FISCAL: </b></td><td>{{ $regcab->apellido_paterno }} {{ $regcab->apellido_materno }} {{ $regcab->nombres }}</td></tr>
	<tr><td><b>3. DESPACHO FISCAL: </b></td><td>{{ numeroAOrdinal($regcab->despacho) }} DESPACHO</td></tr>
	<!--<tr><td><b>4. FECHA: </b></td><td>{{ $regcab->fechahora_movimiento }}</td></tr></table><br></div>-->
	<tr><td><b>4. FECHA: </b></td><td>{{ \Carbon\Carbon::parse($regcab->fechahora_movimiento)->translatedFormat('d \d\e F \d\e Y') }}</td></tr></table><br></div>


    <table id="scanned-list" class="table table-striped table-sm" width="100%" border=1 style="border-collapse: collapse;">
        <thead class="thead-dark">
            <tr>
            <th width=4% align="center" rowspan=2>NRO</th>			      
            <th width=17% align="center" rowspan=2>CARPETA FISCAL</th>			      
            <th width=6% align="center" rowspan=2>FOLIOS</th>
            <th width=19% align="center">AGRAVIADO</th>
            <th width=19% align="center">IMPUTADO</th>
            <th align="center" rowspan=2>DELITO</th>
            </tr>
            <tr>
            <td align='center'><b>APELLIDOS Y NOMBRES</b></td>
            <td align='center'><b>APELLIDOS Y NOMBRES</b></td>
            </tr>
        </thead>
        <tbody style="font-size:12px;" >
        @foreach ($primerBloque as $detalle)
        <!--foreach ($regdet as $detalle)-->
            <tr>
            <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $loop->iteration }}</td>
            <td style='padding-top:10px; padding-bottom:10px;' align="center">{{ $detalle->id_dependencia }}-{{ $detalle->ano_expediente }}-{{ $detalle->nro_expediente }}-{{ $detalle->id_tipo }}</td>
            <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $detalle->nro_folios }}</td>
            <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $detalle->agraviado }}</td>
            <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $detalle->imputado }}</td>
            <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $detalle->desc_delito }}</td>
            </tr>
        @endforeach
    </tbody>
    </table>


{{-- Separador de página para mPDF --}}
@if($resto->count() > 0)
    <pagebreak />
@endif

{{-- Tablas con bloques de 10 --}}
@foreach ($resto as $chunkIndex => $chunk)
<br><br><br><br>
    <table class="table" width="100%" border="1" style="border-collapse: collapse;">
        <thead>
            <tr>
            <th width=4% align="center" rowspan=2>NRO</th>			      
            <th width=17% align="center" rowspan=2>CARPETA FISCAL</th>			      
            <th width=6% align="center" rowspan=2>FOLIOS</th>
            <th width=19% align="center">AGRAVIADO</th>
            <th width=19% align="center">IMPUTADO</th>
            <th align="center" rowspan=2>DELITO</th>
            </tr>
            <tr>
            <td align='center'><b>APELLIDOS Y NOMBRES</b></td>
            <td align='center'><b>APELLIDOS Y NOMBRES</b></td>
            </tr>
        </thead>
        <tbody>
            @foreach ($chunk as $index => $detalle)
            <tr>
                <!--<td>{{ 7 + ($chunkIndex * 10) + $index }}</td>-->
                <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ 1 + $index }}</td>
                <td style='padding-top:10px; padding-bottom:10px;' align="center">{{ $detalle->id_dependencia }}-{{ $detalle->ano_expediente }}-{{ $detalle->nro_expediente }}-{{ $detalle->id_tipo }}</td>
                <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $detalle->nro_folios }}</td>
                <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $detalle->agraviado }}</td>
                <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $detalle->imputado }}</td>
                <td style='padding-top:10px; padding-bottom:10px; font-size:12px;' align="center">{{ $detalle->desc_delito }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Solo agregar page break si hay más tablas después --}}
    @if (!$loop->last)
        <pagebreak />
    @endif
@endforeach


	<br><br><br><br><table align='center' style='border-collapse: collapse;'>
	<tr>
	<td align='center' ><b>_____________________________________________<br>FISCAL QUE INTERNA</b></td>
	<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td align='center' ><b>_____________________________________________<br>PERSONAL DEL ARCHIVO DESCONCENTRADO</b></td>
	</tr>
	</table>

</div>
</body>
</html>