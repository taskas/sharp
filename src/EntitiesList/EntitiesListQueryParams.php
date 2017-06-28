<?php

namespace Code16\Sharp\EntitiesList;

class EntitiesListQueryParams
{
    /**
     * @var int
     */
    protected $page;

    /**
     * @var string
     */
    protected $search;

    /**
     * @var string
     */
    protected $sortedBy;

    /**
     * @var string
     */
    protected $sortedDir;

    /**
     * @param string|null $defaultSortedBy
     * @param string|null $defaultSortedDir
     * @return static
     */
    public static function createFromRequest($defaultSortedBy = null, $defaultSortedDir = null)
    {
        $instance = new static;
        $instance->search = request("search");
        $instance->page = request("page");
        $instance->sortedBy = request("sorted") ?: $defaultSortedBy;
        $instance->sortedDir = request("dir") ?: $defaultSortedDir;

        return $instance;
    }

    /**
     * @return bool
     */
    public function hasSearch()
    {
        return strlen(trim($this->search)) > 0;
    }

    /**
     * @return string
     */
    public function sortedBy()
    {
        return $this->sortedBy;
    }

    /**
     * @return string
     */
    public function sortedDir()
    {
        return $this->sortedDir;
    }

    /**
     * @param bool $isLike
     * @param bool $handleStar
     * @param string $noStarTermPrefix
     * @param string $noStarTermSuffix
     * @return array
     */
    public function searchWords($isLike = true, $handleStar = true, $noStarTermPrefix = '%', $noStarTermSuffix = '%')
    {
        $terms = [];

        foreach (explode(" ", $this->search) as $term) {
            $term = trim($term);
            if (!$term) {
                continue;
            }

            if ($isLike) {
                if ($handleStar && strpos($term, '*') !== false) {
                    $terms[] = str_replace('*', '%', $term);
                    continue;
                }

                $terms[] = $noStarTermPrefix . $term . $noStarTermSuffix;
                continue;
            }

            $terms[] = $term;
        }

        return $terms;
    }

}