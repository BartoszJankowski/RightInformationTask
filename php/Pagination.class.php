<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 19:27
 */

/**
 * Class Pagination
 * Simply takes params to create and display pagination of table
 */
class Pagination {

	private $currentPage;
	private $records;
	private $recordPerSite;

	public function DisplayPagination($currentPage, array $records) {
		$this->currentPage = $currentPage;
		$this->records     = count($records);
		$this->recordPerSite = TableDisplay::DISPLAY_ROW_NUM;

		echo '<div  class="d-flex justify-content-center mt-1">'.$this->CreatePagination().'</div>';
	}

	/**
	 * @return string
	 * Creating pagination html
	 */
	private function CreatePagination() : string{
		$maxPages = ceil($this->records / $this->recordPerSite);

		if($this->currentPage > $maxPages){
			$this->currentPage = $maxPages;
		} else if ($this->currentPage <=0 || is_null($this->currentPage) ){
			$this->currentPage = 1;
		}

		$res = '';
		$position = $this->currentPage - 2;
		$i = 0;


		while($i < 5){
			if($position <= 0){
				$position++;
			} else {
				if($position > $maxPages){
					break;
				}
				$active = $position == $this->currentPage ? 'active':'';
				$res .= '<li class="page-item '.$active.'">
				      <a class="page-link" href="'.$this->CreateHref($position).'"  name="page" >'.$position.'</a>
				    </li>';
				$position++;
				$i++;
			}
		}

		return '<ul class="pagination pagination-sm p-0 m-0 d-flex">'.$res.'</ul> <span>'.$maxPages.' stron ('.$this->records.' rekordów)</span>';

	}

	/**
	 * @param $pageNum
	 * Creating href for properly sorting and filtering purposes
	 * @return string
	 */
	private function CreateHref($pageNum) : string{
		$get = [];
		if(is_array($_GET)){
			foreach ($_GET as $k=>$v){
				if($k != "page" ){
					if(is_array($v)){
						foreach ($v as $key=>$val){
							$get[] = "$k%5B$key%5D=$val"  ;
						}
					} else {
						$get[] =  $k.'='.$v;
					}
				}
			}
		}
		$get[] = "page=$pageNum";

		return "?".implode('&', $get);
	}
}