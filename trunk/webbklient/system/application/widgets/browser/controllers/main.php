<?php

require_once dirname(__FILE__).'/../classes/HttpFetcher.php';

class Main extends Controller {
    
    function __construct()
    {
        parent::Controller();    
    }
    
    function get()
    {
  
        $url = $_POST['browserURL'];
        $base_url = 'proxy.php?url=';
            
        // ------------------------------------------
        
        $hf = new HttpFetcher();
        
        $urlRAW = "";
        $host = "";
        if (isset($_POST['proxy_search_host']))
        {
            // search with method get
            $host = $_POST['proxy_search_host'];
            $search_word = $_GET['s'];
            $urlRAW = $host."?s=$search_word";
        }
        else
        { 
            //Get the host
            $urlRAW = urldecode($url); 
            $host = getHost($urlRAW);
        }     
        
        //Strip the host
        $strip_http = array("http://", "www.");
        $strippedhost = str_replace($strip_http, "", $host);
     
        // get file
        $htmlData = $hf->get( $urlRAW );
          
          //Get the DOM
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML($htmlData);
        
        $xpath = new DOMXPath($dom);

        $tags = $xpath->query('//a[@href]');
        
        foreach ($tags as $tag)
        {
                    

            $tag_url = trim($tag->getAttribute('href'));
            
            
            if (stripos($tag_url, $strippedhost)===false)
            {  
                $attribute = "href";
                
                //$htmlData = quoteData($htmlData, "href", $tag_url, false, $strippedhost, $base_url);
                $htmlData = str_replace(
                            $attribute . "=" . "\"$tag_url\"", 
                            $attribute . "=" . "\"" . $base_url . $host . $tag_url . "\"", 
                            $htmlData);      
                //Replace singlequoted
                $htmlData = str_replace(
                        $attribute . "=" . "'$tag_url'", 
                        $attribute . "=" . "\"" . $base_url . $host . $tag_url . "\"", 
                        $htmlData);
            }
            else
            {       
                $attribute = "href";
                
                //$htmlData = quoteData($htmlData, "href", $tag_url, false, $strippedhost, $base_url);
                $htmlData = str_replace(
                            $attribute . "=" . "\"$tag_url\"", 
                            $attribute . "=" . "\"" . $base_url . $tag_url . "\"", 
                            $htmlData);      
                //Replace singlequoted
                $htmlData = str_replace(
                        $attribute . "=" . "'$tag_url'", 
                        $attribute . "=" . "\"" . $base_url . $tag_url . "\"", 
                        $htmlData);
                
            }
            
        }
        
        $tags = $xpath->query('//link[@href]');

        foreach ($tags as $tag)
        {
            

            $tag_url = trim($tag->getAttribute('href'));
            
                if (stripos($tag_url, $strippedhost)===false) {
         
                    //Check if url contains www or http (external url)
                    if((stripos($tag_url, "http://")=== false) && (stripos($tag_url, "www")=== false))                 {
                        //Internal url, we need a host infront of it.
                        //$htmlData = quoteData($htmlData, "href", $tag_url, true, $strippedhost, null);
                        //$htmlData = quoteData($htmlData, "href", $tag_url, false, $strippedhost, $base_url);
                        $attribute = "href";
                        
                        $htmlData = str_replace(
                                    $attribute . "=" . "\"$tag_url\"", 
                                    $attribute . "=" . "\"" . $strippedhost . $tag_url . "\"", 
                                    $htmlData);      
                        //Replace singlequoted
                        $htmlData = str_replace(
                                $attribute . "=" . "'$tag_url'", 
                                $attribute . "=" . "\"" . $strippedhost . $tag_url . "\"", 
                                $htmlData);
                    } else {
                        //External url for stylesheet or so, really, do nothing.
                    }
                    
               } 
               
               else {
                   //Do nothing! It's a URL that should not be proxified.
               }
                
        }
          
          
        $tags = $xpath->query('//img[@src]');

        foreach ($tags as $tag)
        {
            
            
            $tag_url = trim($tag->getAttribute('src'));
            
                if (stripos($tag_url, $strippedhost)===false) {
                    //Check if url contains www or http (external url)
                    if((stripos($tag_url, "http://")=== false) && (stripos($tag_url, "www")=== false)) {
                        //Internal url, we need a host infront of it.
                        //$htmlData = quoteData($htmlData, "src", $tag_url, true, $strippedhost, null);
                         $attribute = "src";
                        
                        $htmlData = str_replace(
                                    $attribute . "=" . "\"$tag_url\"", 
                                    $attribute . "=" . "\"" . "http://" . $strippedhost . $tag_url . "\"", 
                                    $htmlData);      
                        //Replace singlequoted
                        $htmlData = str_replace(
                                $attribute . "=" . "'$tag_url'", 
                                $attribute . "=" . "\"" . "http://" . $strippedhost . $tag_url . "\"", 
                                $htmlData);
                    } else {
                        //External url for stylesheet or so, really, do nothing.
                    }
                    
               } 
               
               else {
                   //Do nothing! It's a URL that should not be proxified.
               }
                
        }
        
        $tags = $xpath->query('//form[@action]');
        
        foreach ($tags as $tag)
        {
                    

            $tag_url = trim($tag->getAttribute('action'));
            
           
            if (stripos($tag_url, $strippedhost)===false)
            {  
                $attribute = "action";
                
                //$htmlData = quoteData($htmlData, "href", $tag_url, false, $strippedhost, $base_url);
                $htmlData = str_replace(
                            $attribute . "=" . "\"$tag_url\"", 
                            $attribute . "=" . "\"" . $base_url . $host . $tag_url . "\"", 
                            $htmlData);      
                //Replace singlequoted
                $htmlData = str_replace(
                        $attribute . "=" . "'$tag_url'", 
                        $attribute . "=" . "\"" . $base_url . $host . $tag_url . "\"", 
                        $htmlData);
                        
                // inject hidden fields (works in wordpress)
                $hidden_data = '<input type="hidden" id="proxy_search_host" name="proxy_search_host" value="'.$host.'" />';
                $htmlData = preg_replace('/<\/form>/i', $hidden_data.'</form>', $htmlData);

            }
            else
            {       
                $attribute = "action";
                
                //$htmlData = quoteData($htmlData, "href", $tag_url, false, $strippedhost, $base_url);
                $htmlData = str_replace(
                            $attribute . "=" . "\"$tag_url\"", 
                            $attribute . "=" . "\"" . $base_url . $tag_url . "\"", 
                            $htmlData);      
                //Replace singlequoted
                $htmlData = str_replace(
                        $attribute . "=" . "'$tag_url'", 
                        $attribute . "=" . "\"" . $base_url . $tag_url . "\"", 
                        $htmlData);
                        
                // inject hidden fields (works in wordpress)
                $hidden_data = '<input type="hidden" id="proxy_search_host" name="proxy_search_host" value="'.$host.'" />';
                $htmlData = preg_replace('/<\/form>/i', $hidden_data.'</form>', $htmlData);
            }
            
        }
        
       echo $htmlData;
        
        
    }
  
  
}
