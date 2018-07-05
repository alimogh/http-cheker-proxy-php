<?php
// php /home/set/PHP/local_proxy/https-cheker-proxy.php
        $start = microtime(true);

        define("__FILE_GOODS__",      __DIR__ . '/goods/https-good.txt');
        define("__FILE_SOURCE__",     __DIR__ . '/source/https.txt');
        define("__FILE_LOG__",        __DIR__ . '/log.txt');

        define("__PROXY_PROTOKOL__", 'HTTPS');
        define("__CONNECTTIMEOUT__", 1000); // CONNECTTIMEOUT_MS


        function get_time($V = 'time_d') {

          date_default_timezone_set("UTC");
          $time = time();
          $offset = 3;
          $time += 3 * 3600;

              if ($V == 'time_d') {
                return date("H:i Y-m-d", $time);
              }
              if ($V == 'time') {
                return date("H:i:s", $time);
              }
              if ($V == 'date') {
                return date("Y-m-d", $time);
              }
        }

        $array_LOG[] = '====================================='. "\r\n";
        $array_LOG[] = 'Старт: ' . get_time('time') . "\r\n";
        $array_LOG[] = 'Запуск проверки ' . __PROXY_PROTOKOL__ . ' Proxy' . "\r\n";

        $fp = fopen(__FILE_GOODS__, "w+");

        $HTTP_PROXY_GET_FILE = file(__FILE_SOURCE__);

        $count = 1;
        $count_proxy = 0;
        $count_error = 0;

        echo "\r\n";
        echo 'Загружено Proxy: ' . count($HTTP_PROXY_GET_FILE);
        echo "\r\n";
        echo "\r\n";

        foreach ($HTTP_PROXY_GET_FILE as $value) {

            $ch = curl_init();
              curl_setopt($ch, CURLOPT_URL,'http://www.proxy-listen.de/azenv.php');
              curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
              curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
              curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36');
              curl_setopt($ch, CURLOPT_PROXY, $value);
              curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,2);
              curl_setopt($ch, CURLOPT_TIMEOUT,7);
              curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, __CONNECTTIMEOUT__);

              $out = curl_exec($ch);
              $info = curl_getinfo($ch);

                  if ($info['http_code'] == 200) {

                    $count_proxy = $count_proxy + 1;

                      $fp = fopen(__FILE_GOODS__, "a");
                      $mytext = $value;
                      $test = fwrite($fp, $mytext);


                      echo '[+] Good: ' . $value;
                  }

                  if ($info['http_code'] != 200) {
                    $count_error = $count_error + 1;
                    echo '[-] Error: ' . $value;
                  }
            $count = $count + 1;
        } // END foreach

        fclose($fp);
        curl_close($ch);
        unset($HTTP_PROXY_GET_FILE);

        echo "\r\n";
        echo "\r\n";
        echo "\r\n";

        $time = microtime(true) - $start;
        $seconds=$time;
        $h=floor($seconds/3600);
        $m=floor(($seconds%3600)/60);
        $s=($seconds%3600)%60;

        $array_LOG[] = 'Рабочих прокси  - ' . $count_proxy . "\n";
        $array_LOG[] = 'Не рабочих прокси  - ' . $count_error . "\n";
        $array_LOG[] = "Время выполнения: ".$m." мин. ".$s." сек." . "\n";
        $array_LOG[] = 'Стоп: ' . get_time('time') . "\r\n";
        $array_LOG[] = '=====================================' . "\r" . "\r";


        /*
        * Обновить файл лога
        */
        foreach ($array_LOG as $value_log) {
          $fp = fopen(__FILE_LOG__, "a");
            $mytext = $value_log;
            $test = fwrite($fp, $mytext);
          fclose($fp);
        }


        foreach ($array_LOG as $key => $value) {
          echo $value;
        }

    echo "\r\n";
    echo "\r\n";
    echo "\r\n";

?>
