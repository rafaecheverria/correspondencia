<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name')); ?></title>

        <!-- Fonts -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <link href="static/css/nucleo_icons.css" rel="stylesheet" />

         <!-- <link href="css/bootstrap.min.css" rel="stylesheet" /> 

        <link href="css/light-bootstrap-dashboard.css?v=2.0.1" rel="stylesheet" /> -->

        <!-- CSS Just for demo purpose, don't include it in your project -->

         <!-- <link href="css/demo.css" rel="stylesheet" /> -->

        <!-- <link href="css/login.css" rel="stylesheet" /> -->

    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/app.css')); ?>"> 

    </head>

    <body>

        <div id="app">

            <div class="wrapper">

                <?php echo $__env->make('layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="main-panel">

                    <?php echo $__env->make('layouts.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                    <?php echo $__env->yieldContent('content'); ?>

                </div>

            </div>

        </div>

    </body>

    <script type="text/javascript" src="<?php echo e(asset('js/main.js')); ?>"></script>
    <!-- <script src="js/jquery.min.js" type="text/javascript"></script>
    <script src="js/popper.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/bootstrap-switch.js"></script>
    <script src="js/light-bootstrap-dashboard.js" type="text/javascript"></script>
    <script src="js/demo.js"></script>  -->
    <!-- <script src="js/login.js"></script> -->

</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/correspondencia/resources/views/layouts/principal.blade.php ENDPATH**/ ?>