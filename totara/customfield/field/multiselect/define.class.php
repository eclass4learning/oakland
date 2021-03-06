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
 * @author Simon Coggins <simon.coggins@totaralms.com>
 * @author Valerii Kuznetsov <valerii.kuznetsov@totaralms.com>
 * @package totara
 * @subpackage totara_customfield
 */
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/totara/customfield/definelib.php');
class customfield_define_multiselect extends customfield_define_base {
    const MAX_CHOICES = 128;

    public function define_form_specific(&$form) {
        $title =  get_string('multiselectoptions', 'totara_customfield');
        $this->define_add_js($form, '_multiselectitem', false);
        $form->addElement('static', 'cautiondatele', '',
                get_string('multiselectdeleteunlink', 'totara_customfield'));
        $group = array();
        $sep = array();

        for ($menuind = 0; $menuind < self::MAX_CHOICES; $menuind++) {
            if ($menuind === 0) {
                $group[] = $form->createElement('static', ' ','', '');
                $sep[] = '<div id="fgroup_id_multiselectitem_' . $menuind . '"><span class="customfield-multiselect-action customfield-multiselect-input">';
            } else {
                $sep[] = '</span></div><div id="fgroup_id_multiselectitem_' . $menuind . '"><span class="customfield-multiselect-action customfield-multiselect-input">';
            }

            $group[] = $form->createElement('text', $menuind.'][option', get_string('optionxtext', 'totara_customfield', $menuind + 1));
            $sep[] = '</span><span class="customfield-multiselect-action customfield-multiselect-icon">';
            $icon = totara_create_icon_picker($form, 'edit', 'course', '', 0, $menuind);
            // The name of the icon picker doesn't really suit our purposes here.
            $icon['icon' . $menuind]->_attributes['name'] = $menuind . '][icon';
            $group = array_merge($group, $icon);
            $sep[] = '</span><span class="customfield-multiselect-action customfield-multiselect-default-checkbox">';
            $group[] = $form->createElement('hidden', $menuind . '][src', '', array('id' => 'src' . $menuind));
            $group[] = $form->createElement('advcheckbox', $menuind . '][default', '',
                    get_string('defaultselected', 'totara_customfield'), array('class' => 'makedefault'));
            $sep[] = '</span><span class="customfield-multiselect-action customfield-multiselect-delete-checkbox">';
            $group[] = $form->createElement('advcheckbox', $menuind.'][delete', '', get_string('delete'),
                array('class' => 'delete'));

            $form->setType('multiselectitem['.$menuind.'][src]', PARAM_TEXT);
            $form->setType('multiselectitem['.$menuind.'][icon]', PARAM_ALPHANUMEXT);
            $form->setType('multiselectitem['.$menuind.'][option]', PARAM_MULTILANG);
            $form->setType('multiselectitem['.$menuind.'][delete]', PARAM_INT);

        }
        $group[] = $form->createElement('static', ' ','', '');
        $sep[] = '</span></div>';

        $group[] = $form->createElement('static', 'addoptionelem', '',
                html_writer::link('#', get_string('addanotheroption', 'totara_question'),
                        array('id' => "addoptionlink_specificsettings", 'class' => 'addoptionlink')));
        $sep[] = '';
        $form->addElement('group', 'multiselectitem', $title, $group, $sep);
        $form->addHelpButton('multiselectitem', 'customfieldmultiselectoptions', 'totara_customfield');
    }

    public function define_validate_specific($data, $files, $tableprefix) {
        $err = array();
        $itemerr = true;
        $addoptions = array();
        foreach ($data->multiselectitem as $ind => $item) {
            if ($item['option'] != '') {
                $itemerr = false;
            }
            if ($item['option'] != '') {
                if (in_array($item['option'], $addoptions)) {
                    $err["multiselectitem"] = get_string('menunotuniqueoptions', 'totara_customfield');
                }
                $addoptions[] = $item['option'];
            }
        }
        if ($itemerr) {
            $err['multiselectitem[0]'] = get_string('menunooptions', 'totara_customfield');
        }
        return $err;
    }

