<?php
require_once('config.php');
global $OUTPUT;
# require_once($CFG->dirroot . '/auth/googleoauth2/lib.php');
require_once($CFG->dirroot . '/auth/oauth2/lib.php');

$PAGE->set_context(context_system::instance());
$PAGE->set_title('Oakland Login');

if (!empty($PAGE->theme->settings->favicon)) {
    $faviconurl = $PAGE->theme->setting_file_url('favicon', 'favicon');
} else {
    $faviconurl = $OUTPUT->favicon();
}

if (!empty($PAGE->theme->settings->logo)) {
    $logourl = $PAGE->theme->setting_file_url('logo', 'logo');
} else {
    $logourl = $OUTPUT->pix_url('logo', 'theme');
}

if(!empty($PAGE->theme->settings->hubdashboard)){
    $hubdashboard = '?id='.$PAGE->theme->settings->hubdashboard;
}else{
    $hubdashboard = '';
}
if(!empty($PAGE->theme->settings->collabdashboard)){
    $collabdashboard = '?id='.$PAGE->theme->settings->collabdashboard;
}else{
    $collabdashboard = '';
}

if(!empty($PAGE->theme->settings->contacttext)){
    $contacttext = $PAGE->theme->settings->contacttext;
}else{
    $contacttext = '';
}

if(!empty($PAGE->theme->settings->abouttext)){
    $abouttext = $PAGE->theme->settings->abouttext;
}else{
    $abouttext = '';
}

if(!empty($PAGE->theme->settings->firsttime)){
    $firsttime = $PAGE->theme->settings->firsttime;
}else{
    $firsttime = '';
}

if(!empty($PAGE->theme->settings->copyright)){
    $copyright = $PAGE->theme->settings->copyright;
}else{
    $copyright = '';
}

$code = optional_param('code',null,PARAM_RAW);
$provider = optional_param('authprovider',null,PARAM_RAW);
if(isset($code) && isset($provider)){
    $loginurl = new moodle_url('/login/index.php',array('code'=>$code,'authprovider'=>$provider,'allow'=>1));
    redirect($loginurl);
}

echo $OUTPUT->doctype();
$loginurl = new moodle_url('/login/index.php?',array('allow'=>1));
//$header = $OUTPUT->standard_head_html();
?>
<script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>
<script type='text/javascript' src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://servicedesk.oakland.k12.mi.us/s/195b89dca634259b8138d9b17dddb084-T/en_US4a27nl/71008/8dabca95fc8615d5c0923ec0c5f12496/2.0.11/_/download/batch/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector/com.atlassian.jira.collector.plugin.jira-issue-collector-plugin:issuecollector.js?locale=en-US&collectorId=ed957ce9"></script>
<script type="text/javascript">

    (function($){
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        var animateCollab = function() {
            var collab = $('.collabimage');

            collab.addClass('rotate');
        };

        var animateHub = function() {
            var hub = $('.hubimage'),
                div = $('<div />', {
                    class: 'absolute et_pb_column_1_2'
                }),
                img = $('<img aria-hidden="true" />'),
                path = hub.attr('src').split('miplace-logo-hub'),
                animateHubQueue = [],
                animateHubIndex = 0;

            for (var i = 9; i > 0; i-- ) {
                var imagePath = path[0] + 'miplace-logo-hub-' + i + path[1],
                    image = img.clone().attr('src', imagePath),
                    imageContainer = div.clone();

                imageContainer.addClass('hubimage-' + i).append(image).insertAfter(hub);
                console.log("hello");
                imageContainer.hide();
                animateHubQueue.push(imageContainer);
            }

            setInterval( function() {
                animateHubQueue[animateHubIndex++ % animateHubQueue.length].fadeIn('slow');
            }, 200);

        };

        window.setTimeout(animateHub, 300);
        window.setTimeout(animateCollab, 700);
        window.ATL_JQ_PAGE_PROPS =  {
            "triggerFunction": function(showCollectorDialog) {
                //Requires that jQuery is available! 
                $("#getHelpLink").click(function(e) {
                    e.preventDefault();
                    showCollectorDialog();
                });
            }
        };

    })(jQuery);

