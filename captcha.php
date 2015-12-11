<?php

/**
 * Captcha of Recaptcha_XH.
 *
 * Site Key : $plugin_cf['recaptcha']['key_public']
 * Secret Key :$plugin_cf['recaptcha']['key_private']
 *
 * Copyright (c) 2011 Christoph M. Becker (see license.txt)
 * Copyright (c) 2015 utaka <http://cmsimple-jp.org/>
 */
 

// utf-8-marker: äöüß


if (!defined('CMSIMPLE_XH_VERSION')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

/**
 * Returns the (x)html block element displaying the captcha,
 * the input field for the captcha code and all other elements,
 * that are related directly to the captcha,
 * such as an reload and an audio button.
 *
 * @return string
 */
function recaptcha_captcha_display() {
    global $plugin_cf, $hjs;
	
	// into <head>~</head>
    $hjs .= '<script src="https://www.google.com/recaptcha/api.js';
	if(!empty($plugin_cf['recaptcha']['language'])){
		$hjs .= '?hl=' . $plugin_cf['recaptcha']['language'];
	}
    $hjs .= '"></script>';

	// into <form>~</form>
    $into_form = '<div class="g-recaptcha"';
	if(trim($plugin_cf['recaptcha']['theme']) == 'dark'){
		$into_form .= ' data-theme="dark"';
	}
	if(trim($plugin_cf['recaptcha']['type']) == 'audio'){
		$into_form .= ' data-type="audio"';
	}
	if(trim($plugin_cf['recaptcha']['size']) == 'compact'){
		$into_form .= ' data-size="compact"';
	}
	$into_form .= ' data-sitekey="'. $plugin_cf['recaptcha']['key_site'] .'"></div>';
    return $into_form;
}


/**
 * Returns wether the correct captcha code was entered
 * after the form containing the captcha was posted.
 *
 * @return bool
 */
function recaptcha_captcha_check() {
    global $plugin_cf;

    if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']) &&
        is_string($_POST['g-recaptcha-response'])) {
        $response_key = $_POST["g-recaptcha-response"];
    } else {
        $response_key = '';
    }

	// secret_key
	$secret_key = $plugin_cf['recaptcha']['key_secret'] ;

	// api URL
	$apiUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secret_key . '&response=' . $response_key ;

	$responceData = json_decode(file_get_contents($apiUrl));
	if ($responceData->success) {
	    return true; // OK
	} else {
	    // $responceData->error-codes ;// error Code
	     return false; // FALSE
	}
}

?>
