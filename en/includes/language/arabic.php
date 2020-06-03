<?php


function lang($phrase)
{
    static $lang = array( // static array for not relocating array every time
        'login' => 'تسجيل الدخول',
        'Admin Login' => 'تسجيل دخول المسئولين',
        'UserName' => 'أسم المستخدم',
        'Password' => 'الرقم السرى',
        'Login' => 'دخول',
        'Dashboard' => 'لوحة التحكم',

    );
    return $lang[$phrase]; // return value of key when calling it
}