<?php
namespace Rakshazi\SlimSuit\Dummy;

class Controller extends \Rakshazi\SlimSuit\Controller
{
    public function indexAction()
    {
        return $this->redirect('/');
    }
}
