<?php 
    namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;

/** @MongoDB\Document(db="SymfonyEvents", collection="userevents")
 * MongoDBUnique(fields="id")
 */
class UserEvent 
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $userid;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $eventid;


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userid;
    }

    public function setUserId(string $userid): self
    {
        $this->userid = $userid;

        return $this;
    }

    public function getEventId(): ?string
    {
        return $this->type;
    }

    public function setEventid(?string $eventid): self
    {
        $this->eventid = $eventid;

        return $this;
    }

}