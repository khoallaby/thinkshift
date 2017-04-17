<?php
// include with time zone constants et al
include_once 'LogFileObjConfig.php';
/**
 * Logfile object (LogFileObj) facilitates the logging of messages
 * to a log file created at instantiation (__construct)
 * Defaults :   file name defaults to logfile.log
 *              handle defaults to null
 *              mode defaults to 'a' (append no overwrite)
 * NOTES :  -- The application using LogFileObj may terminate processing
 *          using lfWriteLn by providing the value true as the third
 *          parameter.  This is not available in the lfWrite method.
 *          -- Closing the log file is not necessary as the __destruct
 *          method will handle it prior to de-allocation of the
 *          calling process.
 * Author : John Borelli
 * History :08/20/2016 -- Created initial design of log file object
 *          08/22/2016 -- Added documentation and halt process in lfWriteLn()
 */

date_default_timezone_set(TIME_ZONE);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

/**
 * Class LogFileObj
 */
class LogFileObj {
    /**
     * Variables that are local to the object (private)
     */
	private $filename='logfile.log';
	private $handle=null;
	private $mode='a+';

    /**
     * @return string
     */
    public function fDate(){
        return date('m/d/Y h:i:s a', time());
    }

    /**
     * LogFileObj constructor.
     * @param string $fname
     */
	public function __construct($fname='logfile.log') {
	    if ($fname==='') {
            return 0;
        } else {
            $this->filename = $fname;
            $this->handle = fopen($this->filename, $this->mode);
            return 1;
        }
	}

    /**
     * Make sure the log file gets properly closed at termination
     */
	public function __destruct() {
		fclose($this->handle);
	}

    /**
     * @return null|resource
     */
	public function getHandle() {
		return $this->handle;
	}

    /**
     * @return string
     */
	public function getFName() {
		return $this->filename;
	}

    /**
     * @return string
     */
	public function getMode() {
		return $this->mode;
	}

    /**
     * @param string $s
     * @param bool $dt
     * @param bool $echo
     */
	public function lfWrite($s='', $dt=true, $echo=false) {
        $st='-- ';
	    if ($dt) {
            $st.=$this->fDate().'   '.$s;
        } else {
            $st.=$s;
        }
        fwrite($this->handle, $st);
        if ($echo) {echo $st;}
	}

    /**
     * log file write with lf/cr
     * @param string $s (required)
     *      The string/message to write
     * @param bool $dt (optional)
     *      Pre-pend with date/time?
     * @param bool $kill (optional)
     *      End the program? (critical error?)
     * @param bool $echo (optional)
     *      Also provide a duplicate of the output to the screen?
     */
	public function lfWriteLn($s='', $dt=true, $kill=false, $echo=false) {
	    $st='-- ';
	    if ($dt){
	        $st.=$this->fDate().'   '.$s.chr(10);
        } else {
            $st.=$s.chr(10);
        }
		fwrite($this->handle,$st);
        if ($echo){echo $st;}
        if ($kill){
            fwrite($this->handle,'Fatal error.  Process halted.'.chr(10));
            if ($echo){echo ('Fatal error.  Process halted.');}
            die();
        }
	}
}
