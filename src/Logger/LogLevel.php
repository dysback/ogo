<?php

namespace Dysback\Ogo\Logger;

/**
 * Enum for log levels. (FATAL, ERROR, WARNING, INFO, DEBUG, VERBOSE)
 * FATAL is the highest log level and will be logged always.
 * ERROR will be logged if the log level is FATAL or ERROR.
 * WARNING will be logged if the log level is FATAL, ERROR or WARNING.
 * INFO will be logged if the log level is FATAL, ERROR, WARNING or INFO.
 * DEBUG will be logged if the log level is FATAL, ERROR, WARNING, INFO or DEBUG.
 * VERBOSE will be logged if the log level is FATAL, ERROR, WARNING, INFO, DEBUG or VERBOSE.
 * @package Dysback\Ogo\Logger
 */
enum LogLevel: string
{
    case FATAL = 'FATAL';
    case ERROR = 'ERROR';
    case WARNING = 'WARNING';
    case INFO = 'INFO';
    case DEBUG = 'DEBUG';
    case VERBOSE = 'VERBOSE';

    private const LLA = [
        'FATALFATAL' => true,       'FATALERROR' => true,       'FATALWARNING' => true,
        'FATALINFO' => true,        'FATALDEBUG' => true,       'FATALVERBOSE' => true,
        'ERRORFATAL' => false,      'ERRORERROR' => true,       'ERRORWARNING' => true,
        'ERRORINFO' => true,        'ERRORDEBUG' => true,       'ERRORVERBOSE' => true,
        'WARNINGFATAL' => false,    'WARNINGERROR' => false,    'WARNINGWARNING' => true,
        'WARNINGINFO' => true,      'WARNINGDEBUG' => true,     'WARNINGVERBOSE' => true,
        'INFOFATAL' => false,       'INFOERROR' => false,       'INFOWARNING' => false,
        'INFOINFO' => true,         'INFODebug' => true,        'INFOVERBOSE' => true,
        'DEBUGFATAL' => false,      'DEBUGERROR' => false,      'DEBUGWARNING' => false,
        'DEBUGINFO' => false,       'DEBUGDEBUG' => true,       'DEBUGVERBOSE' => true,
        'VERBOSEFATAL' => false,    'VERBOSEERROR' => false,    'VERBOSEWARNING' => false,
        'VERBOSEINFO' => false,     'VERBOSEDEBUG' => false,    'VERBOSEVERBOSE' => true,
    ];

    /**
     * Check if a log level should be logged.
     * @param LogLevel $logLevel The log level to check.
     * @param LogLevel $actualLogLevel The actual log level.
     * @return bool True if the log level should be logged, false otherwise.
     */
    public static function shouldLogA(LogLevel $logLevel, LogLevel $actualLogLevel)
    {
        return static::LLA[$logLevel->value . $actualLogLevel->value];
    }
}
