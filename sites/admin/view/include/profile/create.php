<?php /** @var WW\Module $this */ ?>

<div class="box create__profile">
   <form id="create-profile-form" method="post" >
        <h3>
            <i class="fas fa-user"></i>
            <input type="text" 
                   class="profile-name"
                   name="profile-name" 
                   placeholder="enter new profile name"
                   value="" />
        </h3>
        <p>
            <em>
                Scope 
                <select name="profile-site" 
                        class="profile-site">
                    <option value="*">
                        All sites
                    </option>
                    <?php foreach( $websitesList as $website ): ?>
                        <option value="<?=$website->site ?>">
                            <?=$website->site ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </em>
        </p>
        
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Status</th>
                    <th>Position Witch</th>
                    <th>Position Rules</th>
                    <th>Custom</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
                <tr class="policy-container policy-pattern">
                    <td>
                        <input type="hidden" name="policy-id[]" class="policy-id" value="-1" />
                        
                        <div  class="profile-site-displayed profile-site-all">
                            <select name="policy-module[all][]" data-init="*">
                                <option value="*">
                                    All modules
                                </option>
                                <?php foreach( $allSitesModulesList as $moduleItem ): ?>
                                    <option value="<?=$moduleItem ?>">
                                        <?=$moduleItem ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <?php foreach( $websitesList as $site => $website ): ?>
                            <div style="display: none;" class="profile-site-displayed profile-site-<?=$site ?>">
                                <select name="policy-module[<?=$site ?>][]" data-init="*">
                                    <option value="*">
                                        All modules
                                    </option>
                                    <?php foreach( $website->listModules() as $moduleItem ): ?>
                                        <option value="<?=$moduleItem ?>">
                                            <?=$moduleItem ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </td>
                    
                    <td>
                        <div class="profile-site-displayed profile-site-all">
                            <select name="policy-status[all][]" data-init="*">
                                <option value="*">
                                    All status
                                </option>
                                <?php foreach( $statusGlobal as $statusKey => $statusLabel ): ?>
                                    <option value="<?=$statusKey ?>">
                                        <?=$statusLabel ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <?php foreach( $websitesList as $site => $website ): ?>
                            <div style="display: none;" class="profile-site-displayed profile-site-<?=$site ?>">
                                <select name="policy-status[<?=$site ?>][]" data-init="*">
                                    <option value="*">
                                        All status
                                    </option>
                                    <?php foreach( $website->status as $statusKey => $statusLabel ): ?>
                                        <option value="<?=$statusKey ?>">
                                            <?=$statusLabel ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </td>
                    
                    <td>
                        <button class="policy-witch">
                            <i class="fa fa-sitemap"></i>
                            Choose Witch
                        </button>

                        <a  style="display: none;"
                            href="<?=$this->ww->website->getUrl("/view?id=") ?>"
                            class="policy-witch-display"
                            target="_blank">                            
                        </a>
                        
                        <a  style="display: none;"
                            class="unset-policy-witch">
                            <i class="fa fa-times"></i>
                        </a>

                        <input  type="hidden" 
                                value="" 
                                name="policy-witch-id[]" 
                                class="policy-witch-id" />
                    </td>
                    <td>
                        <ul class="policy-witch-set" style="display: none;">
                            <li>
                                <input type="checkbox" 
                                       name="policy-witch-rules-ancestors[]"
                                       value="-1" />
                                <label>Parents</label>
                            </li>
                            <li>
                                <input type="checkbox" 
                                       name="policy-witch-rules-self[]"
                                       value="-1"
                                       checked />
                                <label>Self</label>
                            </li>
                            <li>
                                <input type="checkbox" 
                                       name="policy-witch-rules-descendants[]"
                                       value="-1"
                                       checked />
                                <label>Descendants</label>
                            </li>
                        </ul>
                    </td>
                    <td>
                        <textarea name="policy-custom[]"></textarea>
                    </td>
                    <td>
                        <a class="text-center policy-remove">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
                
            </tbody>
        </table>

        <div class="box__actions">
           <button class="reset-profile-action">
               <i class="fa fa-undo"></i>
               Reset
           </button>
           <button class="add-policy-action">
               <i class="fa fa-plus"></i>
               Add new policy
           </button>
           <button class="trigger-action"
                   data-action="create-profile"
                   data-target="create-profile-form">
               <i class="fas fa-save"></i>
               Create
           </button>
       </div>
   </form>
</div>