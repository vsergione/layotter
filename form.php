<?php


/**
 * Provides forms for editing elements and options
 */
class Eddditor_Form {
    
    
    private
        $type = '',
        $title = '',
        $icon = '',
        $allowed_fields = array(),
        $provided_values = array();
    
    
    /**
     * Create a new form
     * 
     * @param string $type Type of the form - 'options' or 'element'
     * @param array $allowed_fields Allowed fields array as provided by ACF
     * @param array $provided_values Provided field values
     */
    public function __construct($type, $allowed_fields, $provided_values) {
        $this->type
            = in_array($type, array('options', 'element'))
            ? $type
            : 'element';

        $this->allowed_fields = $allowed_fields;
        $this->provided_values = $provided_values;
    }
    
    
    /**
     * Set human-readable title for this form
     * 
     * @param string $title Form title
     */
    public function set_title($title) {
        $this->title = $title;
    }
    
    
    /**
     * Set icon URL for this form
     * 
     * @param string $icon_url Form icon
     */
    public function set_icon($icon_url) {
        $this->icon = $icon_url;
    }
    
    
    /**
     * Output form HTML
     */
    public function output() {
        // used in the form.php template
        $type = $this->type;
        $title = $this->title;
        $icon = $this->icon;
        $fields = array();
        
        // loop through allowed fields and add field values to the array (where provided)
        foreach ($this->allowed_fields as $field) {
            $field_name = $field['name'];
            
            if (isset($this->provided_values[$field_name])) {
                $field['value'] = $this->provided_values[$field_name];
            }
            
            $fields[] = $field;
        }
        
        require dirname(__FILE__) . '/templates/form.php';
    }

    
}