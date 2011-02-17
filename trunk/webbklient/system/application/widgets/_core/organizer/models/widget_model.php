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
    
    
    /**
     * Save new name for a widget (instance name)
     *
     * @param int $widgetId
     * @param string $widgetName
     * @return bool
     */
    function SaveNewInstanceName($widgetId, $widgetName) {
    
        $this->db->where( array('Project_widgets_id' => $widgetId) );
        return $this->db->update('Project_Widgets', array( 'Widget_instance_name' => $widgetName ) );
    }

}

