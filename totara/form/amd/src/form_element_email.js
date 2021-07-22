/**
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
 * @author  Sam Hemelryk <sam.hemelryk@totaralms.com>
 * @package totara_form
 */

/**
 * @module  totara_form/form_element_email
 * @class   EmailElement
 * @author  Sam Hemelryk <sam.hemelryk@totaralms.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define(['jquery', 'totara_form/form_element_text'], function($, TextElement) {

    /**
     * Email element
     *
     * @class
     * @constructor
     * @augments Form.Element
     *
     * @param {(Form|Group)} parent
     * @param {string} type
     * @param {string} id
     * @param {HTMLElement} node
     */
    function EmailElement(parent, type, id, node) {

        if (!(this instanceof EmailElement)) {
            return new EmailElement(parent, type, id, node);
        }

        TextElement.apply(this, arguments);

        this.Name = 'Email';

    }

    EmailElement.prototype = Object.create(TextElement.prototype);
    EmailElement.prototype.constructor = EmailElement;

    /**
     * Returns a string describing this object.
     * @returns {string}
     */
    EmailElement.prototype.toString = function() {
        return '[object EmailElement]';
    };

    return EmailElement;

});