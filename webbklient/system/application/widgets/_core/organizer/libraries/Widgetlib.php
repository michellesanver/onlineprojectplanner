<?php
 
class Widgetlib
{
    private $_CI = null;
    private $_current_project_id = "";
    
    public function __construct()
    {
        // fetch CI instance and model for library
        $this->_CI = & get_instance();
        $this->_CI->load->model_widget('widget_model', 'Widget_model'); 
        
        // fetch current project id
        $this->_CI->load->library('Project_lib', null, 'Project');
        $this->_CI->load->library('Widgets', null, 'Widgets');
        $this->_CI->load->model('Widgets_model', null, 'Widgets_model');
        $this->_current_project_id = $this->_CI->Project->checkCurrentProject();
        
        // manually read widgets (widgets library has an override if an
        // ajax call has been made; so override and read folders)
        $this->_CI->Widgets->ManualReadWidgets();
    }
    
    /**
     * Save new name for a widget (instance name)
     *
     * @param int $widgetId
     * @param string $widgetName
     * @return bool
     */
    public function saveNewInstanceName($widgetId, $widgetName) {
    
        // use model to save
        return $this->_CI->Widget_model->SaveNewInstanceName($widgetId, $widgetName);
    
    }
    
    public function addWidgetToProject($widget_id)
    {
        $instance_name = $this->_CI->Widgets->GetWidgetIconName($widget_id);
        return $this->_CI->Widgets_model->AddProjectWidget($this->_current_project_id, $widget_id, $instance_name);
    }
    
    public function removeWidgetFromProject($project_widget_id)
    {
        //Check so that the widget actually belongs to the current project.
        
        //Check so that the user is an admin or a general
        
        //Check so that the widget is removable
        
        
        //Delete widget
        $this->_CI->Widgets_model->DeleteProjectWidget($project_widget_id);
    }
    
    public function setSort($widgetid, $sort)
    {
        $this->_CI->Widget_model->setSort($widgetid, $sort);
    }
    
    public function getAvailableWidgets()
    {
        return $this->_CI->Widgets->GetAllIconsAsArrayAllowedToInstance($this->_current_project_id);
    }
    
    public function getProjectIcons()
    {
        return $this->_CI->Widgets->GetProjectIconsAsArray($this->_current_project_id);
    }
    
    public function getProjectIcons_JSON()
    {
        return $this->_CI->Widgets->GetProjectIcons($this->_current_project_id);    
    }
    
    public function addTest($array)
    {
       $this->_CI->Widget_model->testSort($array);
    }

    public function allowedToInstanceProjectWidget($widgetid)
    {
        return $this->_CI->Widgets->AllowedToInstanceProjectWidget($this->_current_project_id, $widgetid);
    }
    
}