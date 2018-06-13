<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 17:48
 */

class TableDisplay {

	/**
	 * constant managing rows per page
	 */
	const DISPLAY_ROW_NUM = 15;

	/**
	 * @var Person[]
	 */
	private $persons = [];


	public function __construct(array $persons) {
		$this->persons = $persons;
	}

	/**
	 * @param $pageNr
	 * Print builded table in html
	 */
	public function __invoke( $pageNr ) {
		$pageNr = $pageNr>0 ? $pageNr : 1;
		echo $this->CreateTable($pageNr);
	}


	/**
	 * @param $pageNr
	 *
	 * @return string
	 */
	private function CreateTable($pageNr) : string {

		return "<table id='personTable'>".$this->TableHeader().$this->TableBody($pageNr).$this->TableFooter()."</table>";
	}

	/*
	 * Creates table header with anchors for sorting purposes
	 */
	private function TableHeader() : string{
		return '  <thead>
		    <tr class="gradient-gray">
		      <th scope="col" >L.p.</th>
		      <th scope="col" ><a href="'.$this->GetSortHref('id').'">id</a>'.$this->GetSortIndicator('id').'</th>
		      <th scope="col" ><a href="'.$this->GetSortHref('name').'">name</a>'.$this->GetSortIndicator('name').'</th>
		      <th scope="col"  ><a href="'.$this->GetSortHref('address').'">address</a>'.$this->GetSortIndicator('address').'</th>
		      <th scope="col"  ><a href="'.$this->GetSortHref('birth_date').'">birth date</a>'.$this->GetSortIndicator('birth_date').'</th>
				<th scope="col"  ><a href="'.$this->GetSortHref('color').'">color</a>'.$this->GetSortIndicator('color').'</th>
		      <th scope="col"  ><a href="'.$this->GetSortHref('website').'">website</a>'.$this->GetSortIndicator('website').'</th>
		      <th  scope="col" >edit</th>
		      <th  scope="col" >delete</th>
		    </tr>
		  </thead>';
	}

	/**
	 * @param $pageNr
	 * Takes page number and create corresponding list of rows view.
	 * @return string
	 */
	private function TableBody($pageNr) : string {
		$pageNr += 0;

		$start = ($pageNr-1) * self::DISPLAY_ROW_NUM;
		$end = ($pageNr) * self::DISPLAY_ROW_NUM;
		$body = "";
		if(is_array($this->persons)){
			foreach ($this->persons as $nr => $person){
				if($person instanceof Person){
					if($this->Beetwen($start, $end, $nr))
						$body .= $this->Row($person, $nr+1 );
				}
			}
		}

		return "<tbody> $body </tbody>";
	}

	/*
	 * Creates table footer with current filters
	 */
	private function TableFooter() : string  {

		$tab = $_GET['filter'];

		return "<tfoot><tr><form action=\"\" method=\"get\">
					<th><i class=\"fas fa-filter\"></i></th>
					<th><input type=\"number\" min=\"0\"  name=\"filter[id]\" value=\"".$tab['id']."\" style=\"width:50px;\" /></th>
					<th><input type=\"text\" name=\"filter[name]\" value=\"".$tab['name']."\" style=\"width:100%;\"/></th>
					<th><input type=\"text\" name=\"filter[address]\" value=\"".$tab['address']."\" style=\"width:100%;\"/></th>
					<th><input type=\"text\" name=\"filter[birth_date]\" value=\"".$tab['birth_date']."\" style=\"width:100%;\"/></th>
					<th><input type=\"text\" name=\"filter[color]\" value=\"".$tab['color']."\" style=\"width:100%;\"/></th>
					<th><input type=\"text\" name=\"filter[website]\" value=\"".$tab['website']."\" style=\"width:100%;\" /></th>
					<th><input type=\"submit\"  value=\"Filter\"></th>
					<th></th>
					
					</form></tr>
				</tfoot>";
	}

	/*
	 * Creates Person row <tr> in table
	 */
	private function Row(Person $person, int $row) : string{

		$personData = $person->personData;

		return "<tr>
			<td>$row</td>
			<td>$person->id</td>
			<td>$person->name</td>
			<td>$person->address</td>
			<td>$person->birth_date</td>
			<td style='color:$personData->color'>$personData->color</td>
			<td>$personData->website</td>
			<td><button onclick='LoadModalEdit($person->id)' data-toggle=\"modal\" data-target=\"#modal\"><i class=\"fas fa-wrench\"></i></button></td>
		<td><button onclick='DeletePerson($person->id)'><i class=\"fas fa-trash\"></i></button></td>
		</tr>";
	}

	/**
	 * @param int $from
	 * @param int $to
	 * @param int $val
	 *  Checks $val between up and down bordres
	 * @return bool
	 */
	private function Beetwen(int $from,int $to, int $val) : bool{
		return $val>=$from && $val<$to;
	}

	/**
	 * @param $byName
	 * Builds href for sort anchor in table header
	 * @return string
	 */
	private function GetSortHref($byName){

		$get = [];
		$sortType = 'ASC';
		if(is_array($_GET)){
			foreach ($_GET as $k=>$v){
				if($k != "sortBy" && $k != "sortType" ){
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
		if(isset($_GET['sortBy']) && $_GET['sortBy'] === $byName){
			if($_GET['sortType'] === 'DESC'){
				$sortType = 'ASC';
			} else {
				$sortType = 'DESC';
			}
		}
		$get[] = "sortBy=$byName&sortType=$sortType";

		return "?".implode('&', $get);
	}

	/**
	 * @param $byName
	 * Sort indicator displayed in table header
	 * @return string
	 */
	private function GetSortIndicator($byName) : string{
		$res = '';
		if($_GET['sortBy'] == $byName){
			if($_GET['sortType'] === 'ASC'){
				$res  = '<i class="fas fa-caret-up text-dark"></i>';
			} else {
				$res  = '<i class="fas fa-caret-down text-dark"></i>';
			}
		}
		return $res;
	}

}