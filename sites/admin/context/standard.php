<?php /** @var WW\Context $this */

if( $this->breadcrumb ){
    $breadcrumb = $this->breadcrumb;
}
else
{
    $breadcrumb         = [];
    $breadcrumbWitch    = $this->witch("target") ?? $this->witch();
    while( !empty($breadcrumbWitch) )
    {
        if( empty($breadcrumb) ){
            $url    = "javascript: location.reload();";
        }
        else {
            $url    = $breadcrumbWitch->url();
        }
        
        if( $url ){
            $breadcrumb[]   = [
                "name"  => $breadcrumbWitch->name,
                "data"  => $breadcrumbWitch->data,
                "href"  => $url,
            ];
        }

        if( $this->witch('root') === $breadcrumbWitch ){
            break;
        }

        $breadcrumbWitch    = $breadcrumbWitch->mother();    
    }
    
    $breadcrumb = array_reverse($breadcrumb);
}

$this->view();