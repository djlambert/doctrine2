<?php
namespace Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeign;

/**
 * @MappedSuperclass
 */
class AbstractContent
{
    /**
     * @Id
     * @OneToOne(targetEntity="FileFolder", inversedBy="content")
     * @JoinColumn(name="id")
     */
    private $fileFolder;

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

    public function getFileFolder()
    {
        return $this->fileFolder;
    }

    public function setFileFolder(FileFolder $fileFolder)
    {
        $this->fileFolder = $fileFolder;
    }
}
