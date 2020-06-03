<?php

function lang($phrase){
    static $lang = array( // static array for not relocating array every time
        'message' => 'welcome',
        // key  => value
        'admin'  => 'adminstrator',

    );
    return $lang[$phrase]; // return value of key when calling it
}