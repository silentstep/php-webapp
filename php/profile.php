<!DOCTYPE html>
<html>
<head>
<?php
# start a session to get variables;
session_start();
if ($_SESSION['logged_in'] != 1) {
    $_SESSION['error'] = true;
    header("location: /php/error.php");
} else {
    # take session variable for the specific user;
    $uid = $_SESSION['uid'];
    $first = $_SESSION['first'];
    $last = $_SESSION['last'];
    $hash = $_SESSION['hash'];
}
?>
<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
<META HTTP-EQUIV="EXPIRES" CONTENT="Mon, 22 Jul 2002 11:12:01 GMT">
    <meta charset="UTF-8">
    <title><?php echo $_SESSION['first']; ?>'s profile</title>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<link href="https://fonts.googleapis.com/css?family=Pinyon+Script|Ubuntu" rel="stylesheet">

<link rel="stylesheet" href="/css/awesomplete.css">
<link rel="stylesheet" href="/css/materialize.css">
<link rel="stylesheet" href="/css/secondary.css">


</head>
<body>
    <main>
        <div class="profilepage">
            <!-- NAV BAR -->
            <div class="navbar-fixed">
                <nav>
                    <div class="nav-wrapper">
                        <a href="/php/about.php" class="brand-logo right">
                            <i class="material-icons">info</i>
                        </a>
                        <form id="search-form" method="POST" action="javascript:fireAnim();">
                                <input id="search" name="search" type="search" required>
                                <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                                <i id="search-close" class="material-icons">close</i>
                        </form>
                    
                    </div>
                </nav>
            </div>
            <!-- END OF NAV BAR -->

            <!-- MENU BUTTON -->
            <div class=" fixed-action-btn vertical click-to-toggle ">
                <a id="menu" class="btn-floating btn-large purple waves-effect waves-light tooltipped" data-position="left" data-delay="50" data-tooltip="Menu">
                    <i class="large material-icons">menu</i>
                </a>
                <ul>
                    <li>
                        <a id="toggle-slider" class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="Toggle gallery On/Off">
                            <i class="fa fa-eye-slash"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" onClick="galleryRedirect();" class="btn-floating yellow darken-1 tooltipped" data-position="left" data-delay="50" data-tooltip="Your gallery">
                            <i class="material-icons">perm_media</i>
                        </a>
                    </li>
                    <li>
                        <a href="#modal1" class="btn-floating green tooltipped modal-trigger" data-target="modal1" data-position="left" data-delay="50" data-tooltip="Upload">
                            <i class="material-icons">publish</i>
                        </a>
                    </li>
                    <li>
                        <a href="/php/signout.php" class="btn-floating blue tooltipped" data-position="left" data-delay="50" data-tooltip="Sign Out">
                            <i class="material-icons">settings_power</i>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END OF MENU BUTTON -->
            <!-- GALLERY CONTAINER-->
            <div class="gallery-container">
                <div class="anim"> <div class="dot dot-1"></div> <div class="dot dot-2"></div> <div class="dot dot-3"></div> </div> <svg xmlns="http://www.w3.org/2000/svg" version="1.1"> <defs> <filter id="goo"> <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" /> <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7"/> </filter> </defs> </svg>
                <div class="slider">
                    <ul id="result" class="slides">
                        <li id="result-list">
                            <div class="welcome-msg fade-in"> <h1> <em><strong>Welcome <?php echo $first.' '.$last; ?></strong></em> </h1> <p>This is Your personal page</p> <p>You can search a vacation destination by clicking the purple field above</p> <p>The navigation menu on the side will take you to Your personal gallery</p> <p>From there you can upload photos and help others reach great corners of our world</p> <h1> <strong><em>Enjoy!</em></strong> </h1> </div>
                       </li>
                    </ul>
                </div>
                <!--  END OF SLIDER GALLERY -->
            </div>
            <!-- END OF GALLERY CONTAINER-->

            <!-- Modal Structure -->
            <div id="modal1" class="modal">

                <div class="modal-content">
                    <h4>Add an Image</h4>
                    <div class="row">
                        <form class="col s12" action="#" method="POST" enctype="multipart/form-data">
                            <div class="row modal-form-row">

                                <div class="file-field input-field">
                                    <div class="btn">
                                        <span>Image</span>
                                        <input id="image" name="image" type="file" required="required">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input id="image-txt" name="image-txt" type="text" class="file-path validate">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div id="search-form" class="input-field col s12">
                                    <input id="country" name="country" type="text" class="validate">
                                    <label for="country">Country</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <input id="city" name="city" type="text" class="validate">
                                    <label for="city">City</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea id="caption" name="caption" type="text" class="materialize-textarea validate"></textarea>
                                    <label for="image_description">Description</label>
                                </div>
                            </div>
                            <div id="btn-msg" class="modal-footer">
                                <a name="submit" type="submit" class=" modal-action waves-effect waves-green btn-flat" onClick="return false" onmousedown="javascript:up();">
                                    Upload
                                </a>
                            </div>
                        </form>
                    </div>
                </div>


            </div>
            <!-- END OF MODAL -->

        </div>
        <!-- END OF PROFILE PAGE -->
    </main>
</body>

<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
<script src="/js/awesomplete.js"></script>
<script src="/js/materialize.js"></script>
<script src="/js/main.js"></script>

<script> var ajax = new XMLHttpRequest(); ajax.open("GET", "https://restcountries.eu/rest/v1/", true); ajax.onload = function() { var list = JSON.parse(ajax.responseText).map(function(i) { return i.name;  }); new Awesomplete(document.querySelector("input"),{ list: list  }); }; ajax.send(); </script>

<!-- Loader animation script -->
<script type="text/javascript" charset="utf-8"> function fireAnim(){ $("#result").empty(); $(".anim").fadeIn("slow"); $(".anim").delay(1500).fadeOut("slow"); lookup(); } </script>
<script type="text/javascript" charset="utf-8"> function galleryRedirect(){ setTimeout(function(){ window.location="/php/gallery.php"; }, 100); } </script>

<!-- Slider and tooltip script -->
<script> $(document).ready(function() { $('.tooltipped').tooltip({ delay: 50 }); }); $(document).ready(function() { $('.slider').slider({ height: 548, indicators: false, interval: 60000, }); $('.slider').slider('pause'); }); function fwd() { $('.slider').slider('next'); }; function rwd() { $('.slider').slider('prev'); }; $('#toggle-slider').click(function() { $('.slider').slider({ height: 548, indicators: false, interval: 60000, }).toggle(); $('.slider').slider('pause'); }); </script>

<!-- Modal open script -->
<script> $(document).ready(function() { $('.modal').modal(); }); </script>

<!-- Scrolling script -->
<script> var scrollTimer, lastScrollFireTime = 0; $(window).bind('mousewheel', function(e) { var minScrollTime = 100; var now = new Date().getTime(); function processScroll() { if (e.originalEvent.wheelDelta / 120 > 0) { $('.slider').slider('next'); } else { $('.slider').slider('prev'); } } if (!scrollTimer) { if (now - lastScrollFireTime > (3 * minScrollTime)) { processScroll(); lastScrollFireTime = now; } scrollTimer = setTimeout(function() { scrollTimer = null; lastScrollFireTime = new Date().getTime(); }, minScrollTime); } }); </script>

<!-- CLick the menu button on page load script -->
<script> window.onload = function() { document.getElementById("menu").click(); }; </script>
</html>
