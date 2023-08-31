<?php

/**
 *  Event log
 */

class EventLog
{
    var $errors = array();
    var $events = array();
    var $debug = TRUE;

    function __construct()
    {
        
    }

    /**
     *  global errors match
     * 
     *  getter & setser
     */

    function error($msg = NULL)
    {
        if ($msg === NULL) {

            return $this->errors;
        }

        $this->errors[] = $msg;

        $this->event("! {$msg}");
    }

    function isError()
    {
        return count($this->errors) > 0;
    }

    /**
     * event log getter & setter
     */

    function event($msg = NULL)
    {
        if (!$this->debug) {
            return;
        }

        if ($msg === NULL) {

            return $this->events;
        }

        $this->events[] = $msg;
    }

    function debugEvents()
    {
?>
        <pre>
Events

<?php print_r($this->event()); ?>

Errors

<?php print_r($this->error()); ?>
</pre>
<?php
    }
}
