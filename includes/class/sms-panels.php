<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2 & 7.3
 * @ Decoder version: 1.0.6
 * @ Release: 10/08/2022
 */

class MLM_SMS_Panels
{
    private $sms_user = NULL;
    private $sms_pass = NULL;
    private $sms_line = NULL;
    public function __construct()
    {
        $this->sms_user = get_option("mlm_sms_user");
        $this->sms_pass = get_option("mlm_sms_pass");
        $this->sms_line = get_option("mlm_sms_line");
    }
    public function get_site_domain()
    {
        $site_url = esc_url(home_url());
        $site_domain = preg_replace("#^http(s)?://#", "", $site_url);
        $site_domain = preg_replace("/^www\\./", "", $site_domain);
        return $site_domain;
    }
    public function format_text($text)
    {
        $text = html_entity_decode($text, ENT_QUOTES, "UTF-8");
        $text = strip_tags($text);
        return $text . "\r\n" . get_bloginfo("name");
    }
    public function niazpardaz($mobile, $text)
    {
        if (empty($this->sms_user) || empty($this->sms_pass) || empty($this->sms_line)) {
            return false;
        }
        $sms_text = $this->format_text($text);
        if (!$sms_text || !class_exists("SoapClient")) {
            return false;
        }
        ini_set("soap.wsdl_cache_enabled", "0");
        $sms_client = new SoapClient("http://payamak-service.ir/SendService.svc?wsdl", ["encoding" => "UTF-8"]);
        try {
            $parameters["userName"] = $this->sms_user;
            $parameters["password"] = $this->sms_pass;
            $parameters["fromNumber"] = $this->sms_line;
            $parameters["toNumbers"] = [$mobile];
            $parameters["messageContent"] = $sms_text;
            $parameters["isFlash"] = false;
            $recId = [];
            $status = [];
            $parameters["recId"] =& $recId;
            $parameters["status"] =& $status;
            $res = @$sms_client->SendSMS($parameters)->SendSMSResult;
            if ($res == 0) {
                return true;
            }
        } catch (Exception $e) {
        }
        return false;
    }
    public function ipanel($mobile, $text)
    {
        if (empty($this->sms_user) || empty($this->sms_pass) || empty($this->sms_line)) {
            return false;
        }
        $sms_text = $this->format_text($text);
        if (!$sms_text) {
            return false;
        }
        $rcpt_nm = [$mobile];
        $param = ["uname" => $this->sms_user, "pass" => $this->sms_pass, "from" => $this->sms_line, "message" => $sms_text, "to" => json_encode($rcpt_nm), "op" => "send"];
        $handler = curl_init("https://ippanel.com/services.jspd");
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($handler);
        $response2 = json_decode($response2);
        $res_code = $response2[0];
        if ($res_code == 0) {
            return true;
        }
        return false;
    }
    public function mellipayamak($mobile, $text)
    {
        if (empty($this->sms_user) || empty($this->sms_pass) || empty($this->sms_line)) {
            return false;
        }
        $sms_text = $this->format_text($text);
        if (!$sms_text || !class_exists("SoapClient")) {
            return false;
        }
        ini_set("soap.wsdl_cache_enabled", "0");
        $client = new SoapClient("http://api.payamak-panel.com/post/send.asmx?wsdl", ["encoding" => "UTF-8"]);
        try {
            $parameters["username"] = $this->sms_user;
            $parameters["password"] = $this->sms_pass;
            $parameters["from"] = $this->sms_line;
            $parameters["to"] = [$mobile];
            $parameters["text"] = $sms_text;
            $parameters["isflash"] = false;
            $parameters["udh"] = "";
            $recId = [0];
            $status = 0;
            $parameters["recId"] =& $recId;
            $parameters["status"] =& $status;
            $res = $client->SendSms($parameters)->SendSmsResult;
            if ($res == 1) {
                return true;
            }
        } catch (SoapFault $ex) {
            return false;
        }
        return false;
    }
    public function kavenegar($mobile, $text)
    {
        if (empty($this->sms_user) || empty($this->sms_line)) {
            return false;
        }
        $sms_text = $this->format_text($text);
        if (!$sms_text || !class_exists("SoapClient")) {
            return false;
        }
        $path = sprintf("https://api.kavenegar.com/v1/%s/sms/send.json", $this->sms_user);
        $response = wp_remote_get($path, ["body" => ["receptor" => $mobile, "sender" => $this->sms_line, "message" => urlencode($sms_text)]]);
        if (is_array($response) && !is_wp_error($response)) {
            $output = json_decode($response["body"]);
            if (isset($output->return->status) && $output->return->status == 200) {
                return true;
            }
        }
        return false;
    }
    public function farazsms($mobile, $text)
    {
        if (empty($this->sms_user) || empty($this->sms_pass) || empty($this->sms_line)) {
            return false;
        }
        $sms_text = $this->format_text($text);
        if (!$sms_text) {
            return false;
        }
        $rcpt_nm = [$mobile];
        $param = ["uname" => $this->sms_user, "pass" => $this->sms_pass, "from" => $this->sms_line, "message" => $sms_text, "to" => json_encode($rcpt_nm), "op" => "send"];
        $handler = curl_init("https://ippanel.com/services.jspd");
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($handler, CURLOPT_POSTFIELDS, $param);
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $response2 = curl_exec($handler);
        $response2 = json_decode($response2);
        $res_code = $response2[0];
        if ($res_code == 0) {
            return true;
        }
        return false;
    }
    public function parsgreen($mobile, $text)
    {
        if (empty($this->sms_user) || empty($this->sms_line)) {
            return false;
        }
        $sms_text = $this->format_text($text);
        if (!$sms_text || !class_exists("SoapClient")) {
            return false;
        }
        ini_set("soap.wsdl_cache_enabled", "0");
        $client = new SoapClient("http://sms.parsgreen.ir/Api/SendSMS.asmx?WSDL", ["encoding" => "UTF-8"]);
        try {
            $parameters["signature"] = $this->sms_user;
            $parameters["toMobile"] = $mobile;
            $parameters["msgbody"] = $sms_text;
            $parameters[""] = "";
            $res = (array) $client->Send($parameters);
            if ($res["SendResult"] == 1) {
                return true;
            }
        } catch (SoapFault $ex) {
        }
        return false;
    }
    public function ipanel_pattern($mobile, $input_data, $code)
    {
        if (empty($this->sms_user) || empty($this->sms_pass) || empty($this->sms_line)) {
            return false;
        }
        if (!is_array($input_data)) {
            return false;
        }
        $rcpt_nm = [$mobile];
        $url = "https://ippanel.com/patterns/pattern?username=" . $this->sms_user . "&password=" . urlencode($this->sms_pass) . "&from=" . $this->sms_line . "&to=" . json_encode($rcpt_nm) . "&input_data=" . urlencode(json_encode($input_data)) . "&pattern_code=" . $code;
        return $this->cUrl($url, [], "GET");
    }
    private function cUrl($url, $params = [], $method = "POST")
    {
        $handler = curl_init($url);
        curl_setopt($handler, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handler, CURLOPT_TIMEOUT, 20);
        curl_setopt($handler, CURLOPT_CUSTOMREQUEST, $method);
        if ($method == "POST") {
            curl_setopt($handler, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($handler, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($handler);
        if (curl_errno($handler)) {
            return false;
        }
        $result = json_decode($result);
        if (is_numeric($result) && 0 < $result) {
            return true;
        }
        return false;
    }
}

?>