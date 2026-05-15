<?php

declare(strict_types=1);

namespace ProgrammerHasan\Seo\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use ProgrammerHasan\Seo\Facades\Seo;

final class Meta extends Component
{
    public function render(): View
    {
        /** @var view-string $view */
        $view = 'seo::components.meta';

        return view($view, [
            'html' => Seo::toHtml(),
        ]);
    }
}
