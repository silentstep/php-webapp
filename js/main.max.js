$(function() {
    $('.homepage').hide();
    $('.box').delay(500).effect("bounce", { times: 4 }, 1000);
    $('.box').hide().slideDown(500);


    $('span').click(function() {
        $('.intro').slideUp(400);
        $('.box').slideUp(200);
        $('.homepage').delay(600).slideDown(300);
    });

});

$('.btn-register').click(function() {
    $('.connect').addClass('remove-section');
    $('.register').removeClass('active-section');
    $('.btn-register').removeClass('active');
    $('.btn-connect').addClass('active');
});

$('.btn-connect').click(function() {
    $('.connect').removeClass('remove-section');
    $('.register').addClass('active-section');
    $('.btn-register').addClass('active');
    $('.btn-connect').removeClass('active');
});


// AJAX REQUESTS FOR PHP CODE //

//===============//
// Login handler //
//===============//

function login() {
    // Get input variables
    var user = $("#uid").val()
    var pass = $("#pwd").val()
    var hash = "<?php echo $_SESSION['hash']; ?>"
    console.log(hash);
    //Check if inputs are empty.
    if ((user == "") || (pass == "")) {
        var $newspan = $("<span id='msg' class='js-fade fade-in-out'></span>");
        if ($("#msg").length) {
            $("span").remove();
            $("#content").append($newspan);
            $("#msg").html("All fields required");
        } else {
            $("#content").append($newspan);
            $("#msg").html("All fields required");
        }
    } else { // Fire up AJAX request to redirect if register successful or pop up a msg if not.
        $.ajax({
            type: "POST",
            url: "/php/signin_script.php",
            data: "uid=" + user + "&pwd=" + pass,
            success: function(html) {
                console.log(html == 'Username does not exist!' || html == 'Wrong password! Try again.');
                if ((html == "Username does not exist!") || (html == "Wrong password! Try again.")) {
                    //swapContent('welcome');
                    //swapContainer('signed');
                    var $newspan = $("<span id='msg' class='js-fade fade-in-out'></span>");
                    if ($("#msg").length) {
                        $("span").remove();
                        $("#content").append($newspan);
                        $("#msg").html(html);
                    } else {
                        $("#content").append($newspan);
                        $("#msg").html(html);
                    }
                } else {
                    window.location = "/php/profile.php";
                    console.log('part2');
                }
            }
        });
    }
    return false;
}

//==================//
// Register handler //
//==================//

function reg() {
    // Get input variables
    var u = $("#reg-uid").val()
    var p = $("#reg-pwd").val()
    var first = $("#first").val()
    var last = $("#last").val()
    var hash = "<?php echo $_SESSION['hash']; ?>"
    console.log(hash);
    //Check if inputs are empty.
    if ((u == "") || (p == "") || (first == "") || (last == "")) {
        var $newspan = $("<span id='msg' class='js-fade fade-in-out'></span>");
        if ($("#msg").length) {
            $("span").remove();
            $("#reg-content").append($newspan);
            $("#msg").html("All fields required");
        } else {
            $("#reg-content").append($newspan);
            $("#msg").html("All fields required");
        }
    } else { // Fire up AJAX request to redirect if register successful or pop up a msg if not.
        $.ajax({
            type: "POST",
            url: "/php/register_script.php",
            data: "reg-uid=" + u + "&reg-pwd=" + p + "&first=" + first + "&last=" + last,
            success: function(html) {
                console.log(html);
                if ((html == "First name only in alphabets!")) {
                    var $newspan = $("<span id='msg' class='js-fade fade-in-out'></span>");
                    if ($("#msg").length) {
                        $("span").remove();
                        $("#reg-content").append($newspan);
                        $("#msg").html(html);
                    } else {
                        $("#reg-content").append($newspan);
                        $("#msg").html(html);
                    }
                } else if ((html == "Last name only in alphabets!")) {
                    var $newspan = $("<span id='msg' class='js-fade fade-in-out'></span>");
                    if ($("#msg").length) {
                        $("span").remove();
                        $("#reg-content").append($newspan);
                        $("#msg").html(html);
                    } else {
                        $("#reg-content").append($newspan);
                        $("#msg").html(html);
                    }
                } else if ((html == "Password too short!")) {
                    var $newspan = $("<span id='msg' class='js-fade fade-in-out'></span>");
                    if ($("#msg").length) {
                        $("span").remove();
                        $("#reg-content").append($newspan);
                        $("#msg").html(html);
                    } else {
                        $("#reg-content").append($newspan);
                        $("#msg").html(html);
                    }
                } else if ((html == "User already exists!")) {
                    var $newspan = $("<span id='msg' class='js-fade fade-in-out'></span>");
                    if ($("#msg").length) {
                        $("span").remove();
                        $("#reg-content").append($newspan);
                        $("#msg").html(html);
                    } else {
                        $("#reg-content").append($newspan);
                        $("#msg").html(html);
                    }
                } else {
                    window.location = "/php/profile.php";
                    console.log('part2');
                }
            }
        });
    }
    return false;
}

