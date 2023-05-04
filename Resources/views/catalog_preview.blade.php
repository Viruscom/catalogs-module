<!DOCTYPE html>
<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.js"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('front/plugins/flipbook/css/flipbook.style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('front/plugins/flipbook/css/font-awesome.css') }}">
    <script src="{{ asset('front/plugins/flipbook/js/flipbook.min.js') }}"></script>
</head>
<body>
<div id="container"></div>
<style type="text/css">
    .flipbook-main-wrapper {background-color: # {{ $mainSettings->{'catalog_background_color'} }}  !important;}
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
