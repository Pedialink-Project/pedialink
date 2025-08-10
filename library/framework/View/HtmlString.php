<?php

namespace Library\Framework\View;

/**
 * Class to handle html inside interpolation directive {{ }}
 * as raw string
 */
class HtmlString
{
    protected string $html;

    public function __construct(string $html)
    {
        $this->html = $html;
    }

    /**
     * Return raw string
     * @return string
     */
    public function toHtml(): string
    {
        return $this->html;
    }

    /**
     * Allow string casting
     * @return string
     */
    public function __toString(): string
    {
        return $this->html;
    }
}
