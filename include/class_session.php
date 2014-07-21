<?php

class HOMESession extends Session {

    static function &getInstance($base = null, $start = true) {
        static $instance;
        if (null === $instance) {
            $instance = new HOMESession($base, $start);
        }
        return $instance;
    }

    function get($key, $default = null, $group = null) {
        if (!$group)
            $group = 'default';
        if ($group)
            $group = '_' . $group;

        if (isset($_SESSION[$group][$key])) {
            return $_SESSION[$group][$key];
        } else {
            return $default;
        }
    }

    function set($key, $value = null, $group = 'default') {
        if (!$group)
            $group = 'default';
        if ($group)
            $group = '_' . $group;

        if (null === $value) {
            unset($_SESSION[$group][$key]);
        } else {
            $_SESSION[$group][$key] = $value;
        }
    }

    function has($key, $group = null) {
        if (!$group)
            $group = 'default';
        if ($group)
            $group = '_' . $group;

        return isset($_SESSION[$group][$key]);
    }

    function copy() {
        if (function_exists('session_write_close')) {
            session_write_close();
        }
        session_start();
    }

    function clear($key, $group = null) {
        return $this->set($key, null, $group);
    }

}

class Session {

    function __construct($base = null, $start = true) {

        if ($start) {
            return $this->start();
        }
        return FALSE;
    }

    function start() {
        if (function_exists('session_write_close')) {
            session_write_close();
        }
        return $this->__startSession();
    }

    function __startSession() {
        //set expire session
        ini_set('session.gc_maxlifetime', 8 * 60 * 60);
        $sessionCookieExpireTime = 8 * 60 * 60;
        session_set_cookie_params($sessionCookieExpireTime);
        if (headers_sent()) {
            if (empty($_SESSION)) {
                $_SESSION = array();
            }
            return false;
        } elseif (!isset($_SESSION)) {
            session_cache_limiter("must-revalidate");
            session_start();
            header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
            return true;
        } else {
            session_start();
            return true;
        }
    }

}
