<?php

namespace App\Http\Controllers\silica;

use App\Http\Controllers\Controller;

/* $private_key = openssl_get_privatekey("-----BEGIN RSA PRIVATE KEY-----
  MIICXAIBAAKBgQCnCt3RZL9gwTGzjs7oVBDOiUdTX4J4XQYXtiJnO+1w6PefXEh4
  vToHmBftvn+WNQoAHibi6PWoxMEBh142ueWMW4pou4Ls+dS/9ElnMXl0rJbrJFGY
  3KQ1Glu5ijRqybb8FqTw7mtGDfdNO/Dajd9ue9hdOjyToj/+PO8ComDbDQIDAQAB
  AoGAZm1ZWt6GI1QTn+C/quJxc9Plso/sNtYDuGJschTjIcsYm9VAcxjFDocKlTOs
  lHRtb2kNuzHSAOy01LUm5jPTNuGmq2y6l1+2zyqzq1awvgvozJJo2SINroMwhUXA
  LmQvX+Fp7F3Q7ZoFGTxJrb4TNmAs74J3sZl5L1tf+6HxIgECQQDabIkglLmXzlpm
  FSgjD8JVv6TiE5kXSsaURkWyc5ACSPRaNs/ki9WOqXDC7HEqmLwtVv0xwNF6eqvR
  1D7/1TxNAkEAw8d4tvXEoCXSkYluRXCPKGiA1ULuxNwpOKOPWS1q7PPsInJY/2RD
  +ZxmZTMUs5m85eu/7IKCtjHXqFqDNJZ5wQJAXUQbCZ534Sprz0sZaF9CS/sZHK/h
  nuB/CrE28YwG4fLk4+CjxKMw/Um8rH7pk5bEQ1fxpV0AFZxr4Z0PiY4zwQJAWSsL
  vwD2+h3f5utaLwRSH4xhSUB/8Wd42tQb4Pj/n0aFgTbldASdtR1XwbXfMuHNmEak
  ljrm/8Z5u7Ll2VjDwQJBAMORrw+1gFzDI5JZiWbQ2gOAUwGadWXFY+6E6py03+jl
  tFE3lNAVVqmI6zfHsoxYXfSDMM4POIhqcny5+SVuMOA=
  -----END RSA PRIVATE KEY-----");

  $public_key_pem = openssl_pkey_get_details($private_key)['key'];
  //echo $public_key_pem;
  $public_key = openssl_pkey_get_public("-----BEGIN PUBLIC KEY-----
  MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnCt3RZL9gwTGzjs7oVBDOiUdT
  X4J4XQYXtiJnO+1w6PefXEh4vToHmBftvn+WNQoAHibi6PWoxMEBh142ueWMW4po
  u4Ls+dS/9ElnMXl0rJbrJFGY3KQ1Glu5ijRqybb8FqTw7mtGDfdNO/Dajd9ue9hd
  OjyToj/+PO8ComDbDQIDAQAB
  -----END PUBLIC KEY-----"); */

class _rsaCryptGeneral {

    private static $instance = null;

    public static function getInstance() {

        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private $strPrKey = "-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQCnCt3RZL9gwTGzjs7oVBDOiUdTX4J4XQYXtiJnO+1w6PefXEh4
vToHmBftvn+WNQoAHibi6PWoxMEBh142ueWMW4pou4Ls+dS/9ElnMXl0rJbrJFGY
3KQ1Glu5ijRqybb8FqTw7mtGDfdNO/Dajd9ue9hdOjyToj/+PO8ComDbDQIDAQAB
AoGAZm1ZWt6GI1QTn+C/quJxc9Plso/sNtYDuGJschTjIcsYm9VAcxjFDocKlTOs
lHRtb2kNuzHSAOy01LUm5jPTNuGmq2y6l1+2zyqzq1awvgvozJJo2SINroMwhUXA
LmQvX+Fp7F3Q7ZoFGTxJrb4TNmAs74J3sZl5L1tf+6HxIgECQQDabIkglLmXzlpm
FSgjD8JVv6TiE5kXSsaURkWyc5ACSPRaNs/ki9WOqXDC7HEqmLwtVv0xwNF6eqvR
1D7/1TxNAkEAw8d4tvXEoCXSkYluRXCPKGiA1ULuxNwpOKOPWS1q7PPsInJY/2RD
+ZxmZTMUs5m85eu/7IKCtjHXqFqDNJZ5wQJAXUQbCZ534Sprz0sZaF9CS/sZHK/h
nuB/CrE28YwG4fLk4+CjxKMw/Um8rH7pk5bEQ1fxpV0AFZxr4Z0PiY4zwQJAWSsL
vwD2+h3f5utaLwRSH4xhSUB/8Wd42tQb4Pj/n0aFgTbldASdtR1XwbXfMuHNmEak
ljrm/8Z5u7Ll2VjDwQJBAMORrw+1gFzDI5JZiWbQ2gOAUwGadWXFY+6E6py03+jl
tFE3lNAVVqmI6zfHsoxYXfSDMM4POIhqcny5+SVuMOA=
-----END RSA PRIVATE KEY-----";
    private $strPuKey = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCnCt3RZL9gwTGzjs7oVBDOiUdT
X4J4XQYXtiJnO+1w6PefXEh4vToHmBftvn+WNQoAHibi6PWoxMEBh142ueWMW4po
u4Ls+dS/9ElnMXl0rJbrJFGY3KQ1Glu5ijRqybb8FqTw7mtGDfdNO/Dajd9ue9hd
OjyToj/+PO8ComDbDQIDAQAB
-----END PUBLIC KEY-----";

    public function encryptRSA_general($plainText) {
        $public_key = openssl_pkey_get_public($this->strPuKey);
        if (openssl_public_encrypt($plainText, $crypted, $public_key, OPENSSL_PKCS1_PADDING)) {
            return base64_encode($crypted);
        } else {
            return $plainText;
        }
    }

    public function decryptRSA_general($crypted) {
        $private_key = openssl_get_privatekey($this->strPrKey);
        if (openssl_private_decrypt(base64_decode($crypted), $decrypted, $private_key)) {
            return $decrypted;
        } else {
            return $crypted;
        }
    }

}

/*$nene= _rsaCryptGeneral::getInstance()->encryptRSA_general("admina");
$a = "IRHsfegf31YW+d/7MsYGt7jr3eyE5fJ098c7p0aeWSpZe5fncx6CGQocYkTkhmsnAiXQgXBBm+AhnW/TSEGNQmdRRqDQd0qdTvIAXssMTJhdv1VKsdFFCdCd+2a6QkZiEfrfzZYqR0gPLycha/jVgBrhHB/GJtfg4eBr1GflRO8=";
echo $nene ."<br>";
echo _rsaCryptGeneral::getInstance()->decryptRSA_general($nene);*/

