<?php

namespace ProgressUs;

class Fields {

    /**
     * Aparment number
     * @var integer
     */
    protected $number;

    /**
     * Construction
     */
    public function __construct() {

        add_filter('woocommerce_billing_fields',    [$this, 'add_fields'], 10);
        add_action('woocommerce_checkout_process',  [$this, 'validate_apartment_nunber'], 999);

    }

    /**
     * Add custom fields to billing fields
     * Hooked via filter woocommerce_billing_fields, priority 10
     * @param  array $fields
     * @return array
     */
    public function add_fields($fields)
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

    /**
     * Validate apartment_number
     * Hooked via action woocommerce_checkout_process, priority 999
     * @return void
     */
    public function validate_apartment_nunber() {

        $this->number = $_POST['apartment_number'];

        if(!empty($this->number)) :

            $this->number = filter_var( $this->number, FILTER_VALIDATE_INT);

            if(false === $this->number) :
                wc_add_notice( __( ' Your apartment number is not valid. Use numeric only', 'progressus'), 'error' );
            endif;
        endif;
    }
}
