<?php

declare(strict_types=1);

namespace VOSTPT\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class FallbackController
{
    /**
     * Redirect to the documentation.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function documentation(): RedirectResponse
    {
        return redirect('/documentation');
    }
}
