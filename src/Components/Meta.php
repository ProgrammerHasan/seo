<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Components;

use Illuminate\View\Component;
use ProgrammerHasan\Seo\Facades\Seo;

final class Meta extends Component
{
    public function render()
    {
        return view('seo::components.meta', ['html' => Seo::toHtml()]);
    }
}
