<?php

namespace Doctrine\Tests\ORM\Functional;

use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Query;
use Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeign\FileFolder;
use Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeign\Paper;
use Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeign\Photo;
use Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeignDetached\FileFolder as FileFolderDetached;
use Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeignDetached\Paper as PaperDetached;
use Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeignDetached\Photo as PhotoDetached;

require_once __DIR__ . '/../../TestInit.php';

/**
 * @group Doctrine.MappedAssociation
 */
class MappedAssociationTest extends \Doctrine\Tests\OrmFunctionalTestCase
{
    const FILEFOLDER         = 'Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeign\FileFolder';
    const PAPER              = 'Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeign\Paper';
    const PHOTO              = 'Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeign\Photo';
    const FILEFOLDERDETACHED = 'Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeignDetached\FileFolder';
    const PAPERDETACHED      = 'Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeignDetached\Paper';
    const PHOTODETACHED      = 'Doctrine\Tests\Models\MappedAssociation\Simple\PrimaryIsForeignDetached\Photo';

    protected function setUp()
    {
        $this->useModelSet('mappedassociation');
        parent::setUp();
    }

    public function testSimplePrimaryIsForeignMappedAssociation()
    {
        // Create file folder 1
        $fileFolder1 = new FileFolder();
        $fileFolder1->setTitle('Folder 1');
        $this->_em->persist($fileFolder1);
        $this->_em->flush();
        $id1 = $fileFolder1->getId();

        $content1 = new Paper;
        $content1->setDescription('Expense report');
        $fileFolder1->setContent($content1);
        $this->_em->flush();

        // Create file folder 2
        $fileFolder2 = new FileFolder();
        $fileFolder2->setTitle('Folder 2');
        $this->_em->persist($fileFolder2);
        $this->_em->flush();
        $id2 = $fileFolder2->getId();

        $content2 = new Photo;
        $content2->setDescription('Family photo');
        $fileFolder2->setContent($content2);
        $this->_em->flush();

        $this->_em->clear();

        $repository = $this->_em->getRepository($this::FILEFOLDER);
        $query = $repository->createNativeNamedQuery('get-class-by-id');

        $results = $query->setParameter(1, $id1)
            ->getResult();
        $this->assertEquals($results[0]['contentClass'], get_class($content1));

        $results = $query->setParameter(1, $id2)
            ->getResult();
        $this->assertEquals($results[0]['contentClass'], get_class($content2));

        $queryFileFolder1 = $repository->find($id1);
        $this->assertEquals($fileFolder1, $queryFileFolder1);

        $queryFileFolder2 = $repository->find($id2);
        $this->assertEquals($fileFolder2, $queryFileFolder2);
    }
}
