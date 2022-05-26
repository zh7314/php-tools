<?php

namespace ZX\Ec\File;

use ZX\Ec\File\MimeTypes;
use SplFileObject;
use Exception;

class SecurityCheck
{
    //不用string 兼容更多的PHP版本
    protected $filePath = '';

    public function setFilePath(string $filePath = '')
    {
        if (empty($filePath)) {
            throw new Exception('待检测文件路径不能为空');
        }
        $this->$filePath = $filePath;
    }

    /**
     * 获取文件基本信息
     * @return array
     * @throws Exception
     */
    public function getFileInfo()
    {
        if (empty($this->filePath)) {
            throw new Exception('待检测文件路径不能为空');
        }
        //判断文件是否存在，
        if (!file_exists($this->filePath)) {
            throw new Exception('待检测文件未找到');
        }
        //获取 mime.type
        $fileMimeType = mime_content_type($this->filePath);
        $file = new  SplFileObject($this->filePath, 'r');

        return [$fileMimeType, $file];
    }

    /**
     * 检查上传图片是否是图片文件
     * @throws Exception
     */
    public function checkImageFile(array $mimeTypes = [])
    {
        list($fileMimeType, $file) = $this->getFileInfo();
        if (empty($mimeTypes)) {
            $mimeTypes = MimeTypes::getImage();
        }

        $isExist = array_key_exists($fileMimeType, $mimeTypes);
        if (!$isExist) {
            throw new Exception('非允许mime types类型');
        }
        if (empty($mimeTypes[$fileMimeType])) {
            throw new Exception('基础数据丢失');
        }
//        //通过基础的mime type验证之后
        $extension = $file->getExtension();

        try {
            list($width, $height, $type, $attr) = getimagesize($file->getRealPath(), $extension);
            if ($width <= 0 || $height <= 0) {
                return false;
            } else {
                return true;
            }

        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * 检查文件MimeType和文件后缀是否一致
     * @return bool
     * @throws Exception
     */
    public function checkMimeTypeVsExtension(array $mimeTypes = [])
    {
        list($fileMimeType, $file) = $this->getFileInfo();

        $extension = $file->getExtension();
        if (empty($mimeTypes)) {
            $mimeTypes = MimeTypes::getData();
        }

        $isExist = array_key_exists($fileMimeType, $mimeTypes);
        if (!$isExist) {
            throw new Exception('非允许mime types类型');
        }
        if (empty($mimeTypes[$fileMimeType])) {
            throw new Exception('基础数据丢失');
        }

        if (in_array($extension, $mimeTypes[$fileMimeType])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 怀疑上传文件是否是php脚本文件
     * 注意：此操作比较耗时
     * $model false为简单模式 true为严格模式 一般情况下简单模式即可，速度和安全是最为平衡的,
     * 严格模式暂未实现,等有时间在添加
     * @throws Exception
     */
    public function checkPHPFile(array $mimeTypes = [])
    {
        list($fileMimeType, $file) = $this->getFileInfo();

        $extension = $file->getExtension();
        if (empty($mimeTypes)) {
            $mimeTypes = MimeTypes::getPHPScript();
        }

        $isExist = array_key_exists($fileMimeType, $mimeTypes);
        if ($isExist) {
            return true;
        }
        if ($extension == 'php') {
            return true;
        }
        //先判断文件内容是否是二进制
        $file->rewind();
        $data = '';
        while (!$file->eof()) {
            //读取一行，是否是二进制
            $data .= bin2hex($file->fgets());
        }
        return $this->checkHex($data);
    }

    /**
     * 大小写亦可
     * 匹配16进制中的 <% %>
     * 匹配16进制中的 <? ?>
     * 匹配16进制中的 <script /script>
     * 匹配16进制中的 <?php ?>
     * 匹配16进制中的 <?PHP ?>
     * 匹配16进制中的 <SCRIPT /SCRIPT>
     * @param string $hexCode
     * @return bool
     */
    public function checkHex(string $hexCode)
    {
        $tt = preg_match("/3C3F706870|3F3E|3C3F|3C736372697074|2F7363726970743E|3C25|253E|3C3F504850|3C534352495054|2F5343524950543E/is", $hexCode);
        if ($tt) {
            //有恶意代码
            return true;
        } else {
            return false;
        }
    }

    /**
     * 测试方法，正式环境不要用此方法
     * 十六进制转字符串
     * @param string $hex
     * @return string
     */
    public function hexToStr(string $hex)
    {
        $string = "";
        for ($i = 0; $i < strlen($hex) - 1; $i += 2)
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        return $string;
    }

    /**
     * 测试方法，正式环境不要用此方法
     * 字符串转十六进制
     * @param string $string
     * @return string
     */
    public function strToHex(string $string)
    {
        $hex = "";
        for ($i = 0; $i < strlen($string); $i++)
            $hex .= dechex(ord($string[$i]));
        $hex = strtoupper($hex);
        return $hex;
    }
}