    public function define_save_preprocess($data, $old = null) {
        global $DB;
        // Rename first.
        if ($old) {
            $dataold = $this->define_load_preprocess($old);
            foreach ($dataold->multiselectitem as $ind => $item) {
                if (!$data->multiselectitem[$ind]['delete'] &&
                    ($data->multiselectitem[$ind]['option'] != $item['option'] ||
                         $data->multiselectitem[$ind]['icon'] != $item['icon'])) {
                    $tableprefix = $data->tableprefix;
                    $oldhash = md5($dataold->multiselectitem[$ind]['option']);
                    $newhash = md5($data->multiselectitem[$ind]['option']);
                    // Rename: change value in data table.
                    $options = $DB->get_records($tableprefix.'_info_data',
                            array('fieldid' => $dataold->id));
                    foreach ($options as $option) {
                        $itemdata = json_decode($option->data, true);
                        if (isset($itemdata[$oldhash])) {
                            unset($itemdata[$oldhash]);
                            $item['option'] = $data->multiselectitem[$ind]['option'];
                            $item['icon'] = $data->multiselectitem[$ind]['icon'];
                            $itemdata[$newhash] = $item;
                            $option->data = json_encode($itemdata);
                            $DB->update_record($tableprefix.'_info_data', $option);
                        }
                    }

                    // Rename: change param hashes.
                    $sql = 'UPDATE {'.$tableprefix.'_info_data_param} SET value = ?
                            WHERE
                            value = ?
                            AND dataid IN
                                (SELECT id FROM {'.$tableprefix.'_info_data} WHERE fieldid = ?)';
                    $params = array($newhash, $oldhash, $dataold->id);
                    $DB->execute($sql, $params);
                }
            }
        }

        // Then filter out deleted items.
        $param1 = array_filter($data->multiselectitem, function($item) {
            return ($item['delete'] == 0 && $item['option'] != '');
        });
        // Remove the src field (we calculate it when needed).
        foreach (array_keys($param1) as $key) {
            unset($param1[$key]['src']);
        }
        $data->param1 = json_encode(array_values($param1));

        return $data;
    }

    public function define_load_preprocess($data) {
        if (isset($data->param1) && $data->param1 != '') {
            $data->multiselectitem = json_decode($data->param1, true);
            foreach ($data->multiselectitem as $key => $item) {
                list($src, $alt) = totara_icon_url_and_alt('course', $item['icon']);
                $data->multiselectitem[$key]['src'] = $src;
            }
        }
        return $data;
    }

    /**
     * Add scale/choices options supporting JS
     * We don't use $PAGE->js_init_call() because it calls only functions.
     * However, we can generate JS code here as a function and run it using js_init_call()
     *
     * @param MoodleQuickForm $form
     * @param string $jsid Javascript container id (make sense only when more than one choices menu on a page)
     * @return customfield_define_multiselect $this
     */
    public function define_add_js($form, $jsid = '', $limitone = true) {
        global $PAGE, $CFG;

        require_once($CFG->dirroot . '/totara/core/js/lib/setup.php');
        local_js(array(
                TOTARA_JS_UI,
                TOTARA_JS_ICON_PREVIEW,
                TOTARA_JS_DIALOG,
                TOTARA_JS_TREEVIEW
                ));

        $max = self::MAX_CHOICES;

        $PAGE->requires->strings_for_js(array('defaultmake', 'defaultselected', 'undelete'),
                'totara_customfield');
        $PAGE->requires->strings_for_js(array('delete'), 'moodle');
        $args = array(
            'oneAnswer' => $limitone,
            'jsid' => $jsid,
            'max' => $max
        );

        $PAGE->requires->js_call_amd('totara_customfield/field_multiselect-lazy', 'init', $args);

        // Icon picker.
        $PAGE->requires->string_for_js('chooseicon', 'totara_program');
        $iconjsmodule = array(
                        'name' => 'totara_iconpicker',
                        'fullpath' => '/totara/core/js/icon.picker.js',
                        'requires' => array('json'));
        $currenticon = isset($course->icon) ? $course->icon : 'default';
        $iconargs = array('args' => '{"type":"course"}');
        $PAGE->requires->js_init_call('M.totara_iconpicker.init', $iconargs, false, $iconjsmodule);

        return $this;
    }

}
