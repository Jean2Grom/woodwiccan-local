<?php
namespace WW\Craft;

use WW\Craft;
use WW\Structure;

/**
 * class to handle Content crafts
 * 
 * @author Jean2Grom
 */
class Content extends Craft 
{
    const TYPE          = 'content';    
    const DB_FIELDS     = [];
    const ELEMENTS      = [];
    
    function archive( bool $historyMode=false )
    {
        $this->ww->db->begin();
        try {
            $structure      = new Structure( $this->ww, $this->structure->name, Archive::TYPE );
            
            $newArchiveId   = $structure->createCraft($this->name);
            $archive        = Craft::factory( $this->ww, $structure );
            
            $archive->id            = $newArchiveId;
            $archive->name          = $this->name;
            $archive->content_key   = $this->id;
            $archive->attributes    = $this->attributes;
            $archive->save();
            
            if( !$historyMode )
            {
                foreach( $this->getWitches() as $witch ){
                    $witch->edit(['craft_table' => $structure->table, 'craft_fk' => $newArchiveId]);
                }
                
                $this->ww->cairn->setCraft($archive, $this->structure->table, $this->id);
                
                $this->delete( false );
            }
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
}
