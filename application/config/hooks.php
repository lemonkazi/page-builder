<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend Bloxby without hacking the core
| files.
|
*/

// $hook['auth_construct'][] = array(
//         'class'    => 'MY_Auth',
//         'function' => 'auth_construct',
//         'filename' => 'MY_Auth.php',
//         'filepath' => 'hooks',
//         'params'   => ''
// );

// $hook['auth_index_pre'][] = array(
//         'class'    => 'MY_Auth',
//         'function' => 'auth_index_pre',
//         'filename' => 'MY_Auth.php',
//         'filepath' => 'hooks',
//         'params'   => ''
// );

$hook['auth_index_post'][] = array(
        'class'    => 'MY_Auth',
        'function' => 'auth_index_post',
        'filename' => 'MY_Auth.php',
        'filepath' => 'hooks',
        'params'   => ''
);

$hook['auth_destruct'][] = array(
        'class'    => 'MY_Auth',
        'function' => 'auth_destruct',
        'filename' => 'MY_Auth.php',
        'filepath' => 'hooks',
        'params'   => ''
);