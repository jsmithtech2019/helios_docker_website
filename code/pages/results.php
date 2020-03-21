<?php include('../login.php');?>

<!-- ./var/www/html/pages/results.php -->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 
    <!-- not sure what this one ^^ does... all 3 are needed to come first?-->

    <!-- Bootstrap Core CSS -->
    <link href="/css/bootstrap.css" rel="stylesheet">

    <!-- Custom CSS: You can use this stylesheet to override any Bootstrap styles and/or apply your own styles -->
    <link href="/css/random.css" rel="stylesheet">

    <!-- CSS for formatting the text -->
    <link href="/css/text.css" rel="stylesheet" >

    <!-- CSS for formatting the top nav bar -->
    <link href="/css/navbar.css" rel="stylesheet" >

    <!-- CSS for formatting the images -->
    <link href="/css/images.css" rel="stylesheet" >

    <!-- CSS for formatting the buttons -->
    <link href="/css/button.css" rel="stylesheet" >

    <!-- Custom Fonts from Google -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

    <style>
        #prettytable td, #prettytable th {
            border: 1px solid #aaa;
            padding: 6px;
        }
        #prettytable th {
            padding-top: 10px;
            padding-bottom: 10px;
            text-align: center;
            background-color: #808080;
            color: white;
        }
    </style>

    <title>Test Results Portal</title>
</head>

<body>
    <!-- Navigation -->
    <nav id="siteNav" class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Logo and responsive toggle -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">
                    <span class="glyphicon glyphicon-tint"></span>
                    TEST RESULTS
                </a>
            </div>
            <!-- Navbar links -->
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a href="/">Home</a>
                    </li>
                    <li class="active">
                        <a href="/pages/results.php">Results</a>
                    </li>
                    <li>
                        <a href="/pages/admin.php">Administration</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Documentation<span class="caret"></span></a>
                        <ul class="dropdown-menu" aria-labelledby="about-us">
                            <li><a href="/documentation/duet/index.html">DUET</A></li>
                            <li><a href="/documentation/controller-application/index.html">Flutter</a></li>
                            <li><a href="/documentation/rtos/index.html">TI-RTOS</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/pages/contact.html">Contact</a>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container -->
    </nav>

    <!-- Header -->
    <header>
        <div class="header-content1">
            <div class="container">
                <br><br><br>
                <?php include('../test_data.php');?>
            </div>
        </div>
    </header>

    <!-- all this nonsense is for handling animations of the header from what I can tell... -->
    <!-- jQuery -->
    <script src="/js/jquery-1.11.3.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="/js/jquery.easing.min.js"></script>

    <!-- Custom Javascript -->
    <script src="/js/custom.js"></script>

</body>
</html>