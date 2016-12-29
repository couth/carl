<?php
namespace \libraries;

/**
 * Class YuncaiHelper 助手类，依赖于Yii2框架
 *
 * @package common\libraries
 */
class CarlHelper
{
    /**
     * 写文件日志
     *
     * @param string $data 日志数据
     * @param string $file 日志文件
     * @param string $type 写入模式
     * @return bool
     */
    public static function fileWriteLog($data = '', $file = '', $type = 'a')
    {
        if (empty($file)) {
            return false;
        }
        $data = "--------------------------------------------------------------------------------\n" .
            date('Y-m-d H:i:s') . "\n" . print_r($data, true) . "\n";

        return self::fileWrite($file, $data, $type);
    }

    /**
     * 写文件操作
     *
     * @param string $file
     * @param string $data
     * @param string $mode
     * @param int $max_retries
     * @param int $usleep_min
     * @param int $usleep_max
     * @return bool
     */
    public static function fileWrite(
        $file = '',
        $data = '',
        $mode = 'w',
        $max_retries = 20,
        $usleep_min = 100,
        $usleep_max = 1000
    ) {
        $fp = fopen($file, $mode);
        if (!$fp) {
            return false;
        }
        $retries = 0;
        do {
            if ($retries > 0) {
                usleep(rand($usleep_min, $usleep_max));
            }
            $retries += 1;
        } while (!flock($fp, LOCK_EX) and $retries <= $max_retries);
        if ($retries == $max_retries) {
            return false;
        }
        fwrite($fp, $data);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);

        return true;
    }

    /**
     * 生成GUID
     */
    public static function guid()
    {
        //if (function_exists('com_create_guid') === true)
        //{
        //	return trim(com_create_guid(), '{}');
        //}
        //return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));

        // 1、去掉中间的“-”，长度有36变为32
        // 2、字母由“大写”改为“小写”
        if (function_exists('com_create_guid') === true)
        {
            return strtolower(str_replace('-', '', trim(com_create_guid(), '{}')));
        }

        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
    }
}