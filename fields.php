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

        add_filter('woocommerce_billing_fields',                    [$this, 'add_fields'], 10);
        add_action('woocommerce_checkout_process',                  [$this, 'validate_apartment_number'], 999);
        add_action('woocommerce_checkout_order_processed',          [$this, 'set_apartment_number'], 999, 3);
        add_action('woocommerce_order_formatted_billing_address',   [$this, 'prepare_data'], 999, 2);
        add_filter('woocommerce_localisation_address_formats',      [$this, 'set_address_format'], 1);
        add_filter('woocommerce_formatted_address_replacements',    [$this, 'set_replacement_value'], 999, 2);

    }

    /**
     * Add custom fields to billing fields
     * Hooked via filter woocommerce_billing_fields, priority 10
     * @param  array $fields
     * @return array
     */
    public function add_fields(array $fields)
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
    public function validate_apartment_number() {

        $this->number = $_POST['apartment_number'];

        if(!empty($this->number)) :

            $this->number = filter_var( $this->number, FILTER_VALIDATE_INT);

            if(false === $this->number) :
                wc_add_notice( __( ' Your apartment number is not valid. Use numeric only', 'progressus'), 'error' );
            endif;
        endif;
    }

    /**
     * Set apartment number to order meta
     * Hooked via action woocommerce_new_order, priority 999
     * @param int $order_id
     */
    public function set_apartment_number($order_id, $posted_data, $order) {
;
        $number = $posted_data['apartment_number'];
        $number = filter_var( $number, FILTER_VALIDATE_INT);

        if(is_numeric($number)) :
            $order->update_meta_data('apartment_number', $number);
            $order->save();
        endif;
    }

    /**
     * Display apartment number ini billing address
     * Hooked via filter woocommerce_order_formatted_billing_address, priority 999
     * @param  array $address
     * @return array
     */
    public function prepare_data(array $address, \WC_Order $order) {

        $apartment_number = $order->get_meta('apartment_number');

        $address['apartment'] = $apartment_number;
        return $address;
    }

    /**
     * Set address format data
     * Hooked via filter woocommerce_formatted_address_replacements, priority 999
     * @param  array $formats
     * @return array
     */
    public function set_address_format($formats)  {

        foreach($formats as $key => &$format) :
            $format .= "\n{apartment}";
        endforeach;

        return $formats;
    }

    /**
     * Replace address format value for apartment value
     * Hooked via filter woocommerce_formatted_address_replacements, priority 999
     * @param  array $replacements
     * @param  array $args
     * @return array
     */
    public function set_replacement_value($replacements, $args) {

        if(isset($args['apartment'])) :
            $replacements['{apartment}'] = sprintf(__('Apartment no %s', 'progressus'), $args['apartment']);
        endif;

        return $replacements;
    }

}
