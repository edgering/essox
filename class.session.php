<?php

/**
 *  BASIC SESSION SUPORT
 * 
 */

class Sessions
{
    // (string) session name for $_SESSION

    var $session_name = "AppSession75aadw";
    var $disable = FALSE;


    function __construct()
    {
    }

    function setSessionName($name)
    {
        $this->session_name = $name;
    }

    function sessionStart()
    {
        if ($this->disable) {
            return FALSE;
        }

        if (session_status() == PHP_SESSION_NONE) {

            session_start();
        }

        return session_status() !== PHP_SESSION_NONE;
    }

    function setSession($key, $value)
    {
        if ($this->sessionStart()) {
            $_SESSION[$this->session_name][$key] = $value;
        }
    }

    function getSession($key)
    {
        if (!$this->sessionStart() || !isset($_SESSION[$this->session_name][$key])) {

            return NULL;
        }

        return $_SESSION[$this->session_name][$key];
    }

    /**
     *  Test if session is expired
     * 
     *  @param $key (string) session key
     *  @param $time_gap (int) time gap in seconds
     * 
     *  @return bool -> TRUE if session is expired
     */

    function testExpires($time_gap = 0, $key = "expires")
    {
        if (!$expires = $this->getSession($key)) {
            return TRUE;
        }

        return $expires < time() + $time_gap;                         
    }
}
