<?php
namespace WW\Datatype;

/**
 * Class to extend DateTime native, adding a french format 
 * and an associated actor name
 * 
 * @author Jean2Grom
 */
class ExtendedDateTime extends \DateTime 
{
    public $frenchFormatDate;
    public $frenchFormatDateTime;
    public $actor;
    
    public function __construct(?string $datetime = "now", ?string $actor = null, ?string $timezone = null) 
    {
        $this->actor    =  $actor ?? '';
        $dateTimeZone   = new \DateTimeZone( $timezone ?? date_default_timezone_get() );
        
        parent::__construct($datetime, $dateTimeZone);
    }
    
    function sqlFormat()
    {
        return $this->format('Y-m-d H:i:s');
    }
        
    function frenchFormat( $time=false )
    {
        if( !isset($this->frenchFormatDate) )
        {
            $mois   =   array(  'janvier', 
                                'février', 
                                'mars', 
                                'avril', 
                                'mai', 
                                'juin', 
                                'juillet', 
                                'août', 
                                'septembre', 
                                'octobre', 
                                'novembre', 
                                'décembre'
                        );
            $jour   =   array(  'dimanche', 
                                'lundi', 
                                'mardi', 
                                'mercredi', 
                                'jeudi', 
                                'vendredi', 
                                'samedi'
                        );
            $this->frenchFormatDate =   $jour[$this->format('w')].' ';
            $this->frenchFormatDate .=  date('j').' ';
            $this->frenchFormatDate .=  $mois[$this->format('n')-1].' ';
            $this->frenchFormatDate .=  $this->format('Y');
        }
        
        if( !$time ){
            return $this->frenchFormatDate;
        }
        
        $this->frenchFormatDateTime =   $this->frenchFormatDate;
        $this->frenchFormatDateTime .=  " à ".$this->format('H:i:s');
        
        return $this->frenchFormatDateTime;
    }
}
