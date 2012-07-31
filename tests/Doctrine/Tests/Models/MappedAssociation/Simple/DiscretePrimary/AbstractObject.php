<?php
namespace Doctrine\Tests\Models\MappedAssociation\Simple\DiscretePrimary;

/**
 * @MappedSuperclass
 */
class AbstractObject
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @OneToOne(targetEntity="Shelf", mappedBy="object")
     */
    private $shelf;

    /**
     * @Column(type="string", length=128)
     */
    private $description;

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getShelf()
    {
        return $this->shelf;
    }

    public function setShelf(Shelf $shelf)
    {
        $this->shelf = $shelf;
    }
}
