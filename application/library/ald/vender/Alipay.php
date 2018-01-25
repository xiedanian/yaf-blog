<?php

Yaf_Loader::import(__DIR__ . '/alipay/lib/alipay_core.function.php');
Yaf_Loader::import(__DIR__ . '/alipay/lib/alipay_md5.function.php');
Yaf_Loader::import(__DIR__ . '/alipay/lib/alipay_notify.class.php');
Yaf_Loader::import(__DIR__ . '/alipay/lib/alipay_submit.class.php');
class Ald_Vender_Alipay{

    private $config;
    public function __construct($config){
        $this->config = $config;
    }
    
    /**
     * @param unknown $tradeNo 商户订单号，商户网站订单系统中唯一订单号，必填
     * @param unknown $subject 订单名称，必填
     * @param unknown $totalFee 付款金额，必填
     * @param string $desc 商品描述，可空
     * @return 提交表单HTML文本
     */
    public function buildSubmitForm($tradeNo, $subject, $totalFee, $desc = ''){
        $params = array(
            'service' => $this->config['service'],
            'partner' => $this->config['partner'],
            'seller_id' => $this->config['seller_id'],
            'payment_type' => $this->config['payment_type'],
            'notify_url' => $this->config['notify_url'],
            'return_url' => $this->config['return_url'],
            'anti_phishing_key' => $this->config['anti_phishing_key'],
            'exter_invoke_ip' => $this->config['exter_invoke_ip'],
            'out_trade_no' => $tradeNo,
            'subject' => $subject,
            'total_fee' => $totalFee,
            'body' => $desc,
            '_input_charset' => trim(strtolower($this->config['input_charset'])),
        );
        $objAlipaySubmit = new AlipaySubmit($this->config);
        $htmlText = $objAlipaySubmit->buildRequestForm($params, 'get', '确认');
        return $htmlText;
    }

    /**
     * 检查支付宝回调数据的sign
     * @param $data
     * @return bool
     */
    public function checkSign($data){
        $sign = $data['sign'];
        $objAlipaySubmit = new AlipaySubmit($this->config);
        $generateSign = $objAlipaySubmit -> generateSign($data);
        if($sign != $generateSign){
            return false;
        }
        return true;
    }
}