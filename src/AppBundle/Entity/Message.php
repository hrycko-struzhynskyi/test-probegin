<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Message
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=255, nullable=false)
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="access_key", type="string", length=255, nullable=false, unique=true)
     */
    private $accessKey;

    /**
     * @var string
     *
     * @ORM\Column(name="destruct_way", type="string", length=50)
     */
    private $destructWay;

    /**
     * @var string
     *
     * @ORM\Column(name="destruct_option", type="string", length=255)
     */
    private $destructOption;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var datetime $created
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    const DESTRUCT_READ = 'read';
    const DESTRUCT_TIME = 'time';


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set accessKey
     *
     * @param string $accessKey
     *
     * @return Message
     */
    public function setAccessKey($accessKey)
    {
        $this->accessKey = $accessKey;

        return $this;
    }

    /**
     * Get accessKey
     *
     * @return string
     */
    public function getAccessKey()
    {
        return $this->accessKey;
    }

    /**
     * Set destructWay
     *
     * @param string $destructWay
     *
     * @return Message
     */
    public function setDestructWay($destructWay)
    {
        if (!in_array($destructWay, array(self::DESTRUCT_READ, self::DESTRUCT_TIME))) {
            throw new \InvalidArgumentException("Invalid destruction way");
        }
        $this->destructWay = $destructWay;

        return $this;
    }

    /**
     * Get destructWay
     *
     * @return string
     */
    public function getDestructWay()
    {
        return $this->destructWay;
    }

    /**
     * Set destructOption
     *
     * @param string $destructOption
     *
     * @return Message
     */
    public function setDestructOption($destructOption)
    {
        $this->destructOption = $destructOption;

        return $this;
    }

    /**
     * Get destructOption
     *
     * @return string
     */
    public function getDestructOption()
    {
        return $this->destructOption;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param datetime $created
     */
    public function setCreated(datetime $created)
    {
        $this->created = $created;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->setCreated(new DateTime("now"));
    }

}

