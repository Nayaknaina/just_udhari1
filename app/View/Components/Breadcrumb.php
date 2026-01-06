<?php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component {

    public $title;
    public $breadcrumbs;
    public $backButton;

    /**
     * Create a new component instance.
     */

    public function __construct($title = '', $breadcrumbs = [], $backButton = false ){
        $this->title = $title;
        $this->breadcrumbs = $breadcrumbs;
        $this->backButton = $backButton;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.breadcrumb');
    }
}
