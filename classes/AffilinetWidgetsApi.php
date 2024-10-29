<?php


class AffilinetWidgetsApi
{

	const API_ENDPOINT = 'https://productwidget.com/api/v1.0/widgets';

	/**
	 * Login at the  a token
	 *
	 * Returns false if password mismatch
	 *
	 * @param null $username
	 * @param null $password
	 *
	 * @return bool|String $credentialToken
	 */
    public static function logon($username = null, $password = null)
    {

        try {
        	if ($password === null) {
		        $password = get_option('affilinet_product_widgets_publisher_webservice_password');
	        }
	        if ($username === null) {
		        $username = get_option('affilinet_product_widgets_publisher_id');
	        }
            $logon_client = new \SoapClient('https://api.affili.net/V2.0/Logon.svc?wsdl');
            $params = array(
                "Username" => $username,
                "Password" => $password,
                "WebServiceType" => "Publisher"
            );
            $token = $logon_client->__soapCall("Logon", array($params));

            if ($token !== false) {
                update_option('affilinet_product_widgets_webservice_login_is_correct', 'true', true);
            }

            return $token;
        } catch (\SoapFault $e) {
            update_option('affilinet_product_widgets_webservice_login_is_correct', 'false', true);
	        $errorMessage = __('errors.couldNotConnect', 'affilinet-product-widgets');
	        AffilinetWidgetsHelper::displayHugeAdminMessage($errorMessage, 'error', 'fa-exclamation-triangle');

            return false;
        }
    }

	/**
	 * @return array
	 */
    public static function getMyWidgets()
    {
	    $errorMessage = __('errors.couldNotConnect', 'affilinet-product-widgets');

	    try {
		    $token = self::logon();
		    $publisherId = get_option('affilinet_product_widgets_publisher_id');
		    if ( $token === false ) {
		    	// error already displayed
			    return [];
		    }


		    $url = self::API_ENDPOINT . "?credentialToken=" . $token . "&publisherId=" . $publisherId;
		    $response = wp_remote_get($url);
		    if ( is_array( $response ) ) {
			    return json_decode( $response['body'], true); // use the content
		    } else {
			    AffilinetWidgetsHelper::displayHugeAdminMessage($errorMessage, 'error', 'fa-exclamation-triangle');
			    return [];
		    }

	    }
	     catch (\SoapFault $e) {
		     $errorMessage = $e->getMessage();
		     AffilinetWidgetsHelper::displayHugeAdminMessage($errorMessage, 'error', 'fa-exclamation-triangle');
		     return [];
	    }
    }



}
