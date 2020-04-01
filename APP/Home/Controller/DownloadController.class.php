<?php

/**

 * 本程序仅供娱乐开发学习，如有非法用途与本公司无关，一切法律责任自负！

 */

namespace Home\Controller;

use Think\Controller;

class DownloadController extends Controller {

    public function download()

    {

        $config = M('Config')->where(array('name'=>'WEB_SITE_TITLE'))->find();

        $drpath = './Uploads/Scode';

        $imgma = 'downloadcode.png';

        $urel = './Uploads/Scode/' . $imgma;

        if (!file_exists($drpath . '/' . $imgma)) {

            sp_dir_create($drpath);

            vendor("phpqrcode.phpqrcode");

            $phpqrcode = new \QRcode();

            $hurl ="http://".$_SERVER['SERVER_NAME']. U('Download/download');

            $size = "7";

            //$size = "10.10";

            $errorLevel = "L";

            $phpqrcode->png($hurl, $drpath . '/' . $imgma, $errorLevel, $size);



            $phpqrcode->scerweima1($hurl,$urel,$hurl);

        }

        $this->assign('urel',trim($urel,'.'));
        $this->assign('domain',$_SERVER['SERVER_NAME']);
        $this->assign('name',$config['tip']);

        $this->display();

    }



    public function plist()

    {
        $config = M('Config')->where(array('name'=>'WEB_SITE_TITLE'))->find();
        echo '<?xml version="1.0" encoding="UTF-8"?>

<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">

<plist version="1.0">

<dict>

    <key>items</key>

    <array>

        <dict>

            <key>assets</key>

            <array>

                <dict>

                    <key>kind</key>

                    <string>software-package</string>

                    <key>url</key>

                    <string>http://'.$_SERVER['SERVER_NAME'].'/Uploads/App/app.ipa</string>

                </dict>

            </array>

            <key>metadata</key>

            <dict>

                <key>bundle-identifier</key>

                <string>248300@gmail.com</string>

                <key>bundle-version</key>

                <string>1.0</string>

                <key>kind</key>

                <string>software</string>

                <key>title</key>

                <string>'.$config['tip'].'</string>

            </dict>

        </dict>

    </array>

</dict>

</plist>';

    }

}



