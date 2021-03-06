<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2017 onwards Totara Learning Solutions LTD
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
 * @author Petr Skoda <petr.skoda@totaralearning.com>
 * @package auth_approved
 */

namespace auth_approved\bulk;

defined('MOODLE_INTERNAL') || die();

/**
 * Bulk rejection class.
 */
final class reject extends base {
    /**
     * Execute action.
     *
     * NOTE: this method is not supposed to return,
     *       so either render some form or redirect.
     *
     * @return void
     */
    public function execute() {
        global $OUTPUT;
        $requestids = $this->get_request_ids();

        $currentdata = array('bulkaction' => static::get_name(), 'bulktime' => $this->bulktime);
        $parameters = array('report' => $this->report, 'requestids' => $requestids);
        $form = new \auth_approved\form\bulk_reject($currentdata, $parameters);

        if ($form->is_cancelled()) {
            redirect($this->get_return_url());
        }
        $data = $form->get_data();
        if (!$data) {
            echo $OUTPUT->header();
            echo $form->render();
            echo $OUTPUT->footer();
            die;
        }

        $rejected = 0;
        $errors = 0;
        $requestids = $this->get_request_ids();
        foreach ($requestids as $id) {
            $success = \auth_approved\request::reject_request($id, $data->custommessage);
            if ($success) {
                $rejected++;
            } else {
                $errors++;
            }
        }

        if ($rejected) {
            totara_set_notification(get_string('successrejectbulk', 'auth_approved', $rejected), null, array('class' => 'notifysuccess'));
        }
        if ($errors) {
            totara_set_notification(get_string('errorrejectbulk', 'auth_approved', $errors), null);
        }

        redirect($this->report->get_current_url());
    }
}
