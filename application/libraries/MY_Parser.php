<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Parser Class with autonumber
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Parser
 * @author		yrahman
 * @link		http://codeigniter.com/user_guide/libraries/parser.html
 */
class MY_Parser extends CI_Parser{

	var $l_delim = '{';
	var $r_delim = '}';
	var $object;

	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template view,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	public function parse($template, $data, $return = FALSE)
	{
		$CI =& get_instance();
		$template = $CI->load->view($template, $data, TRUE);

		return $this->_parse($template, $data, $return);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a String
	 *
	 * Parses pseudo-variables contained in the specified string,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function parse_string($template, $data, $return = FALSE)
	{
		return $this->_parse($template, $data, $return);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a template
	 *
	 * Parses pseudo-variables contained in the specified template,
	 * replacing them with the data in the second param
	 *
	 * @access	public
	 * @param	string
	 * @param	array
	 * @param	bool
	 * @return	string
	 */
	function _parse($template, $data, $return = FALSE)
	{
		if ($template == '')
		{
			return FALSE;
		}

		foreach ($data as $key => $val)
		{
			if (is_array($val) || is_object($val))
			{
				if(count($val) > 0){
					foreach ($val as $k => $v) {
						if(is_array($v)){
							$template = $this->_parse_pair($key, $val, $template);
							continue(1);
						}else{
							$kk = $key.'.'.$k;
							$template = $this->_parse_single($kk, (string)$v, $template);
						}
					}
				}else{
					$template = $this->_parse_pair($key, $val, $template);	
				}
				// $template = $this->_parse_pair($key, $val, $template);
			}
			else
			{
				$template = $this->_parse_single($key, (string)$val, $template);
			}
		}

		if ($return == FALSE)
		{
			$CI =& get_instance();
			$CI->output->append_output($template);
		}

		return $template;
	}

	// --------------------------------------------------------------------

	/**
	 *  Set the left/right variable delimiters
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function set_delimiters($l = '{', $r = '}')
	{
		$this->l_delim = $l;
		$this->r_delim = $r;
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a single key/value
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	function _parse_single($key, $val, $string)
	{
		return str_replace($this->l_delim.$key.$this->r_delim, $val, $string);
	}

	// --------------------------------------------------------------------

	/**
	 *  Parse a tag pair
	 *
	 * Parses tag pairs:  {some_tag} string... {/some_tag}
	 *
	 * @access	private
	 * @param	string
	 * @param	array
	 * @param	string
	 * @return	string
	 */
	function _parse_pair($variable, $data, $string)
	{
		if (FALSE === ($match = $this->_match_pair($string, $variable)))
		{
			return $string;
		}

		$str = array();
		//$n = 1;
		$CI =& get_instance();
		$n = ($CI->uri->segment(3)!="" && $CI->uri->segment(3) > 0)? (int) $CI->uri->segment(3) + 1:1;
		if($CI->uri->segment(3)=="search" || $CI->uri->segment(2)=="summary_ajax"){
			$n = ($CI->uri->segment(4)!="" && $CI->uri->segment(4) >= 0)? (int) $CI->uri->segment(4) + 1:1;
		}
		if($CI->uri->segment($CI->uri->total_segments()) == "pdf"){
			$n = 1;
		}
		foreach ($match['0'] as $mkey => $mval)
                {
                    	$str[$mkey] = '';
			foreach ($data as $row)
			{
				$row["num"] = $n;
				$temp = $match['1'][$mkey];
				foreach ($row as $key => $val)
				{
					if ( ! is_array($val))
					{
						$temp = $this->_parse_single($key, $val, $temp);
					}
					else
					{
						$temp = $this->_parse_pair($key, $val, $temp);
					}
				}

				$str[$mkey] .= $temp;
				$n++;
			}
		}
		return str_replace($match['0'], $str, $string);
	}

	// --------------------------------------------------------------------

	/**
	 *  Matches a variable pair
	 *
	 * @access	private
	 * @param	string
	 * @param	string
	 * @return	mixed
	 */
	function _match_pair($string, $variable)
	{
		if ( ! preg_match_all("|" . preg_quote($this->l_delim) . $variable . preg_quote($this->r_delim) . "(.+?)". preg_quote($this->l_delim) . '/' . $variable . preg_quote($this->r_delim) . "|s", $string, $match))
		{
			return FALSE;
		}

		return $match;
	}

}
// END Parser Class

/* End of file Parser.php */
/* Location: ./system/libraries/Parser.php */
