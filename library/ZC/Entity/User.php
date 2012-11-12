<?php 
/**
 *
 */
namespace ZC\Entity;
/**
 * 
 * @Table(name="users")
 * @Entity 
 * @author Javier Lorenzana <javier.lorenzana@gointegro.com>
 */
class User
{
    /**
     *
     * @var integer $id
     * @Column(name="id", type="integer", nullable=false)
     * @Id
     * @GeneratedValue(strategy="IDENTITY")
     */
    private $id;
    /**
     *
     * @var integer $id
     * @Column(type="string", length=60, nullable=true)
     */
    private $firstname;
    /**
     *
     * @var integer $id
     * @Column(type="string", length=60, nullable=true)
     */
    private $lastname;
}
?>