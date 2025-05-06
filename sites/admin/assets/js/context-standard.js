$(document).ready(function()
{
    $('.side-nav-toggler').click( function(){
        $('nav').toggle();
    });
    
    var tabs = document.getElementsByClassName("tabs__item");
    
    var selectTab = function( event ){
        event.preventDefault();
        
        if( this.className.includes("tabs__item__close") )
        {
            if( this.parentNode.className.includes("selected") )
            {
                let tabContainer = this.parentNode.parentNode;
                let newSelectTab = '#tab-current';
                
                for( let tabIterator of tabContainer.children ){
                    if( !tabIterator.className.includes("selected") 
                            && tabIterator.style.display !== "none"
                            && tabIterator.querySelectorAll('a.tabs__item__close').length > 0
                    ){
                        newSelectTab = tabIterator.querySelectorAll('a')[0].getAttribute('href');
                    }
                }
                
                triggerTabItem( newSelectTab );
            }
            
            this.parentNode.style.display = "none";            
            return false;
        }
        
        if( this.parentNode.className.includes("selected") ){
            return false;
        }
        
        let seletedTabs = document.getElementsByClassName("tabs__item selected");
        for( let i = 0; i < seletedTabs.length; i++ ){
            seletedTabs[i].classList.remove("selected");
        }
        
        let seletedTargets = document.getElementsByClassName("tabs-target__item selected");
        for( let i = 0; i < seletedTargets.length; i++ ){
            seletedTargets[i].classList.remove("selected");
        }
        
        this.parentNode.classList.add("selected");
        
        let targetId    = this.getAttribute("href").substring(1);
        let target      = document.getElementById( targetId );
        
        target.classList.add("selected");        
        return false;
    };

    for( let i = 0; i < tabs.length; i++ ){
        for( let anchor of tabs[i].children ){
            anchor.addEventListener( 'click', selectTab, {passive: false} );
        }
    }

    window.triggerTabItem = function ( hash )
    {
        let tabFired    = document.querySelectorAll(".tabs__item a[href='" + hash + "']");
        let evObj       = document.createEvent('Events');
        
        evObj.initEvent('click', true, false);
        tabFired[0].dispatchEvent(evObj);
        return;
    };
    
    if( window.top.location.hash !== undefined && window.top.location.hash !== '' ){
        triggerTabItem( window.top.location.hash );
    }
    
    
    for( let triggerElemt of document.querySelectorAll(".tabs__item__triggering") ){
        triggerElemt.addEventListener( 'click', function(){ 
            triggerTabItem( $(this).attr('href') );
            return false;
        });
    }
});