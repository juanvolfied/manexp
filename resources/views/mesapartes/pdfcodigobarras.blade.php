<!DOCTYPE html>
<html>
<head>
    <title>PDF Codigo de Brras</title>
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
<div style='position: absolute; top:  800px; left: 20px;'>
<!--<div style='position: absolute; top:  20px; left: 20px; width: 200px; height: 45px; overflow: hidden;'>-->
    <!--<img src="data:image/png;base64,{{ $barcode }}" alt="Código de barras" style="width: 200px; height: 100px;">-->
    <img src="data:image/png;base64,{{ $barcode }}" alt="Código de barras" style="width: 50px; height: 200px;">
</div>

</body>
</html>