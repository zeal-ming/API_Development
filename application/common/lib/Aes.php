<?php
namespace app\common\lib;

/**
 * aes 加密 解密类库
 * Class Aes
 * @package app\common\lib
 */
class Aes {

    private $key = null;

    /**
     *
     * @param $key 		密钥
     * @return String
     */
    public function __construct() {
        // 需要小伙伴在配置文件app.php中定义aeskey
        $this->key = config('app.aes_key');
    }

    /**
     * 加密
     * @param String input 加密的字符串
     * @param String key   解密的key
     * @return HexString
     */
    public function encrypt($input = '') {

        // mcrypt支持的加密算法: MCRYPT_RIJNDAEL_128 代表AES128加密算法
        // mcrypt支持的加密模式: MCRYPT_MODE_ECB 代表ECB加密模式

        // 获得加密算法的分组大小
        // mcrypt_get_block_size() 用来获取 cipher （其中包括了加密模式） 加密算法分组大小。
        // 返回值: 整数表达的分组大小。
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);

        // 明文字符串长度必须为16的倍数
        $input = $this->pkcs5_pad($input, $size);

        // mcrypt_module_open — 打开算法和模式对应的模块
        // 返回值: 成功则返回加密描述符，如果发生错误则返回 FALSE。
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');

        // mcrypt_enc_get_iv_size — 返回打开的算法的初始向量大小, 参数为td加密描述符。返回初始向量大小。如果算法忽略初始向量，则返回 0。
        // mcrypt_create_iv — 从随机源创建初始向量
        // 参数
//        size: 初始向量大小。
//
        //source
        //初始向量数据来源。可选值有： MCRYPT_RAND （系统随机数生成器）, MCRYPT_DEV_RANDOM （从 /dev/random 文件读取数据） 和 MCRYPT_DEV_URANDOM （从 /dev/urandom 文件读取数据）。 在 Windows 平台，PHP 5.3.0 之前的版本中，仅支持 MCRYPT_RAND。
//
        // 返回初始向量。如果发生错误，则返回 FALSE。
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);

        // mcrypt_generic_init — 初始化加密所需的缓冲区
//        返回值
            //如果发生错误，将会返回负数： -3 表示密钥长度有误，-4 表示内存分配失败， 其他值表示未知错误， 同时会显示对应的警告信息。 如果传入参数不正确，返回 FALSE。
        mcrypt_generic_init($td, $this->key, $iv);

        // mcrypt_generic — 加密数据
        $data = mcrypt_generic($td, $input);

        // mcrypt_generic_deinit — 对加密模块进行清理工作, 本函数终止由加密描述符（td）指定的加密模块,它会清理缓冲区
        mcrypt_generic_deinit($td);

        // mcrypt_module_close — 关闭加密模块
        mcrypt_module_close($td);

        // 加密完成

        // base64_encode — 使用 MIME base64 对数据进行编码
        $data = base64_encode($data);

        // 编码之后返回
        return $data;

    }



    /**
     * 填充方式 pkcs5
     * @param String text 		 原始字符串
     * @param String blocksize   加密长度, (整数表达的分组大小。)
     * @return String
     */
    private function pkcs5_pad($text, $blocksize) {

        $pad = $blocksize - (strlen($text) % $blocksize); //取得补码的长度

        // chr()返回指定的字符
        // str_repeat(str, num) 重复一个字符串, 将str重复num次
        return $text . str_repeat(chr($pad), $pad); //用ASCII码为补码长度的字符， 补足最后一段
    }

    /**
     * 解密
     * @param String input 解密的字符串
     * @param String key   解密的key
     * @return String
     */
    public function decrypt($sStr) {

        // mcrypt_decrypt — 使用给定参数解密密文
        // base64_decode() - 对使用 MIME base64 编码的数据进行解码
        $decrypted= mcrypt_decrypt(MCRYPT_RIJNDAEL_128,$this->key,base64_decode($sStr), MCRYPT_MODE_ECB);

        // 计算解密后的字符串长度
        $dec_s = strlen($decrypted);

        // ord — 返回字符的 ASCII 码值
        // 获取解密字符串最后一个字符的ASCII码值.
        $padding = ord($decrypted[$dec_s-1]); // 去掉补码

        $decrypted = substr($decrypted, 0, -$padding);

        return $decrypted;
    }

}