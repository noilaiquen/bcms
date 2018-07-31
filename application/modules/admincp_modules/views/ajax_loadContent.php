<table class="table table-bordered table-striped dataTable">
    <thead>
        <tr role="row">
            <th class="center">No.</th>
            <th class="center"><input type="checkbox" id="selectAllItems" onclick="selectAllItems(<?=count($results)?>)"></th>
            <th class="sorting" onclick="sort('name')" id="name">Name</th>
            <th class="center sorting" onclick="sort('site')" id="site">Site</th>
            <th class="center sorting" onclick="sort('status')" id="status">Status</th>
            <th class="center sorting" onclick="sort('created')" id="created">Created</th>
        </tr>
    </thead>
    <tbody>
        <?php if(!empty($results)){
            $i = 0;
            foreach($results as $key => $result) { ?>
                <tr class="item_row<?=$i?>">
                    <td class="num_row center"><?=$key + 1?></td>
                    <td class="num_row center"><input type="checkbox" id="item<?=$i?>" onclick="selectItem(<?=$i?>)" value="<?=$result['id']?>"></td>
                    <td><a href="javascript:void(0)"><?=$result['name']?></a></td>
                    <td class="center"><?=$result['site']?></td>
                    <td class="center" style="width: 150px" id="updateStatus<?=$result['id']?>">
                        <a href="javascript:void(0)" onclick="updateStatus('<?=$result['id']?>', '<?=$result['status']?>')">
                            <span class="badge bg-<?=$result['status']==1?'success':'danger';?>"><?=$result['status']==1?'Active':'Disable';?></span>
                        </a>
                    </td>
                    <td class="center" style="width: 150px"><?=$result['created'];?></td>
                </tr>
            <?php  $i++; }
        } else { ?>
            <tr>
                <td colspan="6" class="center">No data!</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<div>
    <?=$this->adminpagination->create_links();?>
</div>
