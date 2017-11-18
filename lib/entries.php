<?php
/**
 * Redaxo D2U Guestbook Addon
 * @author Tobias Krais
 * @author <a href="http://www.design-to-use.de">www.design-to-use.de</a>
 */

namespace D2u_Guestbook;
/**
 * Guestbook entry
 */
class Entry {
	/**
	 * @var int Database ID
	 */
	var $id = 0;
	
	/**
	 * @var string name
	 */
	var $name = "";
	
	/**
	 * @var string email address
	 */
	var $email = "";
	
	/**
	 * @var string description
	 */
	var $description = "";
	
	/**
	 * @var int Redaxo clang ID
	 */
	var $clang_id = 1;
	
	/**
	 * @var int rating
	 */
	var $rating = 5;
	
	/**
	 * @var boolean recommendation
	 */
	var $recommendation = TRUE;
	
	/**
	 * @var string Online status
	 */
	var $online_status = "online";
	
	/**
	 * @var int create date
	 */
	var $create_date = 0;
	
	/**
	 * Constructor. Reads a contact stored in database.
	 * @param int $id Entry ID.
	 */
	 public function __construct($id) {
		$query = "SELECT * FROM ". \rex::getTablePrefix() ."d2u_guestbook "
				."WHERE id = ". $id;
		$result = \rex_sql::factory();
		$result->setQuery($query);
		$num_rows = $result->getRows();

		if ($num_rows > 0) {
			$this->id = $result->getValue("id");
			$this->clang_id = $result->getValue("clang_id");
			$this->name = $result->getValue("name");
			$this->email = $result->getValue("email");
			$this->rating = $result->getValue("rating");
			$this->recommendation = $result->getValue("recommendation") == 1 ? TRUE : FALSE;
			$this->description = stripslashes(htmlspecialchars_decode($result->getValue("description")));
			$this->online_status = $result->getValue("online_status");
			$this->create_date = $result->getValue("create_date");
		}
	}
	
	/**
	 * Changes the status of a property
	 */
	public function changeStatus() {
		if($this->online_status == "online") {
			if($this->id > 0) {
				$query = "UPDATE ". \rex::getTablePrefix() ."d2u_guestbook "
					."SET online_status = 'offline' "
					."WHERE id = ". $this->id;
				$result = \rex_sql::factory();
				$result->setQuery($query);
			}
			$this->online_status = "offline";
		}
		else {
			if($this->id > 0) {
				$query = "UPDATE ". \rex::getTablePrefix() ."d2u_guestbook "
					."SET online_status = 'online' "
					."WHERE id = ". $this->id;
				$result = \rex_sql::factory();
				$result->setQuery($query);
			}
			$this->online_status = "online";
		}
	}

	/**
	 * Deletes the object.
	 */
	public function delete() {
		$query = "DELETE FROM ". \rex::getTablePrefix() ."d2u_guestbook "
			."WHERE id = ". $this->id;
		$result = \rex_sql::factory();
		$result->setQuery($query);
	}

	/**
	 * Get all guestbook entries.
	 * @param boolean $online_only TRUE if only online objects should be returned
	 * @return Entry[] Array with Entry objects.
	 */
	public static function getAll($online_only = FALSE) {
		$query = "SELECT id FROM ". \rex::getTablePrefix() ."d2u_guestbook ";
		if($online_only) {
			$query .= "WHERE online_status = 'online' ";
		}
		$query .= "ORDER BY create_date DESC";
		$result = \rex_sql::factory();
		$result->setQuery($query);
		
		$entries = [];
		for($i = 0; $i < $result->getRows(); $i++) {
			$entries[] = new Entry($result->getValue("id"));
			$result->next();
		}
		return $entries;
	}
	
	/**
	 * Get average guestbook rating.
	 * @return float Average rating
	 */
	public static function getRating() {
		$query = "SELECT AVG(rating) AS rating FROM ". \rex::getTablePrefix() ."d2u_guestbook "
			."WHERE online_status = 'online' ";
		$result = \rex_sql::factory();
		$result->setQuery($query);
		
		return floatval($result->getValue("rating"));
	}

	/**
	 * Get average guestbook rating.
	 * @return float Average rating
	 */
	public static function getRecommendation() {
		$query = "SELECT COUNT(recommendation) AS recommendation FROM ". \rex::getTablePrefix() ."d2u_guestbook "
			."WHERE recommendation = 1";
		$result = \rex_sql::factory();
		$result->setQuery($query);
		
		return floatval($result->getValue("recommendation"));
	}

	/**
	 * Updates or inserts the object into database.
	 * @return in error code if error occurs
	 */
	public function save() {
		$error = 0;
		$query = \rex::getTablePrefix() ."d2u_guestbook SET "
				."clang_id = ". $this->clang_id .", "
				."name = '". $this->name ."', "
				."email = '". $this->email ."', "
				."rating = ". $this->rating .", "
				."recommendation = ". ($this->recommendation ? 1 : 0) .", "
				."description = '". addslashes(htmlspecialchars($this->description)) ."', "
				."online_status = '". $this->online_status ."' ";
		if($this->id == 0) {
			$query = "INSERT INTO ". $query . ", create_date = ". time();
		}
		else {
			$query = "UPDATE ". $query ." WHERE id = ". $this->id;
		}

		$result = \rex_sql::factory();
		$result->setQuery($query);
		if($this->id == 0) {
			$this->id = $result->getLastId();
			$error = $result->hasError();
		}
		
		return $error;
	}
}