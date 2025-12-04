const ArborescenceMenu = function( key ){ 
    return {
        key: key,

        treeData: null,
        currentId: null,
        currentSite: null,
        breadcrumb: null,

        homeIds: [],

        draggable: null,
        draggedId: null,
        dropped: null,
        clipboardUrl: null,
        createUrl: null,
        cauldronUrl: null,
        urlHash: null,

        container: null,

        icons: {
            homeWitch: {dom: "i", classes: [ 'fas', 'fa-home' ]}, 
            //homeWitch: {dom: "i", classes: [ 'fa', 'fa-dungeon' ]}, 
            fullWitch: {dom: "i", classes: [ 'fa', 'fa-hat-wizard' ]}, 
            cauldronWitch: {dom: "i", classes: [ 'fa', 'fa-mortar-pestle' ]}, 
            invokeWitch: {dom: "i", classes: [ 'fa', 'fa-hand-sparkles' ]}, 
            basicWitch: {dom: "i", classes: [ 'fa', 'fa-folder' ]}, 
            //basicWitch: {dom: "i", classes: [ 'fa', 'fa-skull' ]}, 
            //basicWitch: {dom: "i", classes: [ 'fa', 'fa-broom' ]}, 


            toggleDomClosed: {dom: "i", classes: [ 'far', 'fa-folder' ]}, 
            toggleDomOpen: {dom: "i", classes: [ 'far', 'fa-folder-open' ]}, 
            //toggleDomClosed: {dom: "i", classes: [ 'fas', 'fa-chevron-down' ]},
            //toggleDomOpen: {dom: "i", classes: [ 'fas', 'fa-chevron-right' ]},

            add: {dom: "i", classes: [ 'fa', 'fa-plus' ]}, 
            view: {dom: "i", classes: [ 'fa', 'fa-eye' ]}, 
            cancel: {dom: "i", classes: [ 'fa', 'fa-times' ]}, 
            copy: {dom: "i", classes: [ 'fa', 'fa-clone' ]}, 
            move: {dom: "i", classes: [ 'fa', 'fa-arrows' ]}, 
            order: {dom: "i", classes: [ 'fa', 'fa-sort-numeric-down-alt' ]}, 
        },

        init: async function( entries )
        {
            if( Array.isArray(entries.treeData) ){
                this.treeData       = entries.treeData;
            }
            else {
                this.treeData       = [ entries.treeData ];
            }
            this.currentId      = entries.currentId;
            this.currentSite    = entries.currentSite;
            this.breadcrumb     = entries.breadcrumb;

            this.draggable      = entries.draggable ?? false;
            this.clipboardUrl   = entries.clipboardUrl ?? null,
            this.createUrl      = entries.createUrl ?? null,
            this.cauldronUrl    = entries.cauldronUrl ?? null,
            this.urlHash        = entries.urlHash ?? null,

            this.container      = document.querySelector('#' + this.key + '.arborescence-menu-container'),

            this.treeData.forEach(firstLevel => this.homeIds.push(firstLevel.id) );

            this.addArborescenceLevel( this.treeData )
            .then( 
                this.open( this.breadcrumb )
            )
            .then(
                this.container.addEventListener("wheel", (e) => {

                    let diff = this.container.scrollWidth - this.container.offsetWidth;
                    if( !(this.container.scrollLeft === 0 && e.deltaY < 0) 
                        && !(this.container.scrollLeft >= diff && e.deltaY > 0)
                    ){
                        e.preventDefault();
                        this.container.scrollLeft += e.deltaY;
                    }
                })
            )
            .then(
                () => {
                    if( !this.draggable ){
                        return;
                    }

                    this.container.addEventListener( "contextmenu", 
                        e => {
                            e.preventDefault();

                            this.closeDragAndContext();

                            let targetedWitch = e.target.closest('.arborescence-level__witch');
                            if( targetedWitch )
                            {
                                targetedWitch.classList.add('targeted-witch');

                                let menuArray = [
                                    { 
                                        url: targetedWitch.querySelector('a.arborescence-level__witch__name').href, 
                                        icon: this.icons.view, 
                                        text: "View witch" 
                                    }                                    
                                ];
                                if( targetedWitch.dataset.cauldron === 'true' ){
                                    menuArray.push( {
                                        url: this.cauldronUrl+'?id='+targetedWitch.dataset.id, 
                                        icon: this.icons.cauldronWitch, 
                                        text: "Edit cauldron" 
                                    } );
                                }
                                menuArray.push( {
                                    url: this.createUrl+'?id='+targetedWitch.dataset.id, 
                                    icon: this.icons.add, 
                                    text: "Create daughter" 
                                } );
                                menuArray.push( { icon: this.icons.cancel, text: "Cancel" }, );

                                this.triggerContextual( e, menuArray );

                                return;
                            }

                            targetedWitch = this.targetLastOpenWitch(e.x);
                            if( targetedWitch )
                            {
                                targetedWitch.classList.add('targeted-witch');
                                let nameDom = targetedWitch.querySelector('.arborescence-level__witch__name')

                                this.triggerContextual( e, [
                                    {
                                        url: this.createUrl+'?id='+targetedWitch.dataset.id,
                                        icon:  this.icons.add, 
                                        text: "Add daughter to " 
                                                + ( nameDom ? '"'+nameDom.innerHTML.trim()+'"': 'selected witch')
                                    },
                                    { icon: this.icons.cancel, text: "Cancel" },
                                ]);
                            }
                        }
                    );

                    document.addEventListener("click", 
                        e => {
                            let clickedMenu = e.target.closest('.arborescence-menu-context-menu');

                            if( !clickedMenu ){
                                this.closeDragAndContext();
                            }
                        }
                    );
                }
            )
            .then( this.scrollToLastLevel() );

            return this;            
        },
        targetLastOpenWitch: function( x )
        {
            let levels      = this.container.querySelectorAll('.arborescence-level');

            targetedWitch   =   levels[ 0 ].querySelector('.arborescence-level__witch');
            
            let threshold   =   levels[ 0 ].offsetLeft;
            threshold       +=  levels[ 0 ].offsetWidth;
            
            let position    =   x + this.container.scrollLeft; 

            for( let i = 1; i < levels.length; i++) 
            {

                threshold   =   levels[ i ].offsetLeft;
                threshold   +=  levels[ i ].offsetWidth;
                if( position > threshold )
                {
                    let selectedTarget = levels[ i ].querySelector('.arborescence-level__witch.selected');
                    
                    if( selectedTarget ){
                        targetedWitch = selectedTarget;
                    }
                }
            }
            
            return targetedWitch;
        },
        closeDragAndContext: function( )
        {
            this.container.querySelectorAll('.arborescence-menu-context-menu').forEach( 
                contextMenuDom => contextMenuDom.remove() 
            );

            this.container.querySelectorAll('.arborescence-level__position').forEach(
                positionDom => positionDom.remove()
            );

            this.container.querySelectorAll('.targeted-witch').forEach(
                dragOverDom => dragOverDom.classList.remove('targeted-witch')
            );                    

            this.dropped = false;
        },
        triggerContextual: function( e, menuItems )
        {
            this.container.querySelectorAll('.arborescence-menu-context-menu').forEach( 
                contextMenuDom => contextMenuDom.remove() 
            );

            let menu        = document.createElement('menu');

            menu.classList.add("arborescence-menu-context-menu");
            menu.style.left = e.x +'px';
            menu.style.top  = e.y +'px';

            menuItems.forEach(  
                item => {
                    let itemDom = document.createElement('li');
                    let aDom    = document.createElement('a');

                    if( item.icon )
                    {
                        let icon = document.createElement( item.icon.dom );
                        item.icon.classes.forEach(
                            iconClass => icon.classList.add( iconClass )
                        );
                        aDom.append( icon );
                    }

                    aDom.append( item.text );

                    if( item.url === undefined ){
                        aDom.addEventListener( 'click', () => {
                            this.closeDragAndContext();
                        });
                    }
                    else {
                        aDom.href = item.url;
                    }

                    itemDom.append( aDom );
                    menu.append( itemDom );
                }
            );

            this.container.append(menu);
        },
        addArborescenceLevel: async function( subTree )
        {
            let arborescenceLevelDom = document.createElement('div');
            arborescenceLevelDom.classList.add("arborescence-level");

            let current = this.currentId;

            subTree.forEach(  
                daughterData => {
                    let arborescenceLevelWitchDom   = document.createElement('div');
                    arborescenceLevelWitchDom.classList.add("arborescence-level__witch");
                    
                    if( daughterData.id === current ){
                        arborescenceLevelWitchDom.classList.add("current");
                    }
                    
                    arborescenceLevelWitchDom.dataset.id        = daughterData['id'];
                    arborescenceLevelWitchDom.dataset.cauldron  = daughterData['cauldron'];
                    arborescenceLevelWitchDom.dataset.invoke    = daughterData['invoke'];
                    
                    let witchIcon = false;

                    if( this.homeIds.includes(daughterData.id) ){
                        witchIcon = this.icons.homeWitch;
                    }
                    else if( daughterData['cauldron'] && daughterData['invoke'] ){
                        witchIcon = this.icons.fullWitch;
                    } 
                    else if( daughterData['cauldron'] ){
                        witchIcon = this.icons.cauldronWitch;
                    } 
                    else if( daughterData['invoke'] ){
                        witchIcon = this.icons.invokeWitch;
                    } 
                    else {
                        witchIcon = this.icons.basicWitch;
                    }
                    if( witchIcon )
                    {
                        let iDom = document.createElement( witchIcon.dom );
                        witchIcon.classes.forEach(
                            witchIconClass => iDom.classList.add( witchIconClass )
                        );
                        arborescenceLevelWitchDom.append(iDom);
                    }
    
                    let aDom = document.createElement('a');
                    aDom.classList.add("arborescence-level__witch__name");
    
                    if( daughterData['href'] !== undefined ){
                        aDom.setAttribute('href', daughterData['href']);
                    }
                    aDom.setAttribute('title', daughterData['description']);
                    aDom.innerHTML = daughterData['name'];
                    arborescenceLevelWitchDom.append(aDom);
                    
                    if( daughterData['daughters'].length > 0 )
                    {
                        let spanDom = document.createElement('span');
                        spanDom.classList.add("arborescence-level__witch__daughters-display");
                        
                        let toggleDom = document.createElement( this.icons.toggleDomClosed.dom );
                        this.icons.toggleDomClosed.classes.forEach(
                            toggleDomClass => toggleDom.classList.add( toggleDomClass )
                        );

                        spanDom.append(toggleDom);
                        arborescenceLevelWitchDom.append(spanDom);
    
                        spanDom.addEventListener( 'click', 
                            e => this.toggle( e.target )
                                    .then( this.scrollToLastLevel() )
                        );
                    }

                    // if dragagable main menu (root element excluded)
                    if( this.draggable && !this.homeIds.includes(daughterData.id) )
                    {
                        arborescenceLevelWitchDom.classList.add('draggable');
                        arborescenceLevelWitchDom.setAttribute('draggable', true);

                        arborescenceLevelWitchDom.addEventListener( 'dragstart', 
                            () => {
                                this.closeDragAndContext();

                                this.draggedId  = arborescenceLevelWitchDom.dataset.id;

                                let toggleDomOpen =   arborescenceLevelWitchDom.querySelector( 
                                    this.icons.toggleDomOpen.dom 
                                    + (this.icons.toggleDomOpen.classes && this.icons.toggleDomOpen.classes.length > 0? '.': '')
                                    + this.icons.toggleDomOpen.classes.join('.') 
                                );
                                
                                if( toggleDomOpen ){
                                    this.toggle( arborescenceLevelWitchDom.querySelector('.arborescence-level__witch__daughters-display') );
                                }
                            }
                        );
                        arborescenceLevelWitchDom.addEventListener( 'dragend', 
                            () => {
                                this.draggedId  = null;
                                if( !this.dropped ){
                                    this.closeDragAndContext();
                                }
                            }
                        );
                    }
                    
                    arborescenceLevelDom.append(arborescenceLevelWitchDom);
                }
            );

            if( this.draggable )
            {
                arborescenceLevelDom.addEventListener(
                    "dragenter",
                    e => {
                        e.preventDefault();

                        this.dragOver( e );
                    }
                );
                arborescenceLevelDom.addEventListener(
                    "dragover",
                    e => {
                        e.preventDefault();

                        this.dragOver( e );
                    }
                );
                arborescenceLevelDom.addEventListener(
                    "dragleave",
                    e => {
                        e.preventDefault();

                        this.dragleave( e.target );
                    }
                );
                arborescenceLevelDom.addEventListener(
                    "drop",
                    e => {
                        e.preventDefault();

                        this.dropped    = this.draggedId;
                        this.draggedId  = null;

                        if( !this.dropped ){
                            return;
                        }

                        let witch       = e.target.closest('.arborescence-level__witch');

                        if( witch && witch.dataset.id !== this.dropped )
                        {
                            let urlBase =   this.clipboardUrl;
                            urlBase     +=  "?dest="+witch.dataset.id;
                            urlBase     +=  "&origin="+this.dropped;

                            this.triggerContextual( e, [
                                { 
                                    url: urlBase+"&action=copy" + '#'+(this.urlHash ?? ""), 
                                    icon: this.icons.copy, 
                                    text: "Copy to witch" 
                                },
                                { 
                                    url: urlBase+"&action=move" + '#'+(this.urlHash ?? ""), 
                                    icon: this.icons.move, 
                                    text: "Move to witch" 
                                },
                                { icon: this.icons.cancel, text: "Cancel" },
                            ]);
                            
                            return;
                        }

                        let position    = e.target.closest('.arborescence-level__position');

                        if( position )
                        {
                            let positionLevel = e.target.closest('.arborescence-level');
                            
                            let match = false;
                            positionLevel.parentNode.childNodes.forEach(
                                level => {
                                    if( !match && level !== positionLevel ){
                                        witch = level.querySelector('.arborescence-level__witch.selected');
                                    }
                                    else if( level === positionLevel ){
                                        match = true;
                                    }
                                }
                            );

                            let textMoveAction = "Move to position";
                            let iconMoveAction = this.icons.move;
                            if( positionLevel.querySelector('.arborescence-level__witch[data-id="'+this.dropped+'"]') )
                            {
                                textMoveAction = "Change witches order";
                                iconMoveAction = this.icons.order;
                            }

                            let urlBase =   this.clipboardUrl;
                            urlBase     +=  "?dest="+witch.dataset.id;
                            urlBase     +=  "&origin="+this.dropped;

                            urlBase     +=  "&positionRef="+position.attributes.ref.value;
                            urlBase     +=  "&positionRel="+position.attributes.rel.value;
                            
                            this.triggerContextual( e, [
                                {
                                    url: urlBase+"&action=copy" + '#'+(this.urlHash ?? ""), 
                                    icon: this.icons.copy, 
                                    text: "Copy to position" 
                                },
                                { 
                                    url: urlBase+"&action=move" + '#'+(this.urlHash ?? ""), 
                                    icon: iconMoveAction, 
                                    text: textMoveAction 
                                },
                                { icon: this.icons.cancel, text: "Cancel" },
                            ]);
                            
                            return;
                        }

                        this.container.querySelectorAll('.arborescence-level__position').forEach(
                            positionDom => {
                                if( positionDom !== position ){
                                    positionDom.remove();
                                }
                            }
                        );

                        return;
                    }
                );
            }

            this.container.append( arborescenceLevelDom );

            this.scrollToLastLevel();

            return true;
        }, 
        dragOver: async function( e )
        {
            if( !this.draggedId ){
                return;
            }
            let witch       = e.target.closest('.arborescence-level__witch');
            let position    = e.target.closest('.arborescence-level__position');
            let level       = e.target.closest('.arborescence-level');

            if( witch 
                && witch.dataset.id !== (this.draggedId ?? 0) 
                && !witch.classList.contains('targeted-witch')  
            ){
                witch.classList.add('targeted-witch');

                // Temporisation to help user
                await new Promise(r => setTimeout(r, 1000));
                if( !witch.classList.contains('targeted-witch') ){
                    return;
                }

                let openDom = witch.querySelector('.arborescence-level__witch__daughters-display');
                if( openDom && !witch.classList.contains('selected') ){
                    this.toggle( witch.querySelector('.arborescence-level__witch__daughters-display') );
                }

                this.container.querySelectorAll('.arborescence-level__position').forEach(
                    positionDom => positionDom.remove()
                );

                let firstLevel = this.container.querySelector('.arborescence-level');

                if( level !== firstLevel )
                {
                    if( !witch.previousSibling || this.draggedId !== witch.previousSibling.dataset.id )
                    {
                        let positionTop = document.createElement('div');
                        positionTop.classList.add('arborescence-level__position');
                        positionTop.setAttribute('rel', 'before');
                        positionTop.setAttribute('ref', witch.dataset.id);
                        level.insertBefore(  positionTop, witch );  
                    }
                    
                    if( !witch.nextSibling || this.draggedId !== witch.nextSibling.dataset.id )
                    {
                        let positionBottom = document.createElement('div');
                        positionBottom.classList.add('arborescence-level__position');
                        positionBottom.setAttribute('rel', 'after');
                        positionBottom.setAttribute('ref', witch.dataset.id);
                        witch.after(positionBottom);
                    }                
                }
            }
            else if( position && !position.classList.contains('targeted-witch') )
            {
                position.classList.add('targeted-witch');

                this.container.querySelectorAll('.arborescence-level__position').forEach(
                    positionDom => {
                        if( positionDom !== position ){
                            positionDom.remove();
                        }
                    }
                );
            }
            else 
            {
                // ADD LEVEL add icon to witch list
                // level.classList.add('targeted-witch');
                // targetWitch = this.targetLastOpenWitch(e.x);
            }

            return;
        },
        dragleave: function( targetDom )
        {
            let targetedWitch = targetDom.closest('.targeted-witch');
            if( targetedWitch ){
                targetedWitch.classList.remove('targeted-witch');
            }

            return;
        },
        open: async function( path )
        {
            path.forEach( 
                async pathWitchId => {
                    let daughterTriggerSelector =   '.arborescence-level ';
                    daughterTriggerSelector     +=  '.arborescence-level__witch[data-id="' + pathWitchId + '"] ';
                    daughterTriggerSelector     +=  '.arborescence-level__witch__daughters-display ';
                    
                    let daughterTrigger = document.querySelector(daughterTriggerSelector);
                    if( daughterTrigger ){
                        await this.toggle( document.querySelector(daughterTriggerSelector) );
                    }
                }
            );

            return true;
        },
        scrollToLastLevel: async function()
        {
            if( this.container && this.container.lastChild ){
                this.container.lastChild.scrollIntoView({ 
                    behavior: "smooth", 
                    block: "start", 
                    container: "nearest", 
                    inline: "end" 
                });
                //this.container.lastChild.scrollIntoView({ behavior: "smooth", block: "start", inline: "end" });
                //this.container.lastChild.scrollIntoView({ behavior: "smooth", inline: "end" });
            }
        },
        toggle: async function( target )
        {
            let currentLevel    = target.closest('.arborescence-level');
            let currentWitch    = target.closest('.arborescence-level__witch');

            let expand          = true;
            if( currentWitch && currentWitch.classList.contains('selected') ){
                expand = false;
            }

            let matchedLevel    = false;
            this.container.querySelectorAll('.arborescence-level').forEach(
                level => {
                    if( matchedLevel ){
                        level.remove();
                    }

                    if( currentLevel === level )
                    {
                        matchedLevel = true;
                        let selectedWitch = level.querySelector('.arborescence-level__witch.selected');
                        if( selectedWitch ){
                            selectedWitch.classList.remove('selected');
                        }
                        
                        let toggleDomOpen =   level.querySelector( 
                            this.icons.toggleDomOpen.dom 
                            + (this.icons.toggleDomOpen.classes && this.icons.toggleDomOpen.classes.length > 0? '.': '')
                            + this.icons.toggleDomOpen.classes.join('.') 
                        );

                        if( toggleDomOpen )
                        {
                            let toggleDomClosed = document.createElement( this.icons.toggleDomClosed.dom );
                            this.icons.toggleDomClosed.classes.forEach(
                                toggleDomClass => toggleDomClosed.classList.add( toggleDomClass )
                            );

                            toggleDomOpen.after( toggleDomClosed );
                            toggleDomOpen.remove();
                        }
                    }
                }
            );

            if( expand )
            {
                let witchId     = parseInt( currentWitch.dataset.id );
                let subTree     = this.treeData;
                let daughters   = [];
                
                this.container.querySelectorAll('.arborescence-level .arborescence-level__witch.selected').forEach( 
                    element => {
                        let subTreeId = parseInt( element.dataset.id );

                        if( subTreeId !== witchId )
                        {
                            subTree.forEach( wElmnt => {
                                if( wElmnt.id === subTreeId ){
                                    daughters = wElmnt.daughters;
                                }
                            });
                            subTree = daughters ?? [];
                        }
                    }
                );
                currentWitch.classList.add('selected');

                subTree.forEach( wElmnt => {
                    if( wElmnt.id === witchId ){
                        daughters = wElmnt.daughters;
                    }
                });
                subTree = daughters ?? [];
                
                let toggleDomClosed = currentWitch.querySelector( 
                    this.icons.toggleDomClosed.dom 
                    + (this.icons.toggleDomClosed.classes && this.icons.toggleDomClosed.classes.length > 0? '.': '')
                    + this.icons.toggleDomClosed.classes.join('.') 
                );
                
                if( toggleDomClosed )
                {
                    let toggleDomOpen = document.createElement( this.icons.toggleDomOpen.dom );
                    this.icons.toggleDomOpen.classes.forEach(
                        toggleDomClass => toggleDomOpen.classList.add( toggleDomClass )
                    );

                    toggleDomClosed.after( toggleDomOpen );
                    toggleDomClosed.remove();
                }

                this.addArborescenceLevel( subTree );
            }

            return true;
        }
    };
};

document.addEventListener("DOMContentLoaded", () => {
    var arborescenceMenuArray = [];
    
    for( let [key, data] of Object.entries(arborescencesInputs) ){
        arborescenceMenuArray[ key ] = (ArborescenceMenu( key )).init( data );
    }
});
