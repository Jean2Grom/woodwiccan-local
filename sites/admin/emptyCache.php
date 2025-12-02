<?php /** @var WW\Module $this */

$reset = $this->ww->cache->reset();

$this->ww->user->addAlerts([[
    'level'     =>  $reset? 'success': 'error',
    'message'   =>  $reset? 'Cache has been removed': 'Cache removing has failed',    
]]);

$this->setContext('empty');
$this->display('back');
