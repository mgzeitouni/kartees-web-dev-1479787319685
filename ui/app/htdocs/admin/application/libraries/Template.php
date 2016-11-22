<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
		var $template_data = array();
		
		function set($name, $value)
		{
 			$this->template_data[$name] = $value;
		}
	
		function load($template = '', $template2 = FALSE, $view = '' , $view_data = array(), $return = FALSE)
		{               
			$this->CI =& get_instance();
			if($template2 == FALSE){
				$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));			
				return $this->CI->load->view($template, $this->template_data, $return);
			} else {
				if(isset($view_data['page_name'])){
						$this->set('page_name', $view_data['page_name']);
				} else {
						$this->set('page_name', "");
				}
				$this->set('contents', $this->CI->load->view($view, $view_data, TRUE));
				$this->set('contents', $this->CI->load->view($template2, $this->template_data, TRUE));
				return $this->CI->load->view($template, $this->template_data, $return);
			}
			
		}
}

/* End of file Template.php */
/* Location: ./system/application/libraries/Template.php */