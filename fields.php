<?php

namespace ProgressUs;

class Fields {

    /**
     * Construction
     */
    public function __construct() {
        add_filter('woocommerce_billing_fields', [$this, 'add_fields'], 10);
    }

    /**
     * Add custom fields to billing fields
     * Hooked via filter woocommerce_billing_fields, priority 10
     * @param  array $fields
     * @return array
     */
    function add_fields($fields)
    {
        $fields['apartment_number'] = array(
            'label'       => __('Apartment Number', 'progressus'), // Add custom field label
            'placeholder' => _x('Your apartment number', 'placeholder', 'progressus'), // Add custom field placeholder
            'required'    => false,
            'clear'       => false,
            'type'        => 'text',
            'priority'    => 70
        );

        return $fields;
    }
}
