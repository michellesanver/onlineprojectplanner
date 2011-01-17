<?php
  
class Widget_model extends Model
{
    function __construct()
    {
        parent::Model();
    }
    
    
    function setSort($project_widget_id, $sort)
    {
        $this->db->where(array('Project_widgets_id' => $project_widget_id) );
        $this->db->update('Project_Widgets', array('Order' => $sort));
    }
    

}

