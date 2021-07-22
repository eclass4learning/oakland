<?php
/**
 * Created by IntelliJ IDEA.
 * User: gregpankau
 * Date: 6/4/15
 * Time: 10:30 AM
 * To change this template use File | Settings | File Templates.
 */

class block_forum_renderer extends plugin_renderer_base
{

    public function forum_list($discussions, $forum, $course, $forum_to_display)
    {
        global $DB;
        $html = "<div>".$forum->intro."</div>";
        if ($forum_to_display == 'Group') {
            $html .="<table style='width:100%' class='forumheaderlist'><tr><th>Discussion</th><th>Started By</th><th>Replies</th><th>Last Post</th></tr>";
        } else {
            $html .="<table style='width:100%' class='forumheaderlist'><tr><th>Topic</th><th>Started By</th><th>Replies</th><th>Views</th><th>Resource Type</th><th>Last Post</th></tr>";
        }
        $forum_view_url = new moodle_url('/mod/forum/view.php',array('f'=>$forum->id));
        if ($discussions) {
            foreach ($discussions as $discussion) {
                $user = $DB->get_record('user', array('id' => $discussion->userid));
                $replies = $DB->get_records_select('forum_posts', 'discussion = ' . $discussion->id . 'AND parent != 0');
                $firstpost = $DB->get_record_select('forum_posts', 'id = ' . $discussion->firstpost);
                $lastpost = $DB->get_record_select('forum_posts', 'discussion = ' . $discussion->id . 'AND modified = ' . $discussion->timemodified . 'AND parent != 0');

                $profile_view_url = new moodle_url('/user/view.php',array('id'=>$user->id));
                $specific_discussion_url = new moodle_url('/mod/forum/discuss.php',array('d'=>$discussion->id));

                if ($lastpost != null) {
                    $lastuser = $DB->get_record('user', array('id' => $lastpost->userid));
                    $dateformat = 'D, j M Y, g:i a';
                    $lastpostdisplay = html_writer::tag('a',$lastuser->firstname . ' ' . $lastuser->lastname,array('href'=>$profile_view_url)).'<br>'.date($dateformat, $lastpost->modified);
                } else {
                    $lastpostdisplay = 'No Posts';
                }

                if ($forum_to_display == 'Group') {
                    $html .= '<tr>'.
                        '<td>'.html_writer::tag('a',$discussion->name,array('href'=>$specific_discussion_url)).'</td>'.
                        '<td>'.html_writer::tag('a',$user->firstname.' '.$user->lastname,array('href'=>$profile_view_url)).'</td>'.
                        '<td>'.count($replies).'</td>'.
                        '<td>'.$lastpostdisplay.'</td>'.
                        '</tr>';
                } else {
                    $html .= '<tr>'.
                        '<td>'.html_writer::tag('a',$discussion->name,array('href'=>$specific_discussion_url)).'</td>'.
                        '<td>'.html_writer::tag('a',$user->firstname.' '.$user->lastname,array('href'=>$profile_view_url)).'</td>'.
                        '<td>'.count($replies).'</td>'.
                        '<td>'.$discussion->viewcount.'</td>'.
                        '<td>'.$firstpost->resourcetype.'</td>'.
                        '<td>'.$lastpostdisplay.'</td>'.
                        '</tr>';
                }
            }
        } else {
            $html .= '<tr><td>No posts to display</td><td/><td/><td/></tr>';
        }
        $html .= '</table>';
        $html .= '<br>'.html_writer::tag("a class='btn'",'Go to Forum',array('href'=>$forum_view_url));
        return $html;
    }

}
