<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'third_party/stripe-php-6.13.0/init.php');

class Stripe {

    private $ci;
    public $config;

    public function __construct($options = array())
    {
        $this->ci = & get_instance();

        $this->config = $options;

        $mode = $this->config['mode'];

        $this->config['secret_key'] = $this->config['stripe_secret_key'];
        $this->config['publishable_key'] = $this->config['stripe_publishable_key'];

        try
        {
            // Use Stripe's bindings...
            \Stripe\Stripe::setApiKey($this->config['secret_key']);
            // To override the API version
            //\Stripe\Stripe::setApiVersion("2018-01-23");
        }
        catch (\Stripe\Error\Authentication $e)
        {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)

            if ($mode == 'test')
            {
                $body = $e->getJsonBody();

                $err  = $body['error'];

                print('Status is:' . $e->getHttpStatus() . "\n");
                print('Type is:' . $err['type'] . "\n");
                print('Code is:' . $err['code'] . "\n");
                // param is '' in this case
                print('Param is:' . $err['param'] . "\n");
                print('Message is:' . $err['message'] . "\n");
            }
        }
    }

    public function get_product($id)
    {
        return \Stripe\Product::retrieve($id);
    }

    public function add_product($options)
    {
        return \Stripe\Product::create($options);
    }

    /**
     * Delete product from stripe
     *
     * @param  string   $id
     * @return boolean
     */
    public function delete_product($id)
    {
        $product = \Stripe\Product::retrieve($id);
        return $product->delete();
    }

    /**
     * Create token from card details to avoid Stripe security issue
     *
     * @param  array    $options card details
     * @return json
     */
    public function create_token($options=[])
    {
        return \Stripe\Token::create($options);
    }

    public function get_subscription($id='')
    {
        return \Stripe\Subscription::retrieve($id);
    }

    public function create_subscription($options=[])
    {
        return \Stripe\Subscription::create($options);
    }

    public function update_subscription($subscription_id, $options)
    {
        $su = $this->get_subscription($subscription_id);
        foreach ($options as $key => $value)
        {
            $su->$key = $value;
        }

        return $su->save();
    }

    public function getPlan($id='')
    {
        return \Stripe\Plan::retrieve($id);
    }

    public function addPlan($options=[])
    {
        return \Stripe\Plan::create($options);
    }

    public function updatePlan($id, $name)
    {
        $plan = \Stripe\Plan::retrieve($id);
        $plan->name = $name;
        $plan->save();
    }

    public function deletePlan($id='')
    {
        $plan = \Stripe\Plan::retrieve($id);
        return $plan->delete();
    }

    public function getEvent($id='')
    {
        return \Stripe\Event::retrieve($id);
    }

    /**
     * Retrieves the details of a charge that has previously been created. Stripe will return the
     * corresponding charge information.
     *
     * @param  string   $charge_id
     * @return object   stripe charge object
     */
    public function getCharge($charge_id)
    {
        return \Stripe\Charge::retrieve($charge_id);
    }

    /**
     * Charge a credit card
     *
     * @param array     $options
     */
    public function addCharge($options = [])
    {
        $options["currency"] = $this->config['currency'];
        return  \Stripe\Charge::create($options);
    }

    /**
     * Updates the specified charge by setting the values of the parameters passed.
     *
     * @param  string   $charge_id
     * @param  array    $options
     * @return object   stripe charge object
     */
    public function updateCharge($charge_id, $options)
    {
        $ch = $this->getCharge($charge_id);
        foreach ($options as $key => $value)
        {
            $ch->$key = $value;
        }
        return $ch->save();
    }

    /**
     * Capture the payment of an existing, uncaptured, charge.
     *
     * @param  string   $charge_id
     * @return object   stripe charge object
     */
    public function captureCharge($charge_id)
    {
        return $this->getCharge($charge_id)->capture();
    }

    /**
     * Returns a list of charges you've previously created.
     *
     * @param  array    $options
     * @return object   stripe charge object
     */
    public function listCharges($options=[])
    {
        return \Stripe\Charge::all($options);
    }

    /**
     * [addRefund description]
     * @param  $charge_id [Creating a new refund will refund a charge that has previously been created but not yet refunded.
     *                           Funds will be refunded to the credit or debit card that was originally charged. The fees you were
     *                           originally charged are also refunded.]
     *
     * @return  The refund object if the refund succeeded
     */
    public function addRefund($charge_id)
    {
        return $this->getCharge($charge_id)->refunds->create();
    }

    /**
     * [getRefund By default, you can see the 10 most recent refunds stored directly on the charge object, but you can also retrieve
     *             details about a specific refund stored on the charge.]
     *
     * @param   $charge_id [ID stripe of charge]
     * @param   $refund_id [ID stripe of refund]
     *
     * @return             [The refund object]
     */
    public function getRefund($charge_id, $refund_id)
    {
        return $this->getCharge($charge_id)->refunds->retrieve($refund_id);
    }

    /**
     * [updateRefund Updates the specified refund by setting the values of the parameters passed.
     *               Any parameters not provided will be left unchanged.]
     *
     * @param   $charge_id [ID stripe of charge]
     * @param   $refund_id [ID stripe of refund]
     *
     * @param   $options   [metadata]
     *
     * @return             [The refund object]
     */
    public function updateRefund($charge_id, $refund_id, $options)
    {
        $re = $this->getRefund($charge_id, $refund_id);
        foreach ($options as $key => $value)
        {
            $re->$key = $value;
        }
        return $re->save();
    }

    /**
     * [listRefunds You can see a list of the refunds belonging to a specific charge. Note that the 10
     *              most recent refunds are always available by default on the charge object. If you
     *              need more than those 10, you can use this API method and the limit and starting_after
     *              parameters to page through additional refunds.]
     *
     * @param   $charge_id [ID stripe of refund]
     * @param  array  $options   [ending_before, limit, starting_after]
     *
     * @return             [A associative array with a data property that contains an array of up to
     *                            limit refunds, starting after refund starting_after.]
     */
    public function listRefunds($charge_id, $options=[])
    {
        return $this->getRefund($charge_id)->refunds->all($options);
    }

    /**
     * Retrieves the details of an existing customer.
     *
     * @param  string   $customer_id
     * @return object   stripe customer object
     */
    public function getCustomer($customer_id)
    {
        return \Stripe\Customer::retrieve($customer_id);
    }

    /**
     * Creates a new customer
     *
     * @param   array   $options
     * @return  object  stripe customer object
     */
    public function addCustomer($options=[])
    {
        return \Stripe\Customer::create($options);
    }

    /**
     * Update an existing customer
     *
     * @param  string   $customer_id
     * @param  array    $options
     * @return object   stripe customer object
     */
    public function updateCustomer($customer_id, $options)
    {
        $cu = $this->getCustomer($customer_id);
        foreach ($options as $key => $value)
        {
            $cu->$key = $value;
        }
        return $cu->save();
    }

    /**
     * Permanently deletes a customer.
     * @param  string   $customer_id
     * @return object   stripe customer object
     */
    public function deleteCustomer($customer_id)
    {
        $cu = $this->getCustomer($customer_id);
        return $cu->delete();
    }

    /**
     * Returns a list of your customers.
     * @param  array  $options
     * @return array  An associative array with customer object
     */
    public function listCustomers($options=[])
    {
        return \Stripe\Customer::all($opctions);
    }

    /**
     * Get subscription details
     * @param  string   $customer_id
     * @param  string   $subscription_id
     * @return object   stripe subscription object
     */
    public function getSubscription($customer_id, $subscription_id)
    {
        return $this->getCustomer($customer_id)->subscriptions->retrieve($subscription_id);
    }

    /**
     * Creates a new subscription on an existing customer.
     * @param   string  $customer_id
     * @param   array   $options
     * @return  object  stripe subscription object
     */
    public function addSubscription($customer_id, $options)
    {
        return $this->getCustomer($customer_id)->subscriptions->create($options);
    }

    /**
     * Updates an existing subscription
     * @param  string   $customer_id
     * @param  string   $subscription_id
     * @param  array    $options
     * @return object   stripe subscription object
     */
    public function updateSubscription($customer_id, $subscription_id, $options)
    {
        $su = $this->getSubscription($customer_id, $subscription_id);
        foreach ($options as $key => $value)
        {
            $su->$key = $value;
        }

        return $su->save();
    }

    /**
     * Cancels a customer's subscription.
     * @param  string   $subscription_id [description]
     * @param  boolean  $at_period_end   [description]
     * @return object   canceled subscription object.
     */
    public function cancelSubscription($subscription_id, $at_period_end=false)
    {
        $subscription = \Stripe\Subscription::retrieve($subscription_id);
        return $subscription->cancel(array('at_period_end' => $at_period_end));
    }


}
