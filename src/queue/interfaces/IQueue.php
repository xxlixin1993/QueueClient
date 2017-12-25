<?php

/**
 * Interface Queue
 */
namespace queue\interfaces;

interface IQueue
{
    /**
     * 连接
     * @return mixed
     * @author lixin
     */
    public function driver();

    /**
     * 发布
     * @param $subject
     * @param $msg
     * @param $id
     * @return mixed
     * @author lixin
     */
    public function publish($subject, $msg, $id);

    /**
     * 关闭
     * @return mixed
     * @author lixin
     */
    public function close();
}