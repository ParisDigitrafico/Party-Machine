<?php
namespace App\Helpers;

/**
 * PHP Pagination Class
 * @author admin@catchmyfame.com - http://www.catchmyfame.com
 * @version 3.0.0
 * @date February 6, 2014
 * @copyright (c) admin@catchmyfame.com (www.catchmyfame.com)
 * @license CC Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0) - http://creativecommons.org/licenses/by-sa/3.0/
*/

class Paginator
{
	public $current_page;
	public $items_per_page;
	public $limit_end;
	public $limit_start;
	public $num_pages;
	public $total_items;
	protected $ipp_array;
	protected $limit;
	protected $mid_range;
	protected $querystring;
	protected $return;
	protected $get_ipp;

  protected $class_active_li_a = "active";
  protected $class_li_dots     = "page-item";
  protected $class_li_a        = "page-link";

	public function __construct($total=0,$ipp_array=array(10,25,50,100),$mid_range=3)
  {
		$this->total_items = (int) $total;
		if($this->total_items <= 0) exit("Unable to paginate: Invalid total value (must be an integer > 0)");
		$this->mid_range = (int) $mid_range; // midrange must be an odd int >= 1
		if($this->mid_range%2 == 0 or $this->mid_range < 1) exit("Unable to paginate: Invalid mid_range value (must be an odd integer >= 1)");
		if(!is_array($ipp_array)) exit("Unable to paginate: Invalid ipp_array value");
		$this->ipp_array = $ipp_array;
		$this->items_per_page = (isset($_GET["per_page"])) ? $_GET["per_page"] : $this->ipp_array[0];
		$this->default_ipp = $this->ipp_array[0];

		if($this->items_per_page == "All")
    {
			$this->num_pages = 1;
		}
    else
    {
			if(!is_numeric($this->items_per_page) or $this->items_per_page <= 0) $this->items_per_page = $this->ipp_array[0];
			$this->num_pages = ceil($this->total_items/$this->items_per_page);
		}

		$this->current_page = (isset($_GET["page"])) ? (int) $_GET["page"] : 1; // must be numeric > 0

		if($_GET)
    {
			$args = explode("&",$_SERVER["QUERY_STRING"]);
			foreach($args as $arg)
      {
				$keyval = explode("=",$arg);
				if($keyval[0] != "page" && $keyval[0] != "per_page") $this->querystring .= "&" . $arg;
			}
		}

		if($_POST)
    {
			foreach($_POST as $key=>$val)
      {
				if($key != "page" && $key != "per_page") $this->querystring .= "&$key=$val";
			}
		}

		if($this->num_pages > 6)
    {
			##$this->return = ($this->current_page > 1 && $this->total_items >= 10) ? "<a class=\"LinkPagination navigator\" href=\"?page=".($this->current_page-1)."&ipp=$this->items_per_page$this->querystring\" data-page=\"".($this->current_page-1)."\"> < </a> ":"<span class=\"LinkPagination paginado_selected\" href=\"#\"> < </span> ";
			$this->return = ($this->current_page > 1 && $this->total_items >= 10)? '<li class="'.$this->class_li_dots.'"><a class="'.$this->class_li_a .' navigator" href="?page='.($this->current_page-1).'&per_page='.$this->items_per_page.$this->querystring.'" data-page="'.($this->current_page-1).'"> < </a></li>':'';

      $this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

      if($this->start_range <= 0)
      {
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}

      if($this->end_range > $this->num_pages)
      {
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}

			$this->range = range($this->start_range, $this->end_range);

			for($i=1;$i<=$this->num_pages;$i++)
      {
				if($this->range[0] > 2 && $i == $this->range[0]) $this->return .= '<li class="'.$this->class_li_dots.' disabled"><a class="'.$this->class_li_a .' disabled" href="javascript:void(0);">...</a></li>';
				// loop through all pages. if first, last, or in range, display
				##if($i==1 or $i==$this->num_pages or in_array($i,$this->range)) $this->return .= ($i == $this->current_page && $this->items_per_page != "All") ? "<span class=\"LinkPagination paginado_selected\">$i</span> \n":"<a class=\"LinkPagination navigator\" href=\"?page=$i&ipp=$this->items_per_page$this->querystring\" data-page=\"".$i."\">$i</a> \n";
				if($i==1 or $i==$this->num_pages or in_array($i,$this->range)) $this->return .= ($i == $this->current_page && $this->items_per_page != "All") ? '<li class="'.$this->class_li_dots.' active"><a class="'.$this->class_li_a .' active" href="javascript:void(0);" data-page="'.$i.'" style="cursor: default;">'.$i.'</a></li>': '<li class="'.$this->class_li_dots.'"><a class="'.$this->class_li_a .' navigator" href="?page='.$i.'&per_page='.$this->items_per_page.$this->querystring.'" data-page="'.$i.'" >'.$i.'</a></li>';

        if($this->range[$this->mid_range-1] < $this->num_pages-1 && $i == $this->range[$this->mid_range-1]) $this->return .= '<li class="'.$this->class_li_dots.' disabled"><a class="'.$this->class_li_a .' disabled" href="javascript:void(0);">...</a></li>';
			}

			##$this->return .= (($this->current_page < $this->num_pages && $this->total_items >= 10) && ($this->items_per_page != "All") && $this->current_page > 0) ? "<a class=\"LinkPagination navigator\" href=\"?page=".($this->current_page+1)."&ipp=$this->items_per_page$this->querystring\" data-page=\"".($this->current_page+1)."\"> > </a>\n":"<span class=\"LinkPagination paginado_selected\"> > </span>\n";
			$this->return .= (($this->current_page < $this->num_pages && $this->total_items >= 10) && ($this->items_per_page != "All") && $this->current_page > 0) ? '<li class="'.$this->class_li_dots.'"><a class="'.$this->class_li_a .' navigator" href="?page='.($this->current_page+1).'&per_page='.$this->items_per_page.$this->querystring.'" data-page="'.($this->current_page+1).'"> > </a></li>' :'';
		}
    else
    {
			for($i=1;$i<=$this->num_pages;$i++)
      {
				$this->return .= ($i == $this->current_page) ? '<li class="'.$this->class_li_dots.' active"><a class="'.$this->class_li_a .' active" href="javascript:void(0);" data-page="'.$i.'" style="cursor: default;">'.$i.'</a></li>': '<li class="'.$this->class_li_dots.'"><a class="'.$this->class_li_a .' navigator" href="?page='.$i.'&per_page='.$this->items_per_page.$this->querystring.'" data-page="'.$i.'" >'.$i.'</a></li>';
			}
		}

		$this->return = str_replace("&","&amp;",$this->return);
		$this->limit_start = ($this->current_page <= 0) ? 0:($this->current_page-1) * $this->items_per_page;
		if($this->current_page <= 0) $this->items_per_page = 0;
		$this->limit_end = ($this->items_per_page == "All") ? (int) $this->total_items: (int) $this->items_per_page;
	}

  public function display_pages()
  {
		return $this->return;
	}
}
?>