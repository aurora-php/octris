<?php

/*
 * This file is part of the '{{$vendor}}/{{$package}}' package.
 *
 * (c) {{$company}}
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace {{$namespace}}\App;

/**
 * index page.
 *
 * @copyright   copyright (c) {{$year}} by {{$company}}
 * @author      {{$author}} <{{$email}}>
 */
class Index extends \Octris\Core\App\Web\Page
{
    /**
     * The index points to which the current page should allow requests to have to be defined with this
     * property.
     *
     * @type    array
     */
     protected $next_pages = array(
         '' => '{{$namespace}}\App\Index',
     );
    /**/

    /**
     * The constructor is used to setup common settings for example validation rulesets must be defined
     * through the page object constructor.
     *
     * @param   \Octris\Core\App\Web                    Application instance.
     */
    public function __construct(\Octris\Core\App\Web $app)
    {
        parent::__construct($app);
    }

    /**
     * Prepare rendering of a page. this method is called _BEFORE_ rendering a page.
     *
     * @param   \Octris\Core\App\Page       $last_page  Instance of the page that was active before this one
     * @return  null|\Octris\Core\App\Page              A page can be returned.
     */
    public function prepare(\Octris\Core\App\Page $last_page, $action)
    {
    }

    /**
     * This method is used to populate a template with data and render it.
     */
    public function render()
    {
        $tpl = $this->app->getTemplate();
        $tpl->render('index.html');
    }
}
