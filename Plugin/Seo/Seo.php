<?php

namespace Ant\Plugin\Seo;

use Ant\Library\ObjectsCollection;

class Seo implements SeoInterface
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var Canonical[]|ObjectsCollection
     */
    private $canonicals;

    /**
     * @var Og[]|ObjectsCollection
     */
    private $ogs;

    /**
     * @var Meta[]|ObjectsCollection
     */
    private $metas;

    /**
     * @var ItempropLink[]|ObjectsCollection
     */
    private $itempropLinks;

    /**
     * @var ItempropMeta[]|ObjectsCollection
     */
    private $itempropMetas;

    /**
     * @var Alternate[]|ObjectsCollection
     */
    private $alternates;

    /**
     * @var Generic[]|ObjectsCollection
     */
    private $generics;

    /**
     * @param string $title
     */
    public function __construct($title = '')
    {
        $this->title         = $title;
        $this->canonicals    = new ObjectsCollection([]);
        $this->ogs           = new ObjectsCollection([]);
        $this->metas         = new ObjectsCollection([]);
        $this->itempropLinks = new ObjectsCollection([]);
        $this->itempropMetas = new ObjectsCollection([]);
        $this->alternates    = new ObjectsCollection([]);
        $this->generics      = new ObjectsCollection([]);
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Canonical[]|ObjectsCollection
     */
    public function getCanonicals()
    {
        return $this->canonicals;
    }

    /**
     * @param Canonical[] $canonicals
     *
     * @return $this
     */
    public function setCanonicals($canonicals)
    {
        $this->canonicals = $canonicals;
        return $this;
    }

    /**
     * @return Og[]|ObjectsCollection
     */
    public function getOgs()
    {
        return $this->ogs;
    }

    /**
     * @param Og[] $ogs
     *
     * @return $this
     */
    public function setOgs($ogs)
    {
        $this->ogs = $ogs;
        return $this;
    }

    /**
     * @return Meta[]|ObjectsCollection
     */
    public function getMetas()
    {
        return $this->metas;
    }

    /**
     * @param Meta[] $metas
     *
     * @return $this
     */
    public function setMetas($metas)
    {
        $this->metas = $metas;
        return $this;
    }

    /**
     * @return ItempropLink[]|ObjectsCollection
     */
    public function getItempropLinks()
    {
        return $this->itempropLinks;
    }

    /**
     * @param ItempropLink[] $itempropLinks
     *
     * @return $this
     */
    public function setItempropLinks($itempropLinks)
    {
        $this->itempropLinks = $itempropLinks;
        return $this;
    }

    /**
     * @return ItempropMeta[]|ObjectsCollection
     */
    public function getItempropMetas()
    {
        return $this->itempropMetas;
    }

    /**
     * @param ItempropMeta[] $itempropMetas
     *
     * @return $this
     */
    public function setItempropMetas($itempropMetas)
    {
        $this->itempropMetas = $itempropMetas;
        return $this;
    }

    /**
     * @return Alternate[]|ObjectsCollection
     */
    public function getAlternates()
    {
        return $this->alternates;
    }

    /**
     * @param Alternate[] $alternates
     *
     * @return $this
     */
    public function setAlternates($alternates)
    {
        $this->alternates = $alternates;
        return $this;
    }

    /**
     * @return Generic[]|ObjectsCollection
     */
    public function getGenerics()
    {
        return $this->generics;
    }

    /**
     * @param Generic[] $generics
     *
     * @return $this
     */
    public function setGenerics($generics)
    {
        $this->generics = $generics;
        return $this;
    }

    /**
     * @return string
     */
    public function render()
    {
        $result = '';
        $properties = get_object_vars($this);

        foreach ($properties as $property => $value) {
            $getter = 'get' . $property;
            if (!method_exists($this, $getter)) {
                continue;
            }
            if ($property == 'title') {
                $result .= "<title>{$this->getTitle()}</title>\n";
                continue;
            }
            if (!$this->{$getter}() instanceof ObjectsCollection) {
                continue;
            }
            /** @var SeoInterface $seoProperty */
            foreach ($this->{$getter}() as $seoProperty) {
                if (!$seoProperty instanceof SeoInterface) {
                    continue;
                }
                $result .= $seoProperty->render() . "\n";
            }
        }

        return $result;
    }
}