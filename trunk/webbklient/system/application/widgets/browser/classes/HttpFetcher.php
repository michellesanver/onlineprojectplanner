<?php
  
     //Function to get the host
    function getHost($Address) { 
           $parseUrl = parse_url(trim($Address));
           return trim(isset($parseUrl['host']) ? $parseUrl['host'] : array_shift(explode('/', $parseUrl['path'], 2))); 
    } 
  
// ------------------------------------------ ----------------------------------------------------------------------------------------
  
class HttpFetcher
{
    
    function get($url='', $port=80)
    {   

        // hämta med curl
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

        $data = curl_exec($ch);
        
        curl_close($ch);
        
        // retunera data 
        return $data;
    }
    
    private function _isRedirect($data)
    {
        // typ 302? ( <?php header("Location: ...")  )
        if ( preg_match('/302\sFound/', $data) )
        {
             // retunera ny data som splittad array
            return $this->_extractLocation($data);
        }
        
        // typ 301 ( = Moved Permanently )
        if ( preg_match('/301\sMoved\sPermanently/', $data) )
        {
            // retunera ny data som splittad array
            return $this->_extractLocation($data);
        }
        
        // typ 305 ( = Use Proxy )
        if ( preg_match('/305\sUse\sProxy/', $data) )
        {
            // retunera ny data som splittad array
            return $this->_extractLocation($data);
        }
        
        // typ 307 ( = Temporary Redirect )
        if ( preg_match('/307\sTemporary\sRedirect/', $data) )
        {
            // retunera ny data som splittad array
            return $this->_extractLocation($data);
        }
        
        // ingen redirect
        return false;
    }
    
    private function _extractLocation($data)
    {
        // hämta ny url
        $start = strrpos($data, 'Location:');
        $newData = substr($data, $start+10, strlen($data)-$start);
        $end = stripos($newData, "\r\n");
        $newData = substr($newData, 0, $end);    
        
        // dela upp url'en
        $newData = parse_url($newData);
        
        // missformad?
        if ( $newData == false ) die('FATAL ERROR: Malformed redirect!');
        
        // fattas sökväg? (ger php-fel annars)
        if (isset($newData['path'])==false) $newData['path'] = "";
        
        // är path = host?
        if (isset($newData['host'])==false || $newData['host']=="")
        {
            $newData['host'] = $newData['path'];
            $newData['path'] = "";
        } 
        
        // retunera
        return $newData;
    }
    
    private function _isMetaRedirect($data)
    {     
        // typ meta redirect?
        if ( preg_match('/<META\sHTTP-EQUIV="REFRESH"/', strtoupper($data)) )
        {
            // hämta ny url 
            $start = strrpos($data, 'URL=');
            $newData = substr($data, $start+4, strlen($data)-$start);
            $end = stripos($newData, '"');
            $newData = substr($newData, 0, $end); 
         
            // dela upp url'en
            $newData = parse_url($newData);
            
            // missformad?
            if ( $newData == false ) die('FATAL ERROR: Malformed redirect!');
            
            // fattas sökväg? (ger php-fel annars)
            if (isset($newData['path'])==false) $newData['path'] = "";
            
            // är path = host?
            if (isset($newData['host'])==false || $newData['host']=="")
            {
                $newData['host'] = $newData['path'];
                $newData['path'] = "";
            }
            
            // retunera ny data som splittad array
            return $newData;
        }
        
        // ingen redirect
        return false;
    }
    
   function post($postarray, $url)
   {
        $postdata = http_build_query($postarray);
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec($ch);

        return $data;
        
        
        /*$errno = "";
        $errstr = "";
        $content_length = strlen($postdata);
        
        $fp = fsockopen($host, $port, $errno, $errstr, 30);
            
        if ( $fp == false )
        {
            echo "open failed with error; ". $errstr;    
            exit(-1);
        }
        
        if ($url == "") $url = "/";
        
        $out = "POST $url HTTP/1.1\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Content-Length: $content_length\r\n";
        $out .= "Connection: Close\r\n\r\n";
        $out .= "$postdata";
          
        fwrite($fp, $out);
        $data = "";
        while (!feof($fp))
        {
            $data .= fgets($fp, 128);    
        }
        
        fclose($fp);
        
        return $data;   */
          
    }
    
    function cookie($cookiesarray, $host, $url='', $port=80)
    {
        $errno = "";
        $errstr = "";
        
        $fp = fsockopen($host, $port, $errno, $errstr, 30);
            
        if ( $fp == false )
        {
            echo "open failed with error; ". $errstr;    
            exit(-1);
        }
        
        if ($url == "") $url = "/";
        $cookiestring = "";        
        foreach($cookiesarray as $name => $value) {
                $cookiestring .= "$name;$value;";
        }
        $out = "GET $url HTTP/1.1\r\n";
        $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out .= "Host: $host\r\n";
        $out .= "Cookie: $cookiestring\r\n";
        $out .= "Connection: Close\r\n\r\n";
        
        fwrite($fp, $out);
        
        $data = "";
        while (!feof($fp))
        {
            $data .= fgets($fp, 128);    
        }
        
        fclose($fp);
        
        return $data;    
   }
    
}
  
?>
