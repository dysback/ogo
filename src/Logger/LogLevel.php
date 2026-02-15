<?php

namespace Dysback\Ogo\Logger;

class LogLevel
{
    public const FATAL = 'FATAL';
    public const ERROR = 'ERROR';
    public const WARNING = 'WARNING';
    public const INFO = 'INFO';
    public const DEBUG = 'DEBUG';
    public const VERBOSE = 'VERBOSE';

    private const LLA = [
        'FATAL->FATAL' => true,     'FATAL->ERROR' => true,     'FATAL->WARNING' => true,
        'FATAL->INFO' => true,      'FATAL->DEBUG' => true,     'FATAL->VERBOSE' => true,
        'ERROR->FATAL' => false,    'ERROR->ERROR' => true,     'ERROR->WARNING' => true,
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

    public static function shouldLog($logLevel, $actualLogLevel)
    {
        if ($logLevel == static::FATAL) {
            return true;
        } elseif ($logLevel == static::ERROR && ($actualLogLevel == static::FATAL)) {
            return false;
        } elseif ($logLevel == static::WARNING && ($actualLogLevel == static::FATAL || $actualLogLevel == static::ERROR)) {
            return false;
        } elseif ($logLevel == static::INFO && $actualLogLevel && ($actualLogLevel == static::FATAL || $actualLogLevel == static::ERROR || $actualLogLevel == static::WARNING)) {
            return false;
        } elseif ($logLevel == static::DEBUG && $actualLogLevel != static::DEBUG && $actualLogLevel != static::VERBOSE) {
            return false;
        } elseif ($logLevel == static::VERBOSE && $actualLogLevel != static::VERBOSE) {
            return false;
        }
    }
    public static function shouldLogA($logLevel, $actualLogLevel)
    {
        return static::LLA[$logLevel . $actualLogLevel];
    }
}
