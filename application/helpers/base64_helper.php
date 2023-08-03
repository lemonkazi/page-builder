<?php

    function custom_base64_decode($str)
    {

        return urldecode(base64_decode(str_replace(' ', '+', $str)));

    }