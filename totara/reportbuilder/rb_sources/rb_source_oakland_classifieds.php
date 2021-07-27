<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/7/15
 * Time: 3:09 PM
 */

defined('MOODLE_INTERNAL') || die();

class rb_source_oakland_classifieds extends rb_base_source {
    public $base, $joinlist, $columnoptions, $filteroptions;
    public $contentoptions, $paramoptions, $defaultcolumns;
    public $defaultfilters, $requiredcolumns, $sourcetitle;

    function __construct() {
        $this->base = self::get_base_sql();
        $this->joinlist = $this->define_joinlist();
        $this->columnoptions = $this->define_columnoptions();
        $this->filteroptions = $this->define_filteroptions();
        $this->contentoptions = $this->define_contentoptions();
        $this->paramoptions = $this->define_paramoptions();
        $this->defaultcolumns = $this->define_defaultcolumns();
        $this->defaultfilters = $this->define_defaultfilters();
        $this->requiredcolumns = $this->define_requiredcolumns();
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_oakland_classifieds');

        parent::__construct();
    }

    /**
     * Get base sql for learning communities view
     */
    public static function get_base_sql() {
        return "(".
            "SELECT
                mf.id,
                mf.name                   AS forum_name,
                mu.firstname || ' ' || mu.lastname as forum_post_username,
                mfp.created               AS forum_post_created,
                mfp.message               AS forum_post_message
            FROM
                mdl_forum mf
            INNER JOIN
                mdl_forum_discussions mfd
            ON
                mf.id = mfd.forum
            INNER JOIN
                mdl_forum_posts mfp
            ON
                mfd.id = mfp.discussion
            INNER JOIN
                mdl_user mu
            ON
                mfp.userid = mu.id"
        .")";
    }

    //
    //
    // Methods for defining contents of source
    //
    //

    protected function define_joinlist() {
        $joinlist = array(

        );
        return $joinlist;
    }

    protected function define_columnoptions() {
        $columnoptions = array(
            new rb_column_option(
                'base',
                'forum_name',
                get_string('forum_name', 'rb_source_oakland_classifieds'),
                "base.forum_name"
            ),
            new rb_column_option(
                'base',
                'forum_post_username',
                get_string('forum_post_username', 'rb_source_oakland_classifieds'),
                "base.forum_post_username"
            ),
            new rb_column_option(
                'base',
                'forum_post_created',
                get_string('forum_post_created', 'rb_source_oakland_classifieds'),
                "base.forum_post_created",
                array(
                    'displayfunc'=>'nice_date'
                )
            ),
            new rb_column_option(
                'base',
                'forum_post_message',
                get_string('forum_post_message', 'rb_source_oakland_classifieds'),
                "base.forum_post_message"
            )
        );
        return $columnoptions;
    }

    protected function define_filteroptions() {
        $filteroptions = array(
            new rb_filter_option(
                'base',
                'forum_post_username',
                get_string('forum_post_username', 'rb_source_oakland_classifieds'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'forum_name',
                get_string('forum_name', 'rb_source_oakland_classifieds'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'forum_post_created',
                get_string('forum_post_created', 'rb_source_oakland_classifieds'),
                'date'
            ),
            new rb_filter_option(
                'base',
                'forum_post_message',
                get_string('forum_post_message', 'rb_source_oakland_classifieds'),
                'text'
            )
        );
        return $filteroptions;
    }

    protected function define_paramoptions() {
        $paramoptions = array();
        return $paramoptions;
    }

    protected function define_defaultcolumns() {
        $defaultcolumns = array(
            array(
                'type' => 'base',
                'value' => 'forum_name',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_post_username',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_post_created',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_post_message',
            )
        );
        return $defaultcolumns;
    }

    protected function define_defaultfilters() {
        $defaultfilters = array(

        );
        return $defaultfilters;
    }

    //
    //
    // Source specific column display methods
    //
    //

    //
    //
    // Source specific filter display methods
    //
    //

} // End of rb_source_oakland_classifieds class.
