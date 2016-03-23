<?php
/**
 * Created by PhpStorm.
 * User: zhangxiao-pc
 * Date: 2016/3/21
 * Time: 16:19
 */

namespace common\models;


use yii\base\Component;

class QddConfig extends Component
{
    private static $api = [
        /**
         *开发环境
         */
        'development' => [
            'payment'  => [
                'pfmmm' => 'p1731',
                //'public_key'  => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC27nmo/5GU2TEbyrXyBfxzeBok5gaEfCzL8V6knFHwgY/aUfqQI8hrfe40gBIohz7L4Ygh9a+0E2BroSvjt4g2cuemj1gsnTPs5H7lWED93jWBvwbb7FseJGx2AgyP4YdmafwMDb+6lNRgB+UVG88WKXjl21sRSNyxWOaaVImwnQIDAQAB',
                //'private_key' => 'MIICXgIBAAKBgQC27nmo/5GU2TEbyrXyBfxzeBok5gaEfCzL8V6knFHwgY/aUfqQI8hrfe40gBIohz7L4Ygh9a+0E2BroSvjt4g2cuemj1gsnTPs5H7lWED93jWBvwbb7FseJGx2AgyP4YdmafwMDb+6lNRgB+UVG88WKXjl21sRSNyxWOaaVImwnQIDAQABAoGAVKxXtejt+ub5ezK+OxOYQd5iw5eRhrtvhMrpkuokZ12hN13gy900RMUagESToxzO7VIsUAPH22NoqwkEJrhqHHly2dIbEmzZcnmomS6iU/+ffYI1UNg0SZUwweB3GZinyyUePZHUvI5iPU2xFULZ6V+5p8pkTRUMJN3iyk6RrSUCQQDenJf2bqwlQ6FfO6lfylCitZRsLyfm4E9E/JZbB2dW1dFt99bzokwzBz6QVJZhD1OFe6n+h0qk2iEx67L0xt2bAkEA0l5SOoWPwsTqUXIqWQchvjVxRXwnywEM9IgpKdwvn84VHbGQ5LoGFzW4FrBT5Kf6H00QNxdPXjMsSsxrbQ+qJwJBAMSUQfljF+88PreLRvGJBhX5BR1XswPFVxrTdq7h5dafsHZMouu4iVOSdSQdHkKNtzKr1p0mubrDyQZ9XiWbqzsCQQC2TQijMEUhuvV3+SBboEOMitGLLolpcZKhgjFx1h7rNHK2Fb+DhvVqPaI2/zzkRPELGQQ5SZj9AAmdFELv8wg3AkEAp+UFsbWNazd/anjiwIW/HWiBVGM04Ko/bNO9aR1BAwuywDbAiRptKOl436wAOYWsi1f+mCkDOoxhYptLbMiN1w==',
                'public_key'    => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCjH5ZSkauLF00loDh+91mbP3aXSYV1aUBffLJZc7hajs+Q0cvx+GwWNzeCa7WpI4VyxSIsoKdJgIoQwo3M3AnXGQL17bKlOmcV9DyKR9iEXr9tZmJO5EjTXQwx91s+MzLkGk/xmRHXoaCRhBXeVG3S2ZGd89fscOpRHXmVAGBruQIDAQAB',
                'private_key'   => 'MIICXQIBAAKBgQCjH5ZSkauLF00loDh+91mbP3aXSYV1aUBffLJZc7hajs+Q0cvx+GwWNzeCa7WpI4VyxSIsoKdJgIoQwo3M3AnXGQL17bKlOmcV9DyKR9iEXr9tZmJO5EjTXQwx91s+MzLkGk/xmRHXoaCRhBXeVG3S2ZGd89fscOpRHXmVAGBruQIDAQABAoGALm4pPWtVJowFW3ZVXl5NVRElUj6NDDRfLE6z/R4hFTWdAx2ULVWGfnRNCoRZ+sCvlVFjPsyjkRpTMh4OeocPDYN5iVqPpsUGzbqdEXQzOUhEvlA8y7GQ9V71BcU2J2B2dhL24+2Bc0MtkgB9Hxm8y9zaS41knTOwxl/fmAjzlpkCQQDkHggatSPDwIU4gMxR316ffTxrc2obtNzyUtWH5rz04zotzIxCCO3yp318er65PyKQ7LnGTm3IkU/yfZYa7eH/AkEAtw/YVOlXWaUSph8ZlvEVx6EtlzrQRoYy21Wiq2abxsDgRvdLCkYG9gW3F7q/joDQ7F1NsG40Pnvxpr6D5d1CRwJAGZx9kHIR5+Jvkp5zUiXf+8wVwoKcwSuXOuWt76oqQNxaJdY3URqrjHjdj+JAE5BREzBg0zDvBnu6HCGZfsCChQJBAJccG0UsQJCdNKGwNl1ksMfTAmE9iUNN75kiPV8jGh+cgwXRiD34xDI9UX/jBdDKAKu78S9cKQATK8yqoxIR7G0CQQC6G1QOnZzWT3zg2B4TxUL7j1qJHgtM0H99QrYJZMrih5aBTVBZ/WUAF1zD+sgn4ILj2yNqsuzgzfLNUr+JsOeh'
            ],

            'platform' => [
                'base_host' =>  'http://218.4.234.150:88/main/loan', //主机地址
                'register'  =>  'http://218.4.234.150:88/main/loan/toloanregisterbind.action',   // 开户接口地址
                'withdraw'  =>  'http://218.4.234.150:88/main/loan/toloanwithdraws.action',      // 提现接口地址
                'charge'    =>  'http://218.4.234.150:88/main/loan/toloanrecharge.action',       // 充值地址
                'transfer'  =>  'http://218.4.234.150:88/main/loan/loan.action',                 // 转账接口
                'orderquery'=>  'http://218.4.234.150:88/main/loan/loanorderquery.action',       // 对账接口
                'balance'   =>  'http://218.4.234.150:88/main/loan/balancequery.action',         // 余额查询接口
                'authorize' =>  'http://218.4.234.150:88/main/loan/toloanauthorize.action',      // 授权接口
                'audit'     =>  'http://218.4.234.150:88/main/loan/toloantransferaudit.action',  // 审核接口
                'identity'  =>  'http://218.4.234.150:88/main/authentication/identityMatching.action',//姓名匹配
            ]

        ],
        /**
         *线上环境
         */
        'production'  => [
            'payment'  => [
                'pfmmm' => 'p103',
                'public_key'  => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDFyOLDrTYptkzI+t2/3SlHdZkR11aObYIqAix5hUsd/YbxLKqUhR2CAJKcFSRtlcw7df2o/AzqWw+R30VKvXKkUrE5wArhPIHMiahzygfW35JqdvxLCTyIkNO9lUHuFV6GWLOYRp3y+ca1KFXYuzVjFFxImpXqDm2B6UHoSn7bywIDAQAB',
                'private_key' => 'MIICXAIBAAKBgQDFyOLDrTYptkzI+t2/3SlHdZkR11aObYIqAix5hUsd/YbxLKqUhR2CAJKcFSRtlcw7df2o/AzqWw+R30VKvXKkUrE5wArhPIHMiahzygfW35JqdvxLCTyIkNO9lUHuFV6GWLOYRp3y+ca1KFXYuzVjFFxImpXqDm2B6UHoSn7bywIDAQABAoGAMcV3qx4vfxetAvZ+TwXsmVryhhbWZUkRdFjPsFTmrklaZ96BnpZQ8qIKQtTfMeR8XIo4pwmmhmMb6+1vlntOGbIzUKG1gx+StZrez28ErRotTdsEOllvIHRfV0uCPys+EFodd0DYL+HrmLLr7eEw25ff6Gu261jfMVY8CtqxfMECQQD14ljmlrSDs4Uv7vWNSFFzVk58ZbAdT/rS9kxRgYaPqBGOwfN01MRD+bS4gJ71Ri1VV9F27kqMIKnapTHIPe2zAkEAzev0dEZWvY7tivTBBL2YMSYw1hvUo+A6+4Y1zLMjMw0EVvxdQU85mvxWFTuZz2RNBNCtWa8Wqg+Wl2Bn6+09iQJAPiYxHE+ZXvSgRIZc0JIn7EQzYGP/iNkvZ+VTUwKvNV5g2bmSRMSGuzvBfyUbiJltWfXxfuMqOpMC+73ngFqO2wJAKiadcFuhj8W8/A+jnvvMNmtR3dHukejpSeksBA27K80DUWbxE9hKu13hpREBKAGo/k5U3aHIauEr+yqBuzphuQJBANZ79fQByKss//j2kc/tO7HGCtYK3nT95cJOaHQIgiKhiy2l8512xogTFj6cvXGvoGi2vKpZxgxI5m3GhzoODbI=',
            ],
            'platform' => [
                'base_host'  =>  'https://register.moneymoremore.com',
                'register'   =>  'https://register.moneymoremore.com/loan/toloanregisterbind.action',  // 开户接口地址
                'withdraw'   =>  'https://www.moneymoremore.com/loan/toloanwithdraws.action',          // 提现接口地址
                'charge'     =>  'https://recharge.moneymoremore.com/loan/toloanrecharge.action',      // 充值地址
                'transfer'   =>  'https://transfer.moneymoremore.com/loan/loan.action',                // 转账接口
                'orderquery' =>  'https://query.moneymoremore.com/loan/loanorderquery.action',         // 对账接口
                'balance'    =>  'https://query.moneymoremore.com/loan/balancequery.action',           // 余额查询接口
                'authorize'  =>  'https://www.moneymoremore.com/loan/toloanauthorize.action',          // 授权接口
                'audit'      =>  'https://audit.moneymoremore.com/loan/toloantransferaudit.action',    // 审核接口
                'identity'  =>   'https://loan.moneymoremore.com/authentication/identityMatching.action',//姓名匹配
            ]
        ]
    ];

    public static function getConfig(){
        if(defined('yii_debug')){
            return self::$api['development'];
        }else{
            return self::$api['production'];
        }
    }
}