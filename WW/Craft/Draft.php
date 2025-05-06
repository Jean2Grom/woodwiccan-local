<?php
namespace WW\Craft;

use WW\Craft;
use WW\Structure;
use WW\DataAccess\Craft as CraftDA;
use WW\DataAccess\WitchCrafting;

/**
 * class to handle Draft crafts
 * 
 * @author Jean2Grom
 */
class Draft extends Craft 
{
    const TYPE      = 'draft';
    const DB_FIELDS = [
        "`content_key` int(11) DEFAULT NULL",
    ];
    const ELEMENTS = [ 
        'content_key',
    ];
    
    public $content_key;
    
    function createDraft(){
        return clone $this;
    }
    
    function getDraft(){
        return $this;
    }
    
    function publish()
    {
        $this->ww->db->begin();
        try {
            $structure      = new Structure( $this->ww, $this->structure->name, Content::TYPE );
            
            // No content or archive exist
            if( !$this->content_key ){
                $content = $this->publishNewContent( $structure );
            }
            else 
            {
                $craftData  = WitchCrafting::getCraftDataFromIds($this->ww, $structure->table, [ $this->content_key ]);
                $data       = array_values($craftData)[0] ?? null;
                
                if( $data ){
                    $content = $this->publishUpdatedContent( $structure, $data );
                }
                else {
                    $content = $this->publishRestoredContent( $structure );
                }
            }
            
            $this->ww->cairn->setCraft($content, $this->structure->table, $this->id);
            
            $this->delete( false );            
        }
        catch( \Exception $e )
        {
            $this->ww->log->error($e->getMessage());
            $this->ww->db->rollback();
            return false;
        }
        $this->ww->db->commit();
        
        return true;
    }
    
    private function publishNewContent( Structure $structure )
    {
        $content        = Craft::factory( $this->ww, $structure );
        
        $content->name          = $this->name;
        $content->attributes    = $this->attributes;            
        $content->save();
        
        foreach( $this->getWitches() as $witch ){
            $witch->edit([ 'craft_table' => $structure->table, 'craft_fk' => $content->id ]);
        }
        
        return $content;
    }
    
    private function publishUpdatedContent( Structure $structure, array $data )
    {
        $content = Craft::factory( $this->ww, $structure, $data );
            
        $content->archive( true );
            
        $content->name          = $this->name;
        $content->attributes    = $this->attributes;            
        $content->save();
        
        return $content;
    }
    
    private function publishRestoredContent( Structure $structure )
    {
        $content = Craft::factory( $this->ww, $structure );
        
        $content->name          = $this->name;
        $content->attributes    = $this->attributes;            
        $content->save();
        
        foreach( $this->getWitches(Archive::TYPE) as $witch ){
            $witch->edit(['craft_table' => $structure->table, 'craft_fk' => $content->id]);
        }
        
        CraftDA::update( $this->ww, $this->structure->table, ['content_key' => $content->id], ['content_key' => $this->content_key] );
        CraftDA::update( $this->ww, Archive::TYPE.'__'.$this->structure->name, ['content_key' => $content->id], ['content_key' => $this->content_key] );
        
        return $content;
    }
    
    function remove()
    {
        if( !$this->content_key ){
            foreach( $this->getWitches() as $witch ){
                $witch->edit([ 'craft_table' => null, 'craft_fk' => null ]);
            }
        }
        
        return $this->delete();
    }
}