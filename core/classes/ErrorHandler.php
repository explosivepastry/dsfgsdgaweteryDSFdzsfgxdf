<?php
/*
 *	Made by Samerton
 *  Additions by Aberdeener
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr9
 *
 *  License: MIT
 *
 *  Error handler class
 */

class ErrorHandler {

    /*
     * Defined for easy changing.
     * This constant indicates how many LOC from each frame's PHP file to show before and after the highlighted line
     */
    const LINE_BUFFER = 20;

    /*
     * Used to neatly display exceptions and the trace/frames leading up to it.
     * If this is called manually, the error_string, error_file and error_line must be manually provided,
     * and a single trace frame will be generated for it.
     */
    public static function catchException($exception, $error_string = null, $error_file = null, $error_line = null) {

        // Define variables based on if a Throwable was caught by the compiler, or if this was called manually
        $error_string = is_null($exception) ? $error_string : $exception->getMessage();
        $error_file = is_null($exception) ? $error_file : $exception->getFile();
        $error_line = is_null($exception) ? intval($error_line) : $exception->getLine();

        // Create a log entry for viewing in staffcp
        self::logError('fatal', '[' . date('Y-m-d, H:i:s') . '] ' . $error_file . '(' . $error_line . '): ' . $error_string);

        $frames = array();

        // Most recent frame is not included in getTrace(), so deal with it individually
        $frames[] = self::parseFrame($exception, $error_file, $error_line);

        // Loop all frames in the exception trace & get relevent information
        if ($exception != null) {

            $i = count($exception->getTrace());

            foreach ($exception->getTrace() as $frame) {
                $frames[] = self::parseFrame($exception, $frame['file'], $frame['line'], $i);
                $i--;
            }
        }

        define('ERRORHANDLER', true);
        require_once(ROOT_PATH . DIRECTORY_SEPARATOR . 'error.php');
        die();
    }

    /*
     * Returns frame array from specified information.
     * Leaving number as null will use Exception trace count + 1 (for most recent frame)
     */
    private static function parseFrame($exception, $error_file, $error_line, $number = null) {
        $lines = file($error_file);

        return [
            'number' => is_null($number) ? (is_null($exception) ? 1 : count($exception->getTrace()) + 1) : $number,
            'file' => $error_file,
            'line' => $error_line,
            'start_line' => count($lines) >= self::LINE_BUFFER ? ($error_line - self::LINE_BUFFER) : 1,
            'highlight_line' => count($lines) >= self::LINE_BUFFER ? (self::LINE_BUFFER + 1) : $error_line,
            'code' => self::parseFile($lines, $error_line)
        ];
    }

    /*
     * Create purified and truncated string from a file
     * for use with error source code preview.
     */
    private static function parseFile($lines, $error_line) {

        $return = '';

        if ($lines == false || count($lines) < 1) {
            return $return;
        }

        $line_num = 1;

        foreach ($lines as $line) {
            if (($error_line - self::LINE_BUFFER) <= $line_num && $line_num <= ($error_line + self::LINE_BUFFER)) {
                $return .= Output::getClean($line);
            }

            $line_num++;
        }

        return $return;
    }

    public static function catchError($error_number, $error_string, $error_file, $error_line) {

        if (!(error_reporting() & $error_number)) {
            return false;
        }

        switch($error_number) {
            case E_USER_ERROR:
                // Pass execution to new error handler.
                // Since we registered an exception handler, I dont think this will ever be called,
                // simply a precaution.
                self::catchException(null, $error_string, $error_file, $error_line);
                break;

            case E_USER_WARNING:
                self::logError('warning', '[' . date('Y-m-d, H:i:s') . '] ' . $error_file . '(' . $error_line . ') ' . $error_number . ': ' . $error_string);
                break;

            case E_USER_NOTICE:
                self::logError('notice', '[' . date('Y-m-d, H:i:s') . '] ' . $error_file . '(' . $error_line . ') ' . $error_number . ': ' . $error_string);
                break;

            default:
                self::logError('other', '[' . date('Y-m-d, H:i:s') . '] ' . $error_file . '(' . $error_line . ') ' . $error_number . ': ' . $error_string);
                break;
        }

        return true;
    }

    private static function catchShutdownError() {
        $error = error_get_last();

        if ($error == null) {
            return;
        }

        if ($error['type'] === E_ERROR) {
            self::catchException(null, $error['message'], $error['file'], $error['line']);
        }
    }

    private static function logError($type, $contents) {

        $dir_exists = false;

        try {

            if (!is_dir(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs')))) {
                if (is_writable(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache')) {
                    mkdir(ROOT_PATH . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'logs');
                    $dir_exists = true;
                }
            } else {
                $dir_exists = true;
            }

            if ($dir_exists) {
                file_put_contents(join(DIRECTORY_SEPARATOR, array(ROOT_PATH, 'cache', 'logs', $type . '-log.log')), $contents . PHP_EOL, FILE_APPEND);
            }

        } catch (Exception $exception) {
            // Unable to write to file, ignore for now
        }
    }

    // Log a custom error
    // Not used internally. Only for modules
    public static function logCustomError($contents) {
        self::logError('other', $contents);
    }
}
