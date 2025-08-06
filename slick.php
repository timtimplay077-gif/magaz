<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./slick/slick.css">
    <link rel="stylesheet" type="text/css" href="./slick/slick-theme.css">
    <script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
    <script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>

    <title>Document</title>
</head>

<body>
    <div class="your-class slider">
        <div>your content</div>
        <div>your content</div>
        <div>your content</div>
        <div>your content</div>
        <div>your content</div>
        <div>your content</div>
        <div>your content</div>
    </div>
    <script>
        $(document).on('ready', function () {
            $(".your-class").slick({
                dots: true,
                arrows: true,

            });
        })
    </script>

</body>


</html>