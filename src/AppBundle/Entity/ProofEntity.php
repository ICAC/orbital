<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class ProofEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Person who submitted the proof.
     *
     * @ORM\ManyToOne(targetEntity="Person")
     */
    private $person;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $image_name;
    /**
     * @ORM\ManyToOne(targetEntity="Person")
     * @ORM\JoinColumn(nullable=true)
     */
    private $voucher;
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $notes;

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
     * Set image_name.
     *
     * @param string $imageName
     *
     * @return ProofEntity
     */
    public function setImageName($imageName)
    {
        $this->image_name = $imageName;

        return $this;
    }

    /**
     * Get image_name.
     *
     * @return string
     */
    public function getImageName()
    {
        return $this->image_name;
    }

    /**
     * Set notes.
     *
     * @param string $notes
     *
     * @return ProofEntity
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set person.
     *
     * @param Person $person
     *
     * @return ProofEntity
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

    /**
     * Set voucher.
     *
     * @param Person $voucher
     *
     * @return ProofEntity
     */
    public function setVoucher(Person $voucher = null)
    {
        $this->voucher = $voucher;

        return $this;
    }

    /**
     * Get voucher.
     *
     * @return Person
     */
    public function getVoucher()
    {
        return $this->voucher;
    }
}
