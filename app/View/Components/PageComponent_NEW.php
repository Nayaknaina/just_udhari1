<?php
// app/View/Components/PageComponent.php
namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PageComponent extends Component {

    public $datas ;
    public $myKey ;
    public $anchor ;

    /**
     * Create a new component instance.
     *
     * @param array $data
     */

    public function __construct($data,$anchor=[]) {
        $param = json_decode($data,true) ;
        $myKey = $this->myKey = array_key_first($param) ;
        $breadcrumbData = $param[$myKey] ;
        $this->datas = $breadcrumbData ;
        $this->anchor = $anchor;
    }

    /**
     * Get the view / contents that represent the component.
     */

    public function render(): View|Closure|string
    {
        return view("components.{$this->myKey}") ;
    }
}
