<?php

namespace WhitePage\Traits;

use WhitePage\Facades\WhitePage;

trait HasHref
{
    public function getAttributeList(string $section)
    {
        return array_merge($this->getAttributes(), [
            'href' => href(WhitePage::CMS_ROOT_PREFIX, $section, 'update', $this->id),
        ]);
    }
}
