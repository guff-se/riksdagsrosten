<!DOCTYPE html>
<html lang="en">
  <head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>Riksdagsr&ouml;sten admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="/includes/bootstrap/css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="/includes/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

  </head>

  <body>
    
    <script>
        $(document).ready(function() {
         
         
         $("#titel").keyup(function(event) {
             if(event.keyCode == 13) 
                   $("#sok").click();
         });
             
         
         
         $("#sok").click(function() {
             
             var keyword = $("#titel").val();
               
             document.location.href = "/admin/search.php?text="+keyword;

         });
          
        });
       
    </script>
      
      <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="/admin">Riksdagsrösten admin</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li <?if($_SERVER['SCRIPT_NAME']=="/admin/index.php"){?>class="active"<?}?>><a href="/admin">Voteringar</a></li>
              <li <?if($_SERVER['SCRIPT_NAME']=="/admin/cohort.php"){?>class="active"<?}?>><a href="/admin/cohort.php">Cohortanalys</a></li>
            </ul>
			<form class="navbar-search pull-left" action="search.php">
			  <input type="text" name="text" class="search-query" placeholder="Sök" value="<? if( isset($_GET['text'])) echo $_GET['text']; ?>">
			</form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
    <div class="container">		