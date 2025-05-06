<?php
namespace WW\Cauldron;

use WW\Cauldron;
use WW\DataAccess\IngredientDataAccess as DataAccess;


class FileCauldron extends Cauldron
{
    /** @var string[] */
    public array $pendingRemoveFiles = [];

    function readInputs( mixed $inputs=null ): self
    {
        $storagePath    = $this->content('storage-path')->value();
        $return         = parent::readInputs( $inputs );
        $newStoragePath = $this->content('storage-path')->value();

        if( $newStoragePath !== $storagePath ){
            $this->pendingRemoveFiles[] = $storagePath;
        }

        return $return;
    }

    protected function saveAction(): bool 
    {
        if( !parent::saveAction() ){
            return false;
        }

        $this->removePendingFiles();

        return true;
    }

    protected function deleteAction(): bool
    {
        $this->pendingRemoveFiles[] = $this->content('storage-path')->value();

        $this->removePendingFiles();
        
        return parent::deleteAction();
    }

    private function removePendingFiles(): void 
    {
        $storage = $this->ww->configuration->storage();
        foreach( $this->pendingRemoveFiles as $removeFile )
        {
            if( !is_file($storage.'/'.$removeFile) ){
                continue;
            }

            if( DataAccess::searchValueCount($this->content('storage-path'), $removeFile) === 0 ){
                unlink($storage.'/'.$removeFile);
            }
        }

        $this->pendingRemoveFiles = [];

        return;
    }

}

