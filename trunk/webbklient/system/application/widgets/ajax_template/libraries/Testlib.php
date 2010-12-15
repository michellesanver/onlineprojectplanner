<?php 

class Testlib {

    function randomFunctionName()
    {
        $returnDATA = "<div id=\"ajax_template_wrapper\" style=\"padding:15px;\">";
        $returnDATA .= "<h1>AJAX template; Mashed up data in library</h1>";
        $returnDATA .= "<p><a href=\"javascript:void(0);\" class=\"small\" onclick=\"ajaxTemplateWidget.loadURL('/some_controller_name');\"><< Back to previous page</a></p>";
        $returnDATA .= "<br style=\"clear:both;\">"; 
        
        
        // fetch CI instance
        $CI = & get_instance();
        
        // load CI library calendar    
        $CI->load->library('calendar');
        
        $returnDATA .= "<div style=\"float:right;width:200px;\">";
        $returnDATA .= $CI->calendar->generate();
        $returnDATA .= "</div>";
        
        // load list of projects
        $CI->load->model('Project_model');
        $projects = $CI->Project_model->getAll();
        
        $returnDATA .= "<div style=\"float:left;width:200px;\">";
        $returnDATA .= "<table cellpadding=\"3\">";
        $returnDATA .= "<tr>";
        $returnDATA .= "<td><strong>Title</strong></td>";
        $returnDATA .= "<td><strong>Created</strong></td>";
        $returnDATA .= "</tr>";
        foreach ($projects as $row)
        {
              $returnDATA .= "<tr>";
              $returnDATA .= "<td>".$row['Title']."</td>";
              $returnDATA .= "<td>".date('Y-m-d', strtotime($row['Created']))."</td>";
              $returnDATA .= "</tr>";
        }
        echo "</table>";
        $returnDATA .= "</div></div>"; 
        
        // return the result
        return $returnDATA;
    }
    
    
    function getUser($userID)
    {
        // fetch CI instance
        $CI = & get_instance();
       
       // fetch all users (no function to get by id)  
       $CI->load->model('User_model');
       $users = $CI->User_model->getAll();  
       
       // get the correct user
       $returnUser = null;
       foreach($users as $row)
       {
            if ((int)$row['User_id'] == (int)$userID)    
            {
                $returnUser = $row;
                break;    
            }
       }
        
        // return the data
        return $returnUser;
    }
}

?>