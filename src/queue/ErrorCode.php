<?php
/**
 * Created by PhpStorm.
 * User: lixin
 * Date: 2017/12/25
 * Time: 下午7:54
 */

namespace queue;


class ErrorCode
{
    const UNSUPPORTED_DRIVER = 1101;
    const CONNECT_OPTIONS_ERROR = 1102;
    const CONNECT_ERROR = 1103;
    const PING_ERROR = 1104;
    const SEND_DATA_ERROR = 1105;
    const SOCKET_ERROR = 1106;
    const CALLBACK_FUNCTION_ERROR = 1107;
}