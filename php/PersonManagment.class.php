<?php
/**
 * Created by PhpStorm.
 * User: Bartosz
 * Date: 12.06.2018
 * Time: 17:28
 */

/**
 * Class PersonManagment
 * Class for managing Person and Parson_data in DB, retriving and filtering Person objects
 */
class PersonManagment extends Db_Conn {

	/**
	 * @var Person[]
	 */
	private $persons = [];


	public function __construct() {
		parent::__construct();
	}

	/**
	 * @return Person[]
	 */
	public function GetPersons(): array {
		return $this->persons;
	}

	/**
	 * @return array
	 * Converts previously aqquired Person objects into array
	 */
	public function GetPersonAsArray() : array {
		$result = [];
		foreach ($this->persons as $person){
			$result[] = $person->toArray();
		}
		return $result;
	}

	/**
	 * @param string $value
	 * Returns required value form fileds in Person or PersonData object
	 * @return array
	 */
	public function ListPersonValue(string $value){
		$result = [];
		foreach($this->persons as $person){
			$var = $person->$value;
			if(!empty($var))
				$result[] = $var;
		}
		return $result;
	}


	/**
	 * @param $filters
	 *  Select Persons from DB, sort them by selected value and Filter result;
	 * @return void;
	 */
	public function SelectAll($filters){

		$this->persons = [];
		try {
			$stmt =  self::$CONN->prepare(
				"SELECT 
						person.*,
						person_data.person_id, person_data.color, person_data.website  
						FROM person 
						LEFT JOIN person_data ON person.id = person_data.person_id ".$this->OrderByQuery()
			);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if($result){
				foreach ($result as $personTab){

					if(Person::Filter($personTab, $filters ) ){
						$this->persons[] = new Person($personTab);
					}


				}
			}
		} catch (PDOException $e){
			echo $e->getMessage();
		}

	}

	/**
	 * @param $id
	 * Select single Person object from DB
	 * @return null|Person
	 */
	public function SelectPerson($id){
		try {
			$stmt =  self::$CONN->prepare(
				"SELECT 
						person.*,
						person_data.person_id, person_data.color, person_data.website  
						FROM person 
						LEFT JOIN person_data ON person.id = person_data.person_id 
						WHERE person.id = :id"
			);
			$stmt->bindParam(':id', $id);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			if($result){
				return new Person($result);

			}
		} catch (PDOException $e){
			echo $e->getMessage();
		}
		return null;
	}

	/**
	 * @return string
	 * Create ORDER query corresponding to $_GET values
	 */
	private function OrderByQuery() : string{
		$query = "";
		if(isset($_GET['sortBy'], $_GET['sortType'])){
			$query = " ORDER BY ".$_GET['sortBy']." ".$_GET['sortType'];
		}
		return $query;
	}

	/**
	 * @param $id
	 * Dalating person from DB
	 * @return bool
	 */
	public function DeletePerson($id) : bool {
		if(is_numeric($id)){
			try {
				$rowsAffected = 0;

				$stmt = self::$CONN->prepare("DELETE FROM  person WHERE id = $id");
				$stmt->execute();
				$rowsAffected += $stmt->rowCount();

				$stmt = self::$CONN->prepare("DELETE FROM  person_data WHERE person_id = $id");
				$stmt->execute();
				$rowsAffected += $stmt->rowCount();

				return $rowsAffected >= 2;
			} catch (PDOException $e){
				echo $e->getMessage();
			}
		}
		return false;
	}

	/**
	 * @param $data
	 * Creating new Person with PersonData in DB
	 * @return bool
	 */
	public function AddPerson($data) : bool {

		try {
			$stmt = self::$CONN->prepare("INSERT INTO person ( name, address, birth_date )
    		 VALUES ( :name, :address, :birth_date)");;
			$stmt->bindParam(':name', $data['name']);
			$stmt->bindParam(':address', $data['address']);
			$stmt->bindParam(':birth_date', $data['birth_date']);
			$stmt->execute();
			$person_id = self::$CONN->lastInsertId();

			$stmt = self::$CONN->prepare("INSERT INTO person_data ( person_id, color, website )
    		 VALUES ( :person_id, :color, :website)");;
			$stmt->bindParam(':person_id', $person_id);
			$stmt->bindParam(':color', $data['color']);
			$stmt->bindParam(':website', $data['website']);
			$stmt->execute();
			return true;
		} catch (PDOException $e){
			echo $e->getMessage();
		}
		return false;
	}

	/**
	 * @param $id
	 * @param $d
	 * Edit person - wont overwrite fileds not listed in array $d
	 * @return bool
	 */
	public function EditPerson($id, $d) : bool {

		$person = $this->SelectPerson($id);
		if($person!=null && $person instanceof Person){
			$person->Update($d);
			try {
				$rowAffected = 0;

				$stmt =  self::$CONN->prepare("UPDATE person SET name=:name,address=:address,birth_date=:birth_date WHERE id=:id");
				$stmt->bindParam(':name',$person->name);
				$stmt->bindParam(':address',$person->address);
				$stmt->bindParam(':birth_date',$person->birth_date);
				$stmt->bindParam(':id',$id);
				$stmt->execute();
				$rowAffected += $stmt->rowCount();

				$stmt =  self::$CONN->prepare("UPDATE person_data SET color=:color,website=:website WHERE person_id=:id");
				$stmt->bindParam(':color',$person->color);
				$stmt->bindParam(':website',$person->website);
				$stmt->bindParam(':id',$id);
				$stmt->execute();
				$rowAffected += $stmt->rowCount();

				return $rowAffected>=2;
			} catch (PDOException $e){
				echo $e->getMessage();
			}
		}
		return false;
	}
}