<?php
/*
 * This file is part of Totara LMS
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
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2015 Bas Brands <www.sonsbeekmedia.nl>
 * @author    Bas Brands
 * @author    David Scotson
 * @author    Joby Harding <joby.harding@totaralearning.com>
 * @author    Petr Skoda <petr.skoda@totaralms.com>
 * @author    Murali Nair <murali.nair@totaralearning.com>
 * @package   theme_oaklandcustomresponsive
 */

defined('MOODLE_INTERNAL') || die();

$PAGE->set_popup_notification_allowed(false);

$themerenderer = $PAGE->get_renderer('theme_oaklandcustomresponsive');
$full_header = $themerenderer->full_header();
if (isset($PAGE->layout_options['nonavbar']) && $PAGE->layout_options['nonavbar'] && strpos($full_header, '<input') === false) {
    $full_header = '';
}
echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes(); ?>>
<?php require("{$CFG->dirroot}/theme/oaklandcustomresponsive/layout/partials/head.php"); ?>

<body <?php echo $OUTPUT->body_attributes(); ?>>
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<!-- Main navigation -->
<?php
$totara_core_renderer = $PAGE->get_renderer('totara_core');
/*
// This commented code has been @deprecated since 12.0.
$hastotaramenu = false;
$totaramenu = '';
if (empty($PAGE->layout_options['nocustommenu'])) {
    $menudata = totara_build_menu();
    $totaramenu = $totara_core_renderer->totara_menu($menudata);
    $hastotaramenu = !empty($totaramenu);
}
require("{$CFG->dirroot}/theme/roots/layout/partials/header.php");
*/
$hasguestlangmenu = (!isset($PAGE->layout_options['langmenu']) || $PAGE->layout_options['langmenu'] );
$nocustommenu = !empty($PAGE->layout_options['nocustommenu']);
echo $totara_core_renderer->masthead($hasguestlangmenu, $nocustommenu);
?>

<?php if ($full_header !== '') { ?>
<!-- Breadcrumb and edit buttons -->
<div class="container-fluid breadcrumb-container">
    <div class="row">
        <div class="col-sm-12">
            <?php echo $full_header; ?>
        </div>
    </div>
</div>
<?php } ?>

<?php
    if ($_GET["allow"]) { ?>
    <style type="text/css">
        #page-login-index #region-main > .row {
            display: none;
        }
        .top-bar {
            display: none;
        }
        #page-login-index #page + .row {
            display: none;
        }
        .loginbox {
            display: block;
        }
    </style>
<?php } ?>

<div class="top-bar">
    <div class="col-sm-12" style="background-color: green; text-align: center; color: white; padding: 10px; font-weight: 700;">Michigan Professional Learning &amp; Collaboration Environment</div>
</div>

<!-- Content -->
<div id="page" class="container-fluid">
    <div id="page-content">

        <?php echo $themerenderer->blocks_top(); ?>
        <div class="row">
            <div id="region-main" class="<?php echo $themerenderer->main_content_classes(); ?>">
                <?php echo $themerenderer->course_content_header(); ?>
                <?php echo $themerenderer->main_content(); ?>
                <?php
                    echo '<div class="row">';
                        echo '<div class="col-md-6">';
                            echo '<div class="login-container login-container-left" style="position: relative; overflow: hidden; height: 600px; ">';
                                echo '<a href="#">';
                                    echo '<img height="400" width="700" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub.png" title="The Hub. Learning for Educators" alt="The Hub. Engaged. Connected. Self-Directed. Learning for Educators" class="et-waypoint et_pb_image et_pb_animation_off hubimage">';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-1" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-1.png"></div>';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-2" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-2.png"></div>';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-3" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-3.png"></div>';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-4" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-4.png"></div>';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-5" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-5.png"></div>';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-6" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-6.png"></div>';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-7" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-7.png"></div>';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-8" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-8.png"></div>';
                                    echo '<div class="absolute et_pb_column_1_2 hubimage-9" style="display: block;"><img aria-hidden="true" src="https://cdn.oakland.k12.mi.us/img/static/miplace/miplace-logo-hub-9.png"></div>';
                                    echo '</a>';
                                echo '<div>';
                                    echo '<h4 style="margin-bottom:0px; text-align: center;">THE</h4>';
                                    echo '<h1 style="margin-top:5px; text-align: center;">HUB</h1>';
                                    echo '<h5 style="text-align: center;">Engaged. Connected. Self-Directed.</h5>';
                                    echo '<h4 style="text-align: center;">Learning for Educators</h4>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        echo '<div class="col-md-6">';
                            echo '<div class="login-container login-container-right">';
                                echo '<a href="#">';
                                    echo '<img height="400" width="700" src="../theme/oaklandcustomresponsive/pix/logo_right.png">';
                                echo '</a>';
                                echo '<div>';
                                    echo '<h1 aria-hidden="true" style="margin-top:33px; text-align: center;">GROUPS</h1>';
                                    echo '<h5 aria-hidden="true" style="text-align: center;">Dialog. Discover. Design.</h5>';
                                    echo '<h4 aria-hidden="true" style="text-align: center;">Connecting for Growth</h4>';
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                ?>

                <?php echo $themerenderer->blocks_main(); ?>
                <?php echo $themerenderer->course_content_footer(); ?>
            </div>
            <?php echo $themerenderer->blocks_pre(); ?>
            <?php echo $themerenderer->blocks_post(); ?>
        </div>
        <?php echo $themerenderer->blocks_bottom(); ?>

    </div>
</div>

<div class="row" style="margin-right:0px;background-color: black; padding-top:5px">
    <div class="col-sm-5">
        <a title="Alternate Login" href="https://www.miplacek12.org/login/index.php?allow=1"><i class="fa fa-archive" style="margin-left: 30px; color: grey;padding-top:2px;"></i></a>
    </div>
    <div class="col-sm-5" style="color: lightgrey; text-align: left;">
                Copyright © 2021 Oakland Schools
    </div>
    <div class="col-sm-2" style="height:26px; text-align:right">
        <a title="Twitter" href="https://twitter.com/OaklandSchools"><i class="fa fa-twitter" style="color: grey; font-size: 20px; margin-top: 2px;"></i></a>
        <a title="Facebook" href="https://www.facebook.com/OSMichigan/"><i class="fa fa-facebook" style="color: grey; font-size: 20px; margin-top:2px;"></i></a>

    </div>
</div>

<script type="text/javascript">
    $(".potentialidp").appendTo($(".totaraNav_prim--side"));
</script>

<!-- Footer -->
<?php require("{$CFG->dirroot}/theme/oaklandcustomresponsive/layout/partials/footer.php"); ?>

<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
