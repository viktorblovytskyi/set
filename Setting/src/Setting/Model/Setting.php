<?php
/**
 * @package Setting
 * @author Viktor Blovytskyi <viktorb@templatemonster.me>
 */
namespace Setting\Model;

use Application\Model\AbstractModel;

class Setting extends AbstractModel
{
    /**
     * Setting id
     */
	protected $id;

    /**
     * Setting name
     */
	protected $name;

    /**
     * Setting value
     */
	protected $value;

    /**
     * Setting description
     */
	protected $description;

    /**
     * Setting type
     */
	protected $type;

    /**
     * Setting archive
     */
	protected $archive;

    /**
     * Setting prefix
     */
    protected $prefix;

    /**
     * Get Setting prefix
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set Setting Id
     * @param string Setting
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     *  Get id Setting.
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Setting Id
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get Setting name.
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Setting name
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get Setting value.
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set Setting value.
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get Setting description.
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set Setting description.
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get Setting type.
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set Setting type.
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get Setting archived value.
     * @return boolean
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Set Setting archived value.
     * @param boolean $archive
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;
    }
}
