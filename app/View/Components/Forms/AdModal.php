<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AdModal extends Component
{
    public $title;
    public $action;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $action)
    {


      $this->title = $title;
    $this->action = $action;


    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.ad-modal');
    }
}
