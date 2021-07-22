<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2010 onwards Totara Learning Solutions LTD
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Mark Webster <mark.webster@catalyst-eu.net>
 * @author Brian Barnes <brian.barnes@totaralms.com>
 * @package totara
 * @subpackage theme
 */

if (!empty($PAGE->theme->settings->logo)) {
    $logourl = $PAGE->theme->setting_file_url('logo', 'logo');
} else {
    $logourl = $OUTPUT->pix_url('logo', 'theme');
}

if (!empty($PAGE->theme->settings->favicon)) {
    $faviconurl = $PAGE->theme->setting_file_url('favicon', 'favicon');
} else {
    $faviconurl = $OUTPUT->favicon();
}

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = !empty($custommenu);
$hascoursefooter = (!isset($PAGE->layout_options['nocoursefooter']) || !$PAGE->layout_options['nocoursefooter']);
$hasfooter = (!isset($PAGE->layout_options['nofooter']) || !$PAGE->layout_options['nofooter']);

$haslogininfo = empty($PAGE->layout_options['nologininfo']);
$showmenu = empty($PAGE->layout_options['nocustommenu']);
$haslangmenu = (!isset($PAGE->layout_options['langmenu']) || $PAGE->layout_options['langmenu'] );
$left = (!right_to_left());

if ($showmenu && !$hascustommenu) {
    // load totara menu
    $menudata = totara_build_menu();
    $totara_core_renderer = $PAGE->get_renderer('totara_core');
    $totaramenu = $totara_core_renderer->print_totara_menu($menudata);
}
/**
 * BEGIN Hide Breadcrumbs
 */
global $DB;
$category = $DB->get_record('course_categories',array('id'=>$COURSE->category));
if($category && $category->idnumber == 'GROUP'){
    $displaybreadcrumbs = false;
}else{
    $displaybreadcrumbs = true;
}
/**
 * END Hide Breadcrumbs
 */
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
    <title><?php echo $OUTPUT->page_title(); ?></title>
    <link rel="shortcut icon" href="<?php echo $faviconurl; ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
</head>

<body <?php echo $OUTPUT->body_attributes(); ?>>

<?php echo $OUTPUT->standard_top_of_body_html() ?>

<header role="banner" class="navbar">
    <nav role="navigation" class="navbar-inner">
        <div id="navbar-inside" class="container-fluid">
            <?php if ($logourl == NULL) { ?>
                <div id="logo"><a href="<?php echo $CFG->wwwroot; ?>">&nbsp;</a></div>
            <?php } else { ?>
                <div id="logo" class="custom"><a href="<?php echo $CFG->wwwroot; ?>"><img class="logo" src="<?php echo $logourl;?>" alt="Logo" /></a></div>
            <?php } ?>
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <div class="nav-collapse collapse">
                <ul id="cec-nav" class="nav <?php echo $left ? "pull-right" : "pull-left" ?>">
                    <li><?php echo $OUTPUT->page_heading_menu(); ?></li>
                    <?php if ($haslogininfo) { ?>
                        <li class="navbar-text header-user"><?php echo cec_login_info(); ?></li>
                    <?php } ?>
                    <?php if ( isloggedin() ) { ?>
                        <li class="navbar-text header-logout"><?php echo cec_logout(); ?></li>
                    <?php }else{ ?>
                        <li class="navbar-text header-logout"><?php echo cec_login(); ?></li>
                    <?php } ?>
                </ul>
                <?php if ($showmenu) { ?>
                    <?php if ($hascustommenu) { ?>
                        <div id="custommenu"><?php echo $custommenu; ?></div>
                    <?php } else { ?>
                        <div id="totaramenu"><?php echo $totaramenu; ?></div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </nav>
</header>

<div id="page" class="container-fluid">

    <header id="page-header" class="clearfix">
        <div id="page-navbar" class="clearfix">
            <div class="breadcrumb-nav"><?php if($displaybreadcrumbs){echo $OUTPUT->navbar();} ?></div>
            <nav class="breadcrumb-button"><?php echo $OUTPUT->page_heading_button(); ?></nav>
        </div>
        <div id="course-header">
            <?php echo $OUTPUT->course_header(); ?>
        </div>
    </header>

    <div id="page-content" class="row-fluid">
        <section id="region-main" class="span12">
            <?php
            echo $OUTPUT->course_content_header();
            echo $OUTPUT->main_content();
            if ($hascoursefooter) {
                echo $OUTPUT->course_content_footer();
            }
            ?>
        </section>
    </div>

</div>

<?php if ($hasfooter) { ?>
    <footer id="page-footer">
        <div class="container-fluid">
            <?php if ($hascoursefooter) { ?>
                <div id="course-footer"><?php echo $OUTPUT->course_footer(); ?></div>
            <?php } ?>
            <div class="footer-powered">Powered by <a href="http://www.totaralms.com/" target="_blank">TotaraLMS</a></div>
            <?php
            echo $OUTPUT->standard_footer_html();
            ?>
        </div>
    </footer>
<?php } ?>

<?php echo $OUTPUT->standard_end_of_body_html() ?>

</body>
</html>
