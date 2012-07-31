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
use Doctrine\Tests\Models\MappedAssociation\Simple\DiscretePrimary\Shelf;
use Doctrine\Tests\Models\MappedAssociation\Simple\DiscretePrimary\Book;
use Doctrine\Tests\Models\MappedAssociation\Simple\DiscretePrimary\Video;

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
    const SHELF              = 'Doctrine\Tests\Models\MappedAssociation\Simple\DiscretePrimary\Shelf';
    const BOOK               = 'Doctrine\Tests\Models\MappedAssociation\Simple\DiscretePrimary\Book';
    const VIDEO              = 'Doctrine\Tests\Models\MappedAssociation\Simple\DiscretePrimary\Video';

    protected function setUp()
    {
        $this->useModelSet('mappedassociation');
        parent::setUp();
    }

    public function testSimplePrimaryIsForeignMappedAssociation()
    {
        // Create file folder 0
        $fileFolder0 = new FileFolder();
        $fileFolder0->setTitle('Folder 0');
        $this->_em->persist($fileFolder0);
        $this->_em->flush();
        $id0 = $fileFolder0->getId();

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

        $results = $query->setParameter(1, $id0)
            ->getResult();
        $this->assertEquals($results[0]['contentClass'], null);

        $results = $query->setParameter(1, $id1)
            ->getResult();
        $this->assertEquals($results[0]['contentClass'], get_class($content1));

        $results = $query->setParameter(1, $id2)
            ->getResult();
        $this->assertEquals($results[0]['contentClass'], get_class($content2));

        $queryFileFolder0 = $repository->find($id0);
        $this->assertEquals($fileFolder0, $queryFileFolder0);

        $queryFileFolder1 = $repository->find($id1);
        $this->assertEquals($fileFolder1, $queryFileFolder1);

        $queryFileFolder2 = $repository->find($id2);
        $this->assertEquals($fileFolder2, $queryFileFolder2);

        $this->_em->remove($queryFileFolder0);
        $this->_em->remove($queryFileFolder1);
        $this->_em->remove($queryFileFolder2);
        $this->_em->flush();
        $this->_em->clear();

        $queryFileFolder0 = $repository->find($id0);
        $this->assertEquals(null, $queryFileFolder0);

        $queryFileFolder1 = $repository->find($id1);
        $this->assertEquals(null, $queryFileFolder1);

        $queryFileFolder2 = $repository->find($id2);
        $this->assertEquals(null, $queryFileFolder2);

        $repository = $this->_em->getRepository($this::PAPER);
        $results = $repository->findAll();
        $this->assertEmpty($results);

        $repository = $this->_em->getRepository($this::PHOTO);
        $results = $repository->findAll();
        $this->assertEmpty($results);
    }

    public function testSimplePrimaryIsForeignDetachedMappedAssociation()
    {
        // Create file folder 0
        $fileFolder0 = new FileFolderDetached();
        $fileFolder0->setTitle('Folder 0');
        $this->_em->persist($fileFolder0);
        $this->_em->flush();
        $id0 = $fileFolder0->getId();

        // Create file folder 1
        $fileFolder1 = new FileFolderDetached();
        $fileFolder1->setTitle('Folder 1');
        $this->_em->persist($fileFolder1);
        $this->_em->flush();
        $id1 = $fileFolder1->getId();

        $content1 = new PaperDetached;
        $content1->setDescription('Expense report');
        $fileFolder1->setContent($content1);
        $this->_em->flush();

        // Create file folder 2
        $fileFolder2 = new FileFolderDetached();
        $fileFolder2->setTitle('Folder 2');
        $this->_em->persist($fileFolder2);
        $this->_em->flush();
        $id2 = $fileFolder2->getId();

        $content2 = new PhotoDetached;
        $content2->setDescription('Family photo');
        $fileFolder2->setContent($content2);
        $this->_em->flush();

        $this->_em->clear();

        $repository = $this->_em->getRepository($this::FILEFOLDERDETACHED);
        $query = $repository->createNativeNamedQuery('get-class-by-id');

        $results = $query->setParameter(1, $id0)
            ->getResult();
        $this->assertEquals($results[0]['contentClass'], null);

        $results = $query->setParameter(1, $id1)
            ->getResult();
        $this->assertEquals($results[0]['contentClass'], get_class($content1));

        $results = $query->setParameter(1, $id2)
            ->getResult();
        $this->assertEquals($results[0]['contentClass'], get_class($content2));

        $queryFileFolder0 = $repository->find($id0);
        $this->assertEquals($fileFolder0, $queryFileFolder0);

        $queryFileFolder1 = $repository->find($id1);
        $this->assertEquals($fileFolder1, $queryFileFolder1);

        $queryFileFolder2 = $repository->find($id2);
        $this->assertEquals($fileFolder2, $queryFileFolder2);

        $this->_em->remove($queryFileFolder0);
        $this->_em->remove($queryFileFolder1);
        $this->_em->remove($queryFileFolder2);
        $this->_em->flush();
        $this->_em->clear();

        $queryFileFolder0 = $repository->find($id0);
        $this->assertEquals(null, $queryFileFolder0);

        $queryFileFolder1 = $repository->find($id1);
        $this->assertEquals(null, $queryFileFolder1);

        $queryFileFolder2 = $repository->find($id2);
        $this->assertEquals(null, $queryFileFolder2);

        $repository = $this->_em->getRepository($this::PAPER);
        $results = $repository->findAll();
        $this->assertEmpty($results);

        $repository = $this->_em->getRepository($this::PHOTO);
        $results = $repository->findAll();
        $this->assertEmpty($results);
    }

    public function testSimpleDiscretePrimaryMappedAssociation()
    {
        // Create shelf 1
        $shelf1 = new Shelf();
        $shelf1->setBookcase('Bedroom');

        $object1 = new Book;
        $object1->setDescription('To Kill a Mockingbird');
        $shelf1->setObject($object1);
        $this->_em->persist($shelf1);
        $this->_em->flush();
        $id1 = $shelf1->getId();

        // Create shelf 2
        $shelf2 = new Shelf();
        $shelf2->setBookcase('Theater');

        $object2 = new Video;
        $object2->setDescription('Die Hard');
        $shelf2->setObject($object2);
        $this->_em->persist($shelf2);
        $this->_em->flush();
        $id2 = $shelf2->getId();

        $this->_em->clear();

        $repository = $this->_em->getRepository($this::SHELF);
        $query = $repository->createNativeNamedQuery('get-class-by-id');

        $query = $query->setParameter(1, $id1);
        $results = $query->getResult();
        $this->assertEquals($results[0]['objectClass'], get_class($object1));

        $results = $query->setParameter(1, $id2)
            ->getResult();
        $this->assertEquals($results[0]['objectClass'], get_class($object2));

        $queryShelf1 = $repository->find($id1);
        $this->assertEquals($shelf1, $queryShelf1);

        $queryShelf2 = $repository->find($id2);
        $this->assertEquals($shelf2, $queryShelf2);
    }
}
