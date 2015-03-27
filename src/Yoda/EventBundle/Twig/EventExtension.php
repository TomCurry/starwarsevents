<?php

namespace Yoda\EventBundle\Twig;

use Yoda\EventBundle\Util\DateUtil;
/**
 * Description of EventExtension
 *
 * @author tom
 */
class EventExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'event';
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('ago', array($this, 'calculateAgo')),
        );
    }
    
    public function calculateAgo(\DateTime $dt)
    {
        return DateUtil::ago($dt);
    }

}
