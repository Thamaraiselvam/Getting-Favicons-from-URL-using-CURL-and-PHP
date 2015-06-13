<?php

    //process all site data
   
                $site      = 'https://www.dropbox.com/';
                echo "<br>$site ";
                $url       = $site . "favicon.ico";//checking way-1
                $ch        = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_COOKIEJAR, '-');
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                $content     = curl_exec($ch);
                $httpCode    = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $totalsize   = strlen($httpCode);
                $stats       = curl_getinfo($ch);
                $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                $size1       = $stats['size_download'];
                $size2       = $stats['download_content_length'];
                if ($size1 > 1 && $size1 > $size2) {
                    $downloadSize = $size1;
                    $compression  = $totalsize / $size1;
                } elseif ($size2 > 1) {
                    $downloadSize = $size2;
                    $compression  = $totalsize / $size2;
                } else {
                    $compression  = 0;
                    $downloadSize = $totalsize;
                }
                //echo $url;
             //   echo $httpCode . "<br>" . $downloadSize . "<br>" . $size1 . "<br>" . $contentType;
                $verifiy_image = 0;
                if (strstr($contentType, "image") !== false || strstr($contentType, "text/plain") !== false) {
                    $verifiy_image = 1;
                }
                if ($httpCode == 200 && $httpCode != 304 && $downloadSize != 0 && $size1 != 0 && $verifiy_image == 1) {
                    echo "<img src=" . $url . ">";
                  
                    $verifiy_image = 0;
                } else {//starts way -2
//                    echo "<hr>";
//                    echo "Stage-1";
                  //  echo $site;
                    $url = $site;
                    $ch  = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_COOKIEJAR, '-');
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                    $content  = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                   // echo $httpCode;
                    $text = $content;
                    if (strpos($content, "icon") !== false || strpos($content, "shortcut icon") !== false) {
                        if ($httpCode == 200) {
                           // echo "Stage-2";
                            $out = substr(strstr($text, 'icon"'), strlen('icon"'));//cutting extra condent
                            $out = substr($out, 0, strpos($out, ">"));//cutting upto that link
                            if (!empty($out)) {
                                $newdata = preg_match('/href\s*=\s*["\"]?\s*([^"\">]+?)\s*["\"]?"/i', $out, $match);//getting exact href using regex
                                if (!empty($match)) {
                                   // echo "Stage-3";
                                    $info = parse_url($match[1]);
                                    if (!empty($info['scheme']) && !empty($info['host']) && !empty($info['path'])) {
                                        if (strstr($info['path'], "~") !== false) {
                                	   $path=$info['path'];
                                            if($path[0]=='/'){
                                            $info['host'] = substr($info['host'], 0, strpos($info['host'], '/~'));
                                            }
                                            else{
                                           	$info['host'] = substr($info['host'], 0, strpos($info['host'], '~'));
                                            }
                                    
                                           
                                        }
                                        echo '<img src=' . $info['scheme'] . '://' . $info['host'] . '' . $info['path'] . '>';
                                        $imgData = $info['scheme'] . '://' . $info['host'] . '' . $info['path'];//preparing url
                                        $check   = curl_init($imgData);
                                        curl_setopt($check, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($check, CURLOPT_BINARYTRANSFER, true);
                                        curl_setopt($check, CURLOPT_SSL_VERIFYPEER, FALSE);
                                        curl_setopt($check, CURLOPT_HEADER, true);
                                        curl_setopt($check, CURLOPT_COOKIEJAR, '-');
                                        curl_setopt($check, CURLOPT_CONNECTTIMEOUT, 10);
                                        curl_setopt($check, CURLOPT_TIMEOUT, 10);
                                        curl_setopt($check, CURLOPT_FOLLOWLOCATION, true);
                                        curl_setopt($check, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                                        $imageCheck  = curl_exec($check);
                                        $httpCode    = curl_getinfo($check, CURLINFO_HTTP_CODE);
                                        $contentType = curl_getinfo($check, CURLINFO_CONTENT_TYPE);
                                        if ($httpCode == 200 && $httpCode != 304 && strstr($contentType, "image") != null) {
                                            
                                        //    echo "goes from 1";
                                        
                                        } else {
                                            $nofavicon = 1;
                                        }
                                    } else if (empty($info['scheme']) && empty($info['host'])) {
                                        if (strstr($info['path'], "~") !== false) {
                                	    $path=$info['path'];
                                            if($path[0]=='/'){
                                                 $url = substr($url, 0, strpos($url, '/~'));
                                            }
                                            else{
                                           	      $url = substr($url, 0, strpos($url, '~'));
                                            }
                                            
                                      
                                        }
                                        echo "<img src=" . $url . "" . $info['path'] . ">";
                                        $imgData = $url . "" . $info['path'];
                                       // echo $imgData;
                                        $check = curl_init($imgData);
                                        curl_setopt($check, CURLOPT_RETURNTRANSFER, true);
                                        curl_setopt($check, CURLOPT_BINARYTRANSFER, true);
                                        curl_setopt($check, CURLOPT_SSL_VERIFYPEER, FALSE);
                                        curl_setopt($check, CURLOPT_HEADER, true);
                                        curl_setopt($check, CURLOPT_COOKIEJAR, '-');
                                        curl_setopt($check, CURLOPT_CONNECTTIMEOUT, 10);
                                        curl_setopt($check, CURLOPT_TIMEOUT, 10);
                                        curl_setopt($check, CURLOPT_FOLLOWLOCATION, true);
                                        curl_setopt($check, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                                        $imageCheck  = curl_exec($check);
                                        $httpCode    = curl_getinfo($check, CURLINFO_HTTP_CODE);
                                        $contentType = curl_getinfo($check, CURLINFO_CONTENT_TYPE);
                                        if ($httpCode == 200 && $httpCode != 304 && strstr($contentType, "image") != null) {
                                        
                                           // echo "goes from 2";
                                        
                                        } else {
                                            $nofavicon = 1;
                                        }
                                    } else {
                                        $nofavicon = 1;
                                    }
                                } else {
                                    $out   = substr($content, 0, strpos($content, 'icon"'));
                                    $data1 = strrpos($out, "link");
                                    $data2 = strlen($out);
                                    $data2 = $data2 - $data1;
                                    $out   = substr($out, $data1, $data2);
                                    if (!empty($out)) {
                                        $newdata = preg_match('/href\s*=\s*["\"]?\s*([^"\">]+?)\s*["\"]?"/i', $out, $match);
                                        if (!empty($match)) {
                                            //echo "Stage-3 of else ";
                                            $info = parse_url($match[1]);
                                           // print_r($info);
                                            if (!empty($info['scheme']) && !empty($info['host']) && !empty($info['path'])) {
                                                if (strstr($info['path'], "~") !== false) {
                                                    $path=$info['path'];
                                                    if($path[0]=='/'){
                                                    $info['host'] = substr($info['host'], 0, strpos($info['host'], '/~'));
                                                    }
                                                    else{
                                                   	$info['host'] = substr($info['host'], 0, strpos($info['host'], '~'));
                                                    }
                                                    
                                                }
                                                $imgData = $info['scheme'] . "://" . $info['host'] . "" . $info['path'];
                                                echo "<img src='$imgData'>";
                                                $check = curl_init($imgData);
                                                curl_setopt($check, CURLOPT_RETURNTRANSFER, true);
                                                curl_setopt($check, CURLOPT_BINARYTRANSFER, true);
                                                curl_setopt($check, CURLOPT_SSL_VERIFYPEER, FALSE);
                                                curl_setopt($check, CURLOPT_HEADER, true);
                                                curl_setopt($check, CURLOPT_COOKIEJAR, '-');
                                                curl_setopt($check, CURLOPT_CONNECTTIMEOUT, 10);
                                                curl_setopt($check, CURLOPT_TIMEOUT, 10);
                                                curl_setopt($check, CURLOPT_FOLLOWLOCATION, true);
                                                curl_setopt($check, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                                                $imageCheck  = curl_exec($check);
                                                $httpCode    = curl_getinfo($check, CURLINFO_HTTP_CODE);
                                                $contentType = curl_getinfo($check, CURLINFO_CONTENT_TYPE);
                                                if ($httpCode == 200 && $httpCode != 304 && strstr($contentType, "image") != null) {
                                                   
                                                    //echo "goes from 3";
                                             
                                                } else {
                                                    $nofavicon = 1;
                                                }
                                            } else if (empty($info['scheme']) && empty($info['host'])) {
                                                if (strstr($info['path'], "~") !== false) {
                                                      $path=$info['path'];
	                                            if($path[0]=='/'){
	                                                 $url = substr($url, 0, strpos($url, '/~'));
	                                            }
	                                            else{
	                                           	      $url = substr($url, 0, strpos($url, '~'));
	                                            }
                                                }
                                                echo "<img src=" . $url . "" . $info['path'] . ">";
                                                $imgData = $url . "" . $info['path'];
                                                $check   = curl_init($imgData);
                                                curl_setopt($check, CURLOPT_RETURNTRANSFER, true);
                                                curl_setopt($check, CURLOPT_BINARYTRANSFER, true);
                                                curl_setopt($check, CURLOPT_SSL_VERIFYPEER, FALSE);
                                                curl_setopt($check, CURLOPT_HEADER, true);
                                                curl_setopt($check, CURLOPT_COOKIEJAR, '-');
                                                curl_setopt($check, CURLOPT_CONNECTTIMEOUT, 10);
                                                curl_setopt($check, CURLOPT_TIMEOUT, 10);
                                                curl_setopt($check, CURLOPT_FOLLOWLOCATION, true);
                                                curl_setopt($check, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                                                $imageCheck  = curl_exec($check);
                                                $httpCode    = curl_getinfo($check, CURLINFO_HTTP_CODE);
                                                $contentType = curl_getinfo($check, CURLINFO_CONTENT_TYPE);
                                                if ($httpCode == 200 && $httpCode != 304 && strstr($contentType, "image") != null) {
                                                    // $imgData = base64_encode(file_get_contents($imgData));
                                                  
                                                    //echo "goes from 4";
                                               
                                                } else {
                                                    $nofavicon = 1;
                                                }
                                            }
                                        } else {
                                            $nofavicon = 1;
                                        }
                                    } else {
                                        $nofavicon = 1;
                                    }
                                }
                            } else {
                                $nofavicon = 1;
                            }
                        } //200 CHECK
                        else {
                            $nofavicon = 1;
                        }
                    } else {
                        $nofavicon = 1;
                    }
                }
                if (isset($nofavicon)) {
                    echo "No Favicon Found";
                   
                }
             
            
        
    


