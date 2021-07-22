<?php
/*
 * This file is part of Totara LMS
 *
 * Copyright (C) 2016 onwards Totara Learning Solutions LTD
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
 * @author Sam Hemelryk <sam.hemelryk@totaralms.com>
 * @package totara_form
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @copyright 2016 Totara Learning Solutions Ltd {@link http://www.totaralms.com/}
 */

namespace totara_form\form\element\behat_helper;

/**
 * Base behat element helper interface.
 *
 * @package totara_form
 */
interface base {

    /**
     * Base constructor.
     *
     * @param \Behat\Mink\Element\NodeElement $node
     * @param \behat_totara_form $context
     */
    public function __construct(\Behat\Mink\Element\NodeElement $node, \behat_totara_form $context);

    /**
     * Returns the current value of the element.
     *
     * @return mixed
     */
    public function get_value();

    /**
     * Sets the value of the element.
     *
     * @param string $value
     * @return void
     */
    public function set_value($value);

}