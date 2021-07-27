<?php
/**
 * Created by IntelliJ IDEA.
 * User: jcweinberg
 * Date: 5/7/15
 * Time: 3:09 PM
 */

defined('MOODLE_INTERNAL') || die();

class rb_source_oakland_forums extends rb_base_source {
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
        $this->sourcetitle = get_string('sourcetitle', 'rb_source_oakland_forums');

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
                mfd.name                  AS forum_discussion_name,
                mfd.viewcount             AS forum_discussion_views_per_post,
                mu.firstname || ' ' || mu.lastname as forum_post_username,
                mfp.subject               AS forum_post_topics,
                mfp.created               AS forum_post_created,
                mfp.resourcetype          AS forum_post_type,
                post_replies.count        AS forum_replies,
                mog.name                  AS group_name
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
                mfp.userid = mu.id
            LEFT JOIN
                mdl_oakland_groups mog
            ON
                mfp.oaklandgroupid = mog.id
            LEFT JOIN
                    (select parent, count(*) from mdl_forum_posts group by parent having parent != 0) post_replies
            ON
                mfp.id = post_replies.parent"
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
                get_string('forum_name', 'rb_source_oakland_forums'),
                "base.forum_name"
            ),
            new rb_column_option(
                'base',
                'forum_discussion_name',
                get_string('forum_discussion_name', 'rb_source_oakland_forums'),
                "base.forum_discussion_name"
            ),
            new rb_column_option(
                'base',
                'forum_discussion_views_per_post',
                get_string('forum_discussion_views_per_post', 'rb_source_oakland_forums'),
                "base.forum_discussion_views_per_post"
            ),
            new rb_column_option(
                'base',
                'forum_post_username',
                get_string('forum_post_username', 'rb_source_oakland_forums'),
                "base.forum_post_username"
            ),
            new rb_column_option(
                'base',
                'forum_post_topics',
                get_string('forum_post_topics', 'rb_source_oakland_forums'),
                "base.forum_post_topics"
            ),
            new rb_column_option(
                'base',
                'forum_post_created',
                get_string('forum_post_created', 'rb_source_oakland_forums'),
                "base.forum_post_created",
                array(
                    'displayfunc'=>'nice_date'
                )
            ),
            new rb_column_option(
                'base',
                'forum_post_type',
                get_string('forum_post_type', 'rb_source_oakland_forums'),
                "base.forum_post_type"
            ),
            new rb_column_option(
                'base',
                'forum_replies',
                get_string('forum_replies', 'rb_source_oakland_forums'),
                "base.forum_replies"
            ),
            new rb_column_option(
                'base',
                'group_name',
                get_string('group_name', 'rb_source_oakland_forums'),
                "base.group_name"
            )
        );
        return $columnoptions;
    }

    protected function define_filteroptions() {
        $filteroptions = array(
            new rb_filter_option(
                'base',
                'forum_post_username',
                get_string('forum_post_username', 'rb_source_oakland_forums'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'forum_name',
                get_string('forum_name', 'rb_source_oakland_forums'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'forum_post_created',
                get_string('forum_post_created', 'rb_source_oakland_forums'),
                'date'
            ),
            new rb_filter_option(
                'base',
                'forum_post_type',
                get_string('forum_post_type', 'rb_source_oakland_forums'),
                'text'
            ),
            new rb_filter_option(
                'base',
                'group_name',
                get_string('group_name', 'rb_source_oakland_forums'),
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
                'value' => 'forum_discussion_name',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_discussion_views_per_post',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_post_username',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_post_topics',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_post_created',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_post_type',
            ),
            array(
                'type' => 'base',
                'value' => 'forum_replies',
            ),
            array(
                'type' => 'base',
                'value' => 'group_name',
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

} // End of rb_source_oakland_forums class.
