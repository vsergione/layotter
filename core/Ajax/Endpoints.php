<?php

namespace Layotter\Ajax;

use Layotter\Core;
use Layotter\Components\Layout;

/**
 * All Ajax requests arrive here
 */
class Endpoints {

    /**
     * Output the edit form for an element
     */
    public static function edit_element() {
        if (isset($_POST['layotter_element_id']) AND ctype_digit($_POST['layotter_element_id']) AND $_POST['layotter_element_id'] != 0) {
            $id = intval($_POST['layotter_element_id']);
            $element = Core::assemble_element($id);
            echo $element->get_form_json();
        } else if (isset($_POST['layotter_type']) AND is_string($_POST['layotter_type'])) {
            $type = $_POST['layotter_type'];
            $element = Core::assemble_new_element($type);
            echo $element->get_form_json();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Save an element
     */
    public static function save_element() {
        if (isset($_POST['layotter_element_id']) AND ctype_digit($_POST['layotter_element_id']) AND $_POST['layotter_element_id'] != 0) {
            $id = intval($_POST['layotter_element_id']);
            $element = Core::assemble_element($id);
            if ($element->is_template()) {
                $element->update_from_post_data();
            } else {
                $element->save_from_post_data();
            }
            echo $element->to_json();
        } else if (isset($_POST['layotter_type']) AND is_string($_POST['layotter_type'])) {
            $element = Core::assemble_new_element($_POST['layotter_type']);
            $element->save_from_post_data();
            echo $element->to_json();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Output the edit form for post, row, col or element options
     */
    public static function edit_options() {
        if (isset($_POST['layotter_post_id'])) {
            $post_type_context = get_post_type($_POST['layotter_post_id']);
        } else {
            $post_type_context = '';
        }

        if (isset($_POST['layotter_options_id']) AND ctype_digit($_POST['layotter_options_id']) AND $_POST['layotter_options_id'] != 0) {
            $id = intval($_POST['layotter_options_id']);
            $options = Core::assemble_options($id);
            $options->set_post_type_context($post_type_context);
            echo $options->get_form_json();
        } else if (isset($_POST['layotter_type']) AND is_string($_POST['layotter_type'])) {
            $type = $_POST['layotter_type'];
            $options = Core::assemble_new_options($type);
            $options->set_post_type_context($post_type_context);
            echo $options->get_form_json();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Save options
     */
    public static function save_options() {
        if (isset($_POST['layotter_post_id'])) {
            $post_type_context = get_post_type($_POST['layotter_post_id']);
        } else {
            $post_type_context = '';
        }

        if (isset($_POST['layotter_options_id']) AND ctype_digit($_POST['layotter_options_id']) AND $_POST['layotter_options_id'] != 0) {
            $id = intval($_POST['layotter_options_id']);
            $options = Core::assemble_options($id);
            $options->set_post_type_context($post_type_context);
            $options->save_from_post_data();
            echo $options->get_id();
        } else if (isset($_POST['layotter_type']) AND is_string($_POST['layotter_type'])) {
            $options = Core::assemble_new_options($_POST['layotter_type']);
            $options->set_post_type_context($post_type_context);
            $options->save_from_post_data();
            echo $options->get_id();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Save element as a new template
     */
    public static function save_new_template() {
        if (isset($_POST['id']) AND ctype_digit($_POST['id'])) {
            $element = Core::assemble_element($_POST['id']);
            $element->set_template(true);
            echo $element->to_json();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Delete a template
     */
    public static function delete_template() {
        if (isset($_POST['layotter_element_id']) AND ctype_digit($_POST['layotter_element_id']) AND $_POST['layotter_element_id'] != 0) {
            $id = intval($_POST['layotter_element_id']);
            $element = Core::assemble_element($id);
            $element->set_template(false);
            echo $element->to_json();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Save a new post layout
     */
    public static function save_new_layout() {
        if (isset($_POST['name']) AND isset($_POST['json'])) {
            $json = stripslashes($_POST['json']);
            $layout = new Layout();
            $layout->set_json($json);
            $layout->save($_POST['name']);
            echo $layout->to_json();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Output post layout data
     */
    public static function load_layout() {
        if (isset($_POST['layout_id'])) {
            $layout = new Layout($_POST['layout_id']);
            echo $layout->to_json();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Rename a post layout
     */
    public static function rename_layout() {
        if (isset($_POST['layout_id']) AND isset($_POST['name'])) {
            $layout = new Layout($_POST['layout_id']);
            $layout->rename($_POST['name']);
            echo $layout->to_json();
        }

        die(); // required by Wordpress after any AJAX call
    }

    /**
     * Delete a post layout
     */
    public static function delete_layout() {
        if (isset($_POST['layout_id'])) {
            $layout = new Layout($_POST['layout_id']);
            $layout->delete();
        }

        die(); // required by Wordpress after any AJAX call
    }
}