</script>
<style type="text/css" id="et-custom-css">
    body {
        font-family: 'Roboto', sans-serif !important;
    }
    .singinprovider:first-child{
        width: 223px;
        margin-right:20px;
    }
    .row {
      margin-right: 0px !important;
    }
    #loginlist li{
        display: inline;
        list-style-type: none;
        padding-right: 20px;
    }
    .loginlogo {
        float: left
    }

    .collabimage{
        -webkit-transition:2s;
        -moz-transition:2s;
        -o-transition:2s;
        transition:2s;
    }

    .hubimage {
        -webkit-transition:1s;
        -moz-transition:1s;
        -o-transition:1s;
        transition:1s;
    }

    .hubimage-1 {
        margin-left: 2px;
    }

    .hubimage-2 {
        margin-left: 1px;
    }

    .hubimage-4 {
        margin-left: 4px
    }

    .hubimage-5 {
        margin-top: 2px;
    }

    .hubimage-6 {
        margin-left: -1px;
        margin-top: 3px;
    }

    .hubimage-7 {
        margin-left: 2px;
        margin-top: 1px;
    }

    .hubimage-8 {
        margin-top: 3px;
        margin-left: 1px;
    }

    .hubimage-9 {
        margin-left: -1px;
        margin-top: 4px;
    }

    .rotate{
        -webkit-transform:rotate(1turn);
        -moz-transform:rotate(1turn);
        -ms-transform:rotate(1turn);
        -o-transform:rotate(1turn);
        transform:rotate(1turn);
    }

    .absolute {
        position: absolute;
        top: -55px;
    }
    .nav>li>a {
        line-height: 28px !important;
    }
    .signin {
      display: block;
      background: url(https://cdn.oakland.k12.mi.us/img/static/miplace/btn_google_signin_dark_normal_web@2x.png) no-repeat bottom;
      background-size: cover;
      height: 40px;
      width: 166.078px;
      margin-right: inherit !important;
      border: 0px;
    }
    .signin:focus {
      outline: 0;
    }
    .signin:active {
      outline: 0;
      background: url(https://cdn.oakland.k12.mi.us/img/static/miplace/btn_google_signin_dark_pressed_web@2x.png) no-repeat bottom;
      background-size: cover;
    }
    .navbar {
      display: inline-block;
      float: right;
    }
    body::after{
        position:absolute; width:0; height:0; overflow:hidden; z-index:-1;
        content:url(https://cdn.oakland.k12.mi.us/img/static/miplace/btn_google_signin_dark_pressed_web@2x.png) url(https://cdn.oakland.k12.mi.us/img/static/miplace/btn_google_signin_dark_normal_web@2x.png);
    }
</style>
<html>
<!--<div id="loginlogo"><a href="--><?php //echo $CFG->wwwroot; ?><!--"><img class="loginlogo" src="--><?php //echo $logourl;?><!--" alt="Logo" /></a></div>-->
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $faviconurl; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
<!-- Piwik -->
    <script type="text/javascript">
      var _paq = _paq || [];
      /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="https://piwik.oakland.k12.mi.us/";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', '8']);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
<!-- End Piwik Code -->
</head>
<body>
<div class="row">
    <div class="col-sm-4">
        <img src="<?php echo $logourl;?>" title="Logo for miPlace Michigan Professional Learning and Collaboration Environment" alt="Logo for miPlace Michigan Professional Learning and Collaboration Environment" />
    </div>
    <div class="col-sm-8">
<nav class="navbar navbar-default" style="margin-top:15px;margin-left:auto;max-width:663px";>
  <div class="container-fluid">
    <ul class="nav navbar-nav">
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">First Time Here? <span class="caret"></span></a>
      <ul class="dropdown-menu" style="width:230px;padding:10px;text-align:center;">
        <?php echo str_replace("left","right",$firsttime);?>
      </ul>
    </li>
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">About miPlace <span class="caret"></span></a>
      <ul class="dropdown-menu" style="width:350px;padding:10px;text-align:center;">
        <?php echo $abouttext;?>
      </ul>
    </li>
    <li><a href="#" id="getHelpLink">Need Help?</a></li>
    <!-- <a class="signin navbar-btn navbar-right" href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo get_config('auth/googleoauth2', 'googleclientid')?>&redirect_uri=<?php echo new moodle_url('/auth/googleoauth2/google_redirect.php')?>&scope=https://www.googleapis.com/auth/userinfo.profile%20https://www.googleapis.com/auth/userinfo.email&response_type=code"></a> -->
    <a class="signin navbar-btn navbar-right" href="https://accounts.google.com/o/oauth2/auth?client_id=<?php echo get_config('auth/oauth2', 'googleclientid')?>&redirect_uri=<?php echo new moodle_url('/auth/oauth2/login.php')?>&scope=https://www.googleapis.com/auth/userinfo.profile%20https://www.googleapis.com/auth/userinfo.email&response_type=code"></a>
  </div>
</nav>
    </div>
</div>
<div class="row">
    <div class="col-sm-12" style="background-color: green; text-align: center; color: white; padding: 10px;">
        Michigan Professional Learning & Collaboration Environment
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div style="position: relative; overflow: hidden; height: 600px; ">
            <a href="<?php echo $CFG->wwwroot.'/totara/dashboard/index.php'.$hubdashboard; ?>"><img height="400" width="700" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub.png" title="The Hub. Learning for Educators" alt="The Hub. Engaged. Connected. Self-Directed. Learning for Educators" class="et-waypoint et_pb_image et_pb_animation_off hubimage" /></a>
            <div style="margin-left: 5%;">
                <h4 aria-hidden="true" style="margin-bottom:0px; text-align: center;">THE</h4>
                <h1 aria-hidden="true" style="margin-top:5px; text-align: center;">HUB</h1>
                <h5 aria-hidden="true" style="text-align: center;">Engaged. Connected. Self-Directed.</h5>
                <h4 aria-hidden="true" style="text-align: center;">Learning for Educators</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6" style="text-align:center;">
        <a href="<?php echo $CFG->wwwroot.'/totara/dashboard/index.php'.$collabdashboard; ?>"><img height="400" width="700" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-collab1.png" title="The Collaboratorium. Connecting for Growth" alt="The Collaboratorium. Dialog. Discover. Design. Connecting for Growth" class="et-waypoint et_pb_image et_pb_animation_off collabimage" /></a>
        <div style="margin-left: 0%;">
            <h1 aria-hidden="true" style="margin-top:33px; text-align: center;">GROUPS</h1>
            <h5 aria-hidden="true" style="text-align: center;">Dialog. Discover. Design.</h5>
            <h4 aria-hidden="true" style="text-align: center;">Connecting for Growth</h4>
        </div>
    </div>
</div>
<div class="row"  style="margin-right:0px;background-color: black; padding-top:5px">
    <div class="col-sm-5">
        <a title="Alternate Login" href="<?php echo $loginurl; ?>"><i class="fa fa-archive" style="margin-left: 30px; color: grey;padding-top:2px;"></i></a>
    </div>
    <div class="col-sm-5" style="color: lightgrey; text-align: left;">
                Copyright © <?php echo date("Y"); ?> Oakland Schools
    </div>
    <div class="col-sm-2" style="height:26px; text-align:right">
        <a title="Twitter" href="https://twitter.com/OaklandSchools"><i class="fa fa-twitter" style="color: grey; font-size: 20px; margin-top: 2px;"></i></a>
        <a title="Facebook" href="https://www.facebook.com/OSMichigan/"><i class="fa fa-facebook" style="color: grey; font-size: 20px; margin-top:2px;"></i></a>

    </div>
</div>

</body>
</html>
