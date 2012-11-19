<?php 
/**
 *
 */
namespace My\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 
 * @ORM\Table(name="users")
 * @ORM\Entity 
 * @author Javier Lorenzana <javier.lorenzana@gointegro.com>
 */
class User implements \JsonSerializable
{
    const ACCESSOR_PREFIX = 'get';
    const ACCESSOR_PREFIX_LENGTH = 3;
    const MUTATOR_PREFIX = 'set';
    const MUTATOR_PREFIX_LENGTH = 3;
    /**
     *
     * @var integer $id
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     *
     * @var integer $id
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $firstname;
    /**
     *
     * @var integer $id
     * @ORM\Column(type="string", length=60, nullable=true)
     */
    private $lastname;

    /**
     * Catches calls to undefined methods.
     */
    public function __call($method, $args)
    {
        if (self::ACCESSOR_PREFIX == substr($method, 0, self::ACCESSOR_PREFIX_LENGTH)) {
            $property = lcfirst(substr($method, self::ACCESSOR_PREFIX_LENGTH));
            if (property_exists($this, $property)) {
                return $this->$property;
            }
        } elseif (self::MUTATOR_PREFIX == substr($method, 0, self::MUTATOR_PREFIX_LENGTH)) {
            $property = lcfirst(substr($method, self::MUTATOR_PREFIX_LENGTH));
            if (property_exists($this, $property)) {
                $this->$property = $args[0];
                return $this;
            }
        }
    }

    /**
     *
     * @see \JsonSerializable
     */
    public function jsonSerialize()
    {
        $popo = new \stdClass();
        foreach ($this as $property => $value) {
            $popo->$property = $value;
        }
        return $popo;
    }
}