//================//
// Upload handler //
//================//

function up() {
    var file_data = $("#image").prop("files")[0];
    console.log(file_data)

    var form_data = new FormData();
    form_data.append("image", file_data);

    var country = $('#country').serializeArray();
    $.each(country, function(key, input) {
        form_data.append(input.name, input.value);
    });
    var city = $('#city').serializeArray();
    $.each(city, function(key, input) {
        form_data.append(input.name, input.value);
    });
    var caption = $('#caption').serializeArray();
    $.each(caption, function(key, input) {
        form_data.append(input.name, input.value);
    });
    console.log(country);
    console.log(city);
    console.log(caption);
    $.ajax({
        type: "POST",
        url: "/php/upload_script.php",
        data: form_data,
        contentType: false,
        cache: false,
        processData: false,
        success: function(html) {
            console.log("executed script");
            var $newspan = $("<span id='msg' class='upload-msg'></span>");
            if ($("#msg").length) {
                $("#msg").remove();
                $("#btn-msg").append($newspan);
                $("#msg").html(html);
                if ((html == "Success!")) {
                    $("#country").replaceWith($("#country").val('').clone(true));
                    $("#city").replaceWith($("#city").val('').clone(true));
                    $("#caption").replaceWith($("#caption").val('').clone(true));
                }
                setTimeout(function() {
                    $('#msg').remove();
                }, 5000);
            } else {
                $("#btn-msg").append($newspan);
                $("#msg").html(html);
                if ((html == "Success!")) {
                    $("#country").replaceWith($("#country").val('').clone(true));
                    $("#city").replaceWith($("#city").val('').clone(true));
                    $("#caption").replaceWith($("#caption").val('').clone(true));
                }
                setTimeout(function() {
                    $('#msg').remove();
                }, 5000);
            }
        }
    });
}

//================//
// Search handler //
//================//

function lookup() {
    console.log("Lookup function fired");
    var search = $("#search").val();
    $("input").replaceWith($("input").val(''));
    $("#search").blur();
    if ((search == "")) {
        console.log("entered blank check");
        return false;
    } else {
        $.ajax({
            type: "POST",
            url: "/php/search_script.php",
            data: "search=" + search,
            success: function(html) {
                console.log("html fetched!");
                setTimeout(function() {
                    $("#result").empty();
                    console.log("emptied #result ul");
                }, 500);
                setTimeout(function() {
                    $("#result").append(html);
                    console.log("appended html");
                }, 2000);
                setTimeout(function() {
                    $('.slider').slider({
                        height: 548,
                        indicators: false,
                        interval: 60000,
                    });
                    $('.slider').slider('pause');
                    console.log("activated slider");
                }, 2100);
//                    $("#result").empty();
//                    $("#result").append(html);
//                    $('.slider').slider({
//                        height: 548,
//                        indicators: false,
//                        interval: 60000,
//                    });
//                    $('.slider').slider('pause');
            }
        });
    }
}
