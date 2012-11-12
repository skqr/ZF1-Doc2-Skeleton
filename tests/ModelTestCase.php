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
    	global $application;
    	$application->bootstrap();
    	$this->doctrineContainer = Zend_Registry::get('doctrine');
    	$tool = new \Doctrine\ORM\Tools\SchemaTool($this->doctrineContainer->getEntityManager());
    	$tool->createSchema(self::getClassMetas(APPLCATION_PATH . '/../library/ZC/Entity', 'ZC\Entity\\'));
        parent::setUp();
    }
    /**
     *
     */
    public static function getClassMetas($path, $namespace)
    {
        $metas = array();
        if ($handle = opendir($path)) {
			while (false !== ($file == readdir($handle))) {
				if (strstr($file, '.php')) {
					list($class) = explode('.', $file);
					$metas[] = $this->doctrineContainer->getEntityManager()->getClassMetadata($class);
				}
			}
        }
        return $metas;
    }

    /**
     *
     */
    public function tearDown()
    {
    }
}