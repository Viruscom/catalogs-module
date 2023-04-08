<!DOCTYPE html>
<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('modules/catalogs/public/plugins/flipbook/css/flipbook.style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/catalogs/public/plugins/flipbook/css/font-awesome.css') }}">
    <script src="{{ asset('modules/catalogs/public/plugins/flipbook/js/flipbook.min.js') }}"></script>
</head>
<body>
<div id="container"></div>
<style type="text/css">
    .flipbook-main-wrapper {background-color: # {{ $otherSetting->{'catalog_background_color'} }}  !important;}
</style>
<script type="text/javascript">

    $(document).ready(function () {
        $("#container").flipBook({
            pdfUrl: "{{$mainCatalogTranslation->catalogPreviewPath() }}",
            // lightBox:true,
            // lightBoxOpened:true
        });

    })
</script>
</body>

</html>
