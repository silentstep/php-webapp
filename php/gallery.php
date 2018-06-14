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
require_once(dirname(__FILE__).'/gallery_script.php');
?>
        <meta charset="UTF-8">
        <title><?php echo $_SESSION['first']; ?>'s gallery</title>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="/css/materialize.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.1.1/jquery-confirm.min.css">

        <link rel="stylesheet" href="/css/secondary.css">

</head>

<body>
<div class="anim" style="display: block;">
  <div class="dot dot-1"></div>
  <div class="dot dot-2"></div>
  <div class="dot dot-3"></div>
</div>

<svg xmlns="http://www.w3.org/2000/svg" version="1.1">
  <defs>
    <filter id="goo">
      <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
      <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 21 -7"/>
    </filter>
  </defs>
</svg>

    <main>
        <div class="profilepage">
            <!-- Nav Structure -->
            <div class="navbar">
                <nav>
                    <div class="nav-wrapper">
                        <a href="/php/about.php" class="brand-logo right">
                            <i class="material-icons">info</i>
                        </a>
                    </div>
                </nav>
            </div>
            <!-- Menu Structure -->
            <div class=" fixed-action-btn vertical click-to-toggle ">
                <a id="menu" class="btn-floating btn-large green tooltipped" data-position="left" data-delay="50" data-tooltip="Menu">
                    <i class="large material-icons">menu</i>
                </a>
                <ul>
                    <li>
                        <a href="/php/profile.php" class="btn-floating red tooltipped" data-position="left" data-delay="50" data-tooltip="Profile view">
                            <i class="material-icons">dashboard</i>
                        </a>
                    </li>
                    <li>
                        <a href="/php/gallery.php" class="btn-floating yellow darken-1 tooltipped" data-position="left" data-delay="50" data-tooltip="Your gallery">
                            <i class="material-icons">perm_media</i>
                        </a>
                    </li>
                    <li>
                        <a href="/php/signout.php" class="btn-floating blue tooltipped" data-position="left" data-delay="50" data-tooltip="Sign Out">
                            <i class="material-icons">settings_power</i>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- Gallery Structure -->
            <div class="gallery-container fade-in">
                <div class="row">
                    <div class="col s12 m6">
                        <ul class="slides">
                            <?php content(); ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- End of Gallery Structure -->
        </div>
        <!-- END OF PROFILE PAGE -->
    </main>
</body>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src='http://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.1.1/jquery-confirm.min.js"></script>

<script src="/js/materialize.js"></script>
<script src="/js/main.js"></script>

<script type="text/javascript" charset="utf-8">
    function edit_cap(){
        var cap = $('.materialboxed.responsive-img.initialized.active').attr('alt');
        var src = $('.materialboxed.responsive-img.initialized.active').attr('id');
        var fields = src.split('=');
        var src = fields[0];
        //var fields_dot = fields[3];
        //var fields_nodot = fields_dot.split('.');
        //var cap = fields_nodot[0];
        console.log("caption of image: " + cap);
        console.log("image id is:" + src);
        $.confirm({
            title:'Edit caption',
            content: '' +
                '<form action="" class="formName">' +
                    '<div class="form-group">' +
                    '<textarea id="caption" name="caption" type="text" class="materialize-textarea validate">'+cap+'</textarea>'+
                    '</div>' +
                '</form>',
            useBootstrap: false,
            buttons: {
                formSubmit: {
                    text: 'Submit changes',
                    action: function(){
                        //AJAX HERE
                        var desc = this.$content.find('.materialize-textarea').val();
                        $.ajax({
                            type: "POST",
                            url: "/php/edit_script.php",
                            data: "desc=" + desc + "&src=" + src,
                            beforeSend: function(){
                                console.log("sending data...");
                            },
                            complete: function(){
                                console.log("data sent!");
                            },
                            success: function(response){
                                if(response.success){
                                    pgRld();
                                } else {
                                    $.alert({
                                        title: 'Error!',
                                        content: 'Could not change the caption!',
                                    });
                                }
                            }
                        });
                        
                    }
                },
                cancel: function(){
                    //close
                },
            }
        });
    }
</script>

<script type="text/javascript" charset="utf-8">
    function img_src(){
        var src = $('.materialboxed.responsive-img.initialized.active').attr('id');
        var fields = src.split('=');
        var src = fields[1];
        var id = fields[0];
        console.log("source of image: " + src);
        console.log("id of image: " + id);
        $.confirm({
            title: 'Confirm!',
            content: 'Are you sure you want to delete this image?',
            useBootstrap: false,
            buttons: {
                OK: function () {
                    $.ajax({
                        type: "POST",
                        url: "/php/delete_script.php",
                        data: "src=" + src + "&id=" + id,
                        beforeSend: function(){
                            console.log("sending data...");
                        },
                        complete: function(){
                            console.log("data sent!");
                        },
                        success: function(response){
                            if(response.success){
                                pgRld();
                            } else {
                                $.alert({
                                    title: 'Alert!',
                                    content: 'Could not delete!',
                                });
                            }
                        } 
                    });
                },
                Cancel: function () {
                    //close the modal
                },
            }
        });
    }
</script>

<script type="text/javascript" charset="utf-8">
    function pgRld(){ 
        window.location.reload();
    }
</script>

<script type="text/javascript" charset="utf-8">

$(window).load(function() {
    // Animate loader off screen
    $(".anim").fadeOut("slow");
});

</script>

<!-- Initialize materialbox -->
<script>
    $(document).ready(function() {
        $('.materialboxed').materialbox();
    });
</script>

<!-- Modal open script -->
<script>
    $(document).ready(function() {
        // the "href" attribute of .modal-trigger must specify the modal ID that wants to be triggered
        $('.modal').modal();
    });
</script>

<!-- CLick the menu button on page load script -->
<script>
    window.onload = function() {
        document.getElementById("menu").click();
    };
</script>
</html>
