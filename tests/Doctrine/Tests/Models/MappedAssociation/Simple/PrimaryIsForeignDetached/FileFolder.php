<?php
namespace Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeignDetached;

/**
 * @Entity
 * @Table(name="pifd_filefolder")
 * @NamedNativeQueries({
 *      @NamedNativeQuery(
 *          name                = "get-class-by-id",
 *          resultSetMapping    = "get-class",
 *          query               = "SELECT contentClass from pifd_filefolder WHERE id = ?"
 *      )
 * })
 *
 * @SqlResultSetMappings({
 *      @SqlResultSetMapping(
 *          name    = "get-class",
 *          columns = {
 *              @ColumnResult(
 *                  name = "contentClass"
 *              )
 *          }
 *      )
 * })
 *
 */
class FileFolder
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @Column(type="string", length=128)
     */
    private $title;

    /**
     * @MappedAssociation
     */
    private $content;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setContent(AbstractContent $content)
    {
        $content->setFileFolder($this);
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }
}
