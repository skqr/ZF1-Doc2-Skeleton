<?php
/**
 *
 */

/**
 *
 * @author Javier Lorenzana <javier.lorenzana@gointegro.com>
 */
class ModelTestCase extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Bisna\Application\Container\DoctrineContainer
     */
    protected $doctrineContainer;
    /**
     *
     */
    public function setUp()
    {
        echo "SETTING UP";
        parent::setUp();
        global $application;
        $application->bootstrap();
        $this->doctrineContainer = Zend_Registry::get('doctrine');
        // self::dropSchema($this->doctrineContainer->getConnection()->getParams());
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->doctrineContainer->getEntityManager());
        $metas = $this->getClassMetas(APPLICATION_PATH . '/../library/My/Entity', 'My\Entity\\');
        //$tool->dropSchema($metas);
        //$tool->dropDatabase();
        $tool->createSchema($metas);
    }
    /**
     *
     */
    public function getClassMetas($path, $namespace)
    {
        $metas = array();
        if ($handle = opendir($path)) {
            while (false !== $file = readdir($handle)) {
                if (strstr($file, '.php')) {
                    list($class) = explode('.', $file);
                    $metas[] = $this->doctrineContainer->getEntityManager()->getClassMetadata($namespace . $class);
                }
            }
        }
        return $metas;
    }

    /**
     *
     */
    public static function dropSchema($params)
    {
        if (file_exists($params['path'])) {
            unlink($params['path']);
        }
    }

    /**
     *
     */
    public function tearDown()
    {
        parent::tearDown();
        // self::dropSchema($this->doctrineContainer->getConnection()->getParams());
    }
}