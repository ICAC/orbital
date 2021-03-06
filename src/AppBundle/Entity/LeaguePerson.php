<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\LeaguePersonRepository")
 * @ORM\Table(name="league_person")
 */
class LeaguePerson
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="League", inversedBy="people")
     */
    protected $league;
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     */
    protected $person;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_added;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $initial_position;
    /**
     * @ORM\Column(type="integer")
     */
    protected $points;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date_added.
     *
     * @param \DateTime $dateAdded
     *
     * @return LeaguePerson
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;

        return $this;
    }

    /**
     * Get date_added.
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set initial_position.
     *
     * @param int $initialPosition
     *
     * @return LeaguePerson
     */
    public function setInitialPosition($initialPosition)
    {
        $this->initial_position = $initialPosition;

        return $this;
    }

    /**
     * Get initial_position.
     *
     * @return int
     */
    public function getInitialPosition()
    {
        return $this->initial_position;
    }

    /**
     * Set points.
     *
     * @param int $points
     *
     * @return LeaguePerson
     */
    public function setPoints($points)
    {
        $this->points = $points;

        return $this;
    }

    /**
     * Get points.
     *
     * @return int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set league.
     *
     * @param League $league
     *
     * @return LeaguePerson
     */
    public function setLeague(League $league = null)
    {
        $this->league = $league;

        return $this;
    }

    /**
     * Get league.
     *
     * @return League
     */
    public function getLeague()
    {
        return $this->league;
    }

    /**
     * Set person.
     *
     * @param Person $person
     *
     * @return LeaguePerson
     */
    public function setPerson(Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person.
     *
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    public function __toString()
    {
        return (string) $this->person;
    }
}
