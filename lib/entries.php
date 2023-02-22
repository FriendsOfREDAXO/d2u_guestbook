<?php
/**
 * Redaxo D2U Guestbook Addon.
 * @author Tobias Krais
 * @author <a href="http://www.design-to-use.de">www.design-to-use.de</a>
 */

namespace D2U_Guestbook;

use rex;
use rex_sql;

/**
 * Guestbook entry.
 */
class Entry
{
    /** @var int Database ID */
    public $id = 0;

    /** @var string name */
    public $name = '';

    /** @var string email address */
    public $email = '';

    /** @var string description */
    public $description = '';

    /** @var int Redaxo clang ID */
    public $clang_id = 1;

    /** @var int rating */
    public $rating = 5;

    /** @var bool recommendation */
    public $recommendation = true;

    /** @var bool Did user accept privacy policy */
    public $privacy_policy_accepted = false;

    /** @var string Online status */
    public $online_status = 'online';

    /** @var string create date */
    public $create_date = '';

    /**
     * Constructor. Reads a contact stored in database.
     * @param int $id entry ID
     */
    public function __construct($id)
    {
        $query = 'SELECT * FROM '. rex::getTablePrefix() .'d2u_guestbook '
                .'WHERE id = '. $id;
        $result = rex_sql::factory();
        $result->setQuery($query);
        $num_rows = $result->getRows();

        if ($num_rows > 0) {
            $this->id = $result->getValue('id');
            $this->clang_id = $result->getValue('clang_id');
            $this->name = stripslashes($result->getValue('name'));
            $this->email = $result->getValue('email');
            $this->rating = $result->getValue('rating');
            $this->recommendation = 1 == $result->getValue('recommendation') ? true : false;
            $this->privacy_policy_accepted = '1' == $result->getValue('privacy_policy_accepted') ? true : false;
            $this->description = stripslashes(htmlspecialchars_decode($result->getValue('description')));
            $this->online_status = $result->getValue('online_status');
            $this->create_date = $result->getValue('create_date');
        }
    }

    /**
     * Changes the status of a property.
     */
    public function changeStatus(): void
    {
        if ('online' === $this->online_status) {
            if ($this->id > 0) {
                $query = 'UPDATE '. rex::getTablePrefix() .'d2u_guestbook '
                    ."SET online_status = 'offline' "
                    .'WHERE id = '. $this->id;
                $result = rex_sql::factory();
                $result->setQuery($query);
            }
            $this->online_status = 'offline';
        } else {
            if ($this->id > 0) {
                $query = 'UPDATE '. rex::getTablePrefix() .'d2u_guestbook '
                    ."SET online_status = 'online' "
                    .'WHERE id = '. $this->id;
                $result = rex_sql::factory();
                $result->setQuery($query);
            }
            $this->online_status = 'online';
        }
    }

    /**
     * Deletes the object.
     */
    public function delete(): void
    {
        $query = 'DELETE FROM '. rex::getTablePrefix() .'d2u_guestbook '
            .'WHERE id = '. $this->id;
        $result = rex_sql::factory();
        $result->setQuery($query);
    }

    /**
     * Get all guestbook entries.
     * @param bool $online_only true if only online objects should be returned
     * @return Entry[] array with Entry objects
     */
    public static function getAll($online_only = false)
    {
        $query = 'SELECT id FROM '. rex::getTablePrefix() .'d2u_guestbook ';
        if ($online_only) {
            $query .= "WHERE online_status = 'online' ";
        }
        $query .= 'ORDER BY create_date DESC';
        $result = rex_sql::factory();
        $result->setQuery($query);

        $entries = [];
        for ($i = 0; $i < $result->getRows(); ++$i) {
            $entries[] = new self($result->getValue('id'));
            $result->next();
        }
        return $entries;
    }

    /**
     * Get average guestbook rating.
     * @return float Average rating
     */
    public static function getRating()
    {
        $query = 'SELECT AVG(rating) AS rating FROM '. rex::getTablePrefix() .'d2u_guestbook '
            ."WHERE online_status = 'online' ";
        $result = rex_sql::factory();
        $result->setQuery($query);

        return (float) $result->getValue('rating');
    }

    /**
     * Get average guestbook rating.
     * @return float Average rating
     */
    public static function getRecommendation()
    {
        $query = 'SELECT COUNT(recommendation) AS recommendation FROM '. rex::getTablePrefix() .'d2u_guestbook '
            .'WHERE recommendation = 1';
        $result = rex_sql::factory();
        $result->setQuery($query);

        return (float) $result->getValue('recommendation');
    }

    /**
     * Updates or inserts the object into database.
     * @return in error code if error occurs
     */
    public function save()
    {
        $error = 0;
        $query = rex::getTablePrefix() .'d2u_guestbook SET '
                .'clang_id = '. $this->clang_id .', '
                ."name = '". addslashes($this->name) ."', "
                ."email = '". $this->email ."', "
                .'rating = '. $this->rating .', '
                .'recommendation = '. ($this->recommendation ? 1 : 0) .', '
                ."privacy_policy_accepted = '". ($this->privacy_policy_accepted ? 'yes' : 'no') ."', "
                ."description = '". addslashes(htmlspecialchars($this->description)) ."', "
                ."online_status = '". $this->online_status ."' ";
        if (0 == $this->id) {
            $query = 'INSERT INTO '. $query . ', create_date = CURRENT_TIMESTAMP';
        } else {
            $query = 'UPDATE '. $query .' WHERE id = '. $this->id;
        }

        $result = rex_sql::factory();
        $result->setQuery($query);
        if (0 == $this->id) {
            $this->id = (int) $result->getLastId();
            $error = $result->hasError();
        }

        return $error;
    }
}
